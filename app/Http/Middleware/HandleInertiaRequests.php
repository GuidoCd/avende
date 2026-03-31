<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
                'preferences' => $request->user() ? $request->user()->preferences : null,
                'avatar_url' => function () use ($request) {
                    $user = $request->user();
                    if (!$user) return null;
                    if ($user->avatar) {
                        $diskName = app()->environment('local', 'testing') ? 'public' : 'r2_public';
                        return \Illuminate\Support\Facades\Storage::disk($diskName)->url($user->avatar);
                    }
                    return $user->getFirstMediaUrl('avatar') ?: null;
                },
                'roles' => $request->user() ? $request->user()->getRoleNames() : [],
                'permissions' => $request->user() ? $request->user()->getAllPermissions()->pluck('name') : [],
            ],
            'locale' => function () {
                return session('locale', config('app.locale', 'es'));
            },
            'translations' => function () {
                $locale = session('locale', config('app.locale', 'es'));
                $path = lang_path($locale . '.json');
                return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
            },
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
