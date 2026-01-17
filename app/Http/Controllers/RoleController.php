<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Role::class);

        return Inertia::render('Roles/Index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Role::class);

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->permissions()->sync($data['permissions'] ?? []);

        return back();
    }

    public function update(Request $request, Role $role)
    {
        Gate::authorize('update', $role);

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $data['name']]);
        $role->permissions()->sync($data['permissions'] ?? []);

        return back();
    }

    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        $role->delete();

        return back();
    }
}
