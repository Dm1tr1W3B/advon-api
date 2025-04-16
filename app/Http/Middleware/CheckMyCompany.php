<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMyCompany
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $company = auth()->user()->company;
        if (!$company)
            return response()->json(['non_field_error' => [__("The company does not exist")]], 400);

        return $next($request);
    }
}
