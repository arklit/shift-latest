<?php

namespace App\Http\Middleware;

use App\Models\Seo;
use Closure;
use Illuminate\Http\Request;

class GetSeoData
{
    public function handle(Request $request, Closure $next)
    {
        $url = request()->path();
        $seo = Seo::query()->where('url', $url)->first();
//        dump($url, $seo);

        view()->composer(['welcome'],
            function ($view) use ($seo) {
                $view->with(compact('seo'));
            }
        );

        return $next($request);
    }
}
