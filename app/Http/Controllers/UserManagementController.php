<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::query()
            ->with('company')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'position' => ['nullable', 'string', 'max:120'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'manager', 'commercial', 'finance', 'support', 'client'])],
            'status' => ['required', Rule::in(['Ativo', 'Bloqueado'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário cadastrado com sucesso!');
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
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'position' => ['nullable', 'string', 'max:120'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'manager', 'commercial', 'finance', 'support', 'client'])],
            'status' => ['required', Rule::in(['Ativo', 'Bloqueado'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Você não pode excluir o próprio usuário logado.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário removido com sucesso!');
    }
}
