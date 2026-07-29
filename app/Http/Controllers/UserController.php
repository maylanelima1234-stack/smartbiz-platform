<?php

namespace App\Http\Controllers;

use App\Core\Authorization\Services\UserAccessService;
use App\Models\Company;
use App\Models\SmartNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct(private readonly UserAccessService $accessService) {}
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $users = User::query()
            ->with('company')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('users.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['password'] = Hash::make($data['password']);
        $data = $this->normalizeCompanyScope($data);
        unset($data['password_confirmation'], $data['remove_avatar']);

        $user = DB::transaction(function () use ($data, $request) {
            $user = User::create($data);
            $this->accessService->sync($user, $user->company_id, $user->role, $request->user()?->id);
            return $user;
        });

        if (class_exists(SmartNotification::class)) {
            SmartNotification::pushFor($request->user(), 'Usuário cadastrado', $user->name.' foi adicionado à equipe.', 'user', route('users.show', $user), 'U');
        }

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function show(User $user)
    {
        $user->load('company');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $companies = Company::orderBy('name')->get();
        return view('users.edit', compact('user', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate($this->rules($user));

        if ($request->boolean('remove_avatar')) {
            $this->deleteAvatar($user->avatar);
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            $this->deleteAvatar($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data = $this->normalizeCompanyScope($data);
        unset($data['password_confirmation'], $data['remove_avatar']);
        DB::transaction(function () use ($user, $data, $request): void {
            $user->update($data);
            $this->accessService->sync($user, $user->company_id, $user->role, $request->user()?->id);
        });

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $this->deleteAvatar($user->avatar);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso!');
    }

    private function rules(?User $user = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'.($user ? ','.$user->id : '')],
            'phone' => ['nullable','string','max:20'],
            'position' => ['nullable','string','max:255'],
            'company_id' => ['nullable','exists:companies,id'],
            'role' => ['required','string','max:50'],
            'status' => ['required','in:Ativo,Bloqueado'],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'remove_avatar' => ['nullable','boolean'],
            'password' => [$user ? 'nullable' : 'required','string','min:6','confirmed'],
        ];
    }


    private function normalizeCompanyScope(array $data): array
    {
        if (in_array($data['role'] ?? null, ['super_admin', 'admin_cto', 'admin_tm'], true)) {
            $data['company_id'] = null;
        }

        return $data;
    }

    private function deleteAvatar(?string $avatar): void
    {
        if (!$avatar || filter_var($avatar, FILTER_VALIDATE_URL)) {
            return;
        }

        $path = ltrim(str_replace(['/storage/', 'storage/'], '', $avatar), '/');
        Storage::disk('public')->delete($path);
    }
}
