<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param Request $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        $client = auth(Client::GUARD)->user();
        abort_if(empty($client), Response::HTTP_UNAUTHORIZED);
        $request->request->add(['client' => $client]);

        return $next($request);
    }
}
