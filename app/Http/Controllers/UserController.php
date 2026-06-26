<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage users');
    }

    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
            'is_active'=> ['boolean'],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($data['role']);

        ActivityLogger::log('create', "Created user: {$user->name} ({$data['role']})", 'User', $user);

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" has been created.");
    }

    public function edit(User $user)
    {
        $roles       = Role::all();
        $currentRole = $user->roles->first()?->name;

        return view('users.edit', compact('user', 'roles', 'currentRole'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
            'is_active'=> ['boolean'],
        ]);

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'is_active' => $request->boolean('is_active'),
            ...(isset($data['password']) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        $user->syncRoles([$data['role']]);

        ActivityLogger::log('update', "Updated user: {$user->name}", 'User', $user);

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" has been updated.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        ActivityLogger::log('delete', "Deleted user: {$name}", 'User', $user);

        return redirect()->route('users.index')
            ->with('success', "User \"{$name}\" has been deleted.");
    }
}
