<?php

namespace App\Http\Middleware;

use App\Repositories\CommonRepository;
use App\Services\ConfiguratorService;
use Closure;
use Illuminate\Http\Request;

class GetCommonDateAtStartup
{
    public function handle(Request $request, Closure $next)
    {
        $configsData = CommonRepository::take()->getConfigurationData();
        $config = new ConfiguratorService($configsData);

        if (!empty($configsData)) {
            $configs = [];
            foreach ($configsData as $datum) {
                $configs['rocont.' . $datum['key']] = $datum['value'];
            }
            config($configs);
        }

        view()->composer('*',
            function ($view) use ($config) {
                $view->with(compact('config'));
            }
        );

        return $next($request);
    }
}
