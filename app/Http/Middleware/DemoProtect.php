<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoProtect
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('demo.enabled')) {
            return $next($request);
        }

        $isUserCreate = $request->is('admin/users')
            && $request->isMethod('post');

        $isUserUpdate = $request->is('admin/users/*')
            && in_array($request->method(), ['PUT', 'PATCH']);

        $isPasswordUpdate = $request->is('account-password-update')
            && $request->isMethod('post');

        if ($isUserCreate || $isUserUpdate || $isPasswordUpdate) {
            $msg = config('demo.message');
            if ($request->expectsJson()) {
                return response()->json(['error' => $msg], 403);
            }
            return back()->withErrors(['demo' => $msg]);
        }

        return $next($request);
    }
}
