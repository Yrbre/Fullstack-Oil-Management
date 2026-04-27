<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    protected $roleAccess = [
        'admin'     => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'adjustment-stock', 'store-adjustment-stock', 'detail'],
        'manager'   => ['index', 'create', 'store', 'edit', 'update', 'adjustment-stock', 'store-adjustment-stock', 'detail'],
        'staff'     => ['index', 'create', 'store', 'adjustment-stock', 'store-adjustment-stock', 'detail'],
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Anda Harus Login Terlebih Dahulu');
        }

        $user = auth()->user();
        $designation = $user->designation;

        if (!in_array($designation, $roles)) {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Akses Untuk Halaman Ini');
        }

        $routeAction = last(explode('.', $request->route()->getName()));

        $allowedActions = $this->roleAccess[$designation] ?? [];

        if (!in_array($routeAction, $allowedActions)) {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Akses Untuk Melakukan Aksi Ini');
        }


        return $next($request);
    }
}
