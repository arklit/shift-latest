<?php

    namespace App\Http\Controllers;

    use App\Helpers\DebugNotificationHelper;
    use App\Helpers\LoggerHelper;
    use App\Services\SitemapGenerator;

    class SitemapController extends Controller
    {
        public function __invoke()
        {
            try {
                $generator = new SitemapGenerator();
                $map = $generator->generateMap();
                return response($map,200, ['Content-Type' => 'application/xml']);
            } catch (\Exception $e) {
                LoggerHelper::commonErrorVerbose($e);
                DebugNotificationHelper::sendVerboseErrorEmail($e);
                abort(500);
            }
        }
    }
