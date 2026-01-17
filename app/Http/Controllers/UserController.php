<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Users/Index', [
            'users' => User::with('roles:id,name')
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', Role::SUPER_ADMIN);
                })
                ->select('id', 'name', 'email')
                ->paginate(10),

            'roles' => Role::where('name', '!=', Role::SUPER_ADMIN)
                ->select('id', 'name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->back()->with('success', 'User created');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->update($data);
        $user->roles()->sync($data['roles'] ?? []);

        cache()->forget("user:{$user->id}:perm:*");

        return back();
    }


    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted');
    }
}
