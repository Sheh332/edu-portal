<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions', 'users'));
    }

    /** Create a custom role with a set of permissions. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', "Role '{$data['name']}' created.");
    }

    /** Update the permissions assigned to a role. */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', "Permissions updated for '{$role->name}'.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['admin', 'student'])) {
            return back()->with('error', 'Core roles cannot be deleted.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted.');
    }

    /** Assign a single role to a user. */
    public function assign(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        User::findOrFail($data['user_id'])->syncRoles([$data['role']]);

        return back()->with('success', 'Role assigned to user.');
    }
}
