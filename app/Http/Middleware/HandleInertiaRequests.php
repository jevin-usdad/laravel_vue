<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'name' => config('app.name'),

            'auth' => [
                'user' => $user,
                'can' => [
                    'users' => [
                        'view' => $user?->hasPermission('users.view') ?? false,
                        'create' => $user?->hasPermission('users.create') ?? false,
                        'edit' => $user?->hasPermission('users.edit') ?? false,
                        'delete' => $user?->hasPermission('users.delete') ?? false,
                    ],
                    'roles' => [
                        'view' => $user?->hasPermission('roles.view') ?? false,
                        'create' => $user?->hasPermission('roles.create') ?? false,
                        'edit' => $user?->hasPermission('roles.edit') ?? false,
                        'delete' => $user?->hasPermission('roles.delete') ?? false,
                    ],
                ],
            ],

            'sidebarOpen' =>
            ! $request->hasCookie('sidebar_state')
                || $request->cookie('sidebar_state') === 'true',
        ]);
    }
}
