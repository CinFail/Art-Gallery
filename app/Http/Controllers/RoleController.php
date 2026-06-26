<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage roles');
    }

    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'general');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'guard_name'  => 'web',
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        ActivityLogger::log('create', "Created role: {$role->name}", 'Role');

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$role->name}\" has been created.");
    }

    public function edit(Role $role)
    {
        $permissions        = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'general');
        $assignedPermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'assignedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        ActivityLogger::log('update', "Updated role: {$role->name}", 'Role');

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$role->name}\" has been updated.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Administrator', 'Staff', 'Viewer'])) {
            return back()->with('error', 'Default system roles cannot be deleted.');
        }

        $name = $role->name;
        $role->delete();

        ActivityLogger::log('delete', "Deleted role: {$name}", 'Role');

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$name}\" has been deleted.");
    }
}
