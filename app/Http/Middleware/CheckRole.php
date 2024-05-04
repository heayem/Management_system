<?php
namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Redirect;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = $request->user()->userRole;
        if (in_array($userRole, $roles)) {
            return Redirect::back()->with('error', 'Unauthorized action');
        }
        return $next($request);
    }

}