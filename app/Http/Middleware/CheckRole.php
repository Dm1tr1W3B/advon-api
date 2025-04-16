<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $roleName
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roleName)
    {
        $role = Role::find(auth()->user()->role_id);
        if($role->name !=  $roleName)
            return response()->json(['non_field_error' => [__('auth.Access denied for users with role') . $role->display_name.'.']], 400);

        return $next($request);
    }
}
