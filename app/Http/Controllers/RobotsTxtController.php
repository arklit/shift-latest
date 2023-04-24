<?php

    namespace App\Http\Controllers;

    use Illuminate\Support\Facades\Storage;

    class RobotsTxtController extends Controller
    {
        public function __invoke()
        {
            $content = app()->isProduction()
                ? Storage::disk('public')->get('robots.txt')
                : 'User-agent: *'. PHP_EOL . 'Disallow: /';
            $content = $content ?? '';
            return response($content, 200)->header('Content-Type', 'text/plain');
        }
    }
