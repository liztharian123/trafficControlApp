<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDepartment
{
    public function handle(Request $request, Closure $next, string $slug): Response
    {
        if (! $request->user()?->inDepartment($slug)) {
            abort(403, 'Your department does not have access to this area.');
        }

        return $next($request);
    }
}
