<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\SmartNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'role' => 'required|string|max:50',
            'status' => 'required|in:Ativo,Bloqueado',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'position' => $data['position'] ?? null,
            'company_id' => $data['company_id'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'],
            'password' => Hash::make($data['password']),
        ]);

        SmartNotification::pushFor(
            $request->user(),
            'Usuário cadastrado',
            $user->name . ' foi adicionado à equipe.',
            'user',
            route('users.show', $user),
            'U'
        );

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $companies = Company::orderBy('name')->get();

        return view('users.edit', compact('user', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'role' => 'required|string|max:50',
            'status' => 'required|in:Ativo,Bloqueado',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'position' => $data['position'] ?? null,
            'company_id' => $data['company_id'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso!');
    }
}