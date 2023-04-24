<?php

namespace App\Http\Controllers;

use App\Services\SitemapGenerator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    protected string $managersGuard = 'web';
    protected ?Authenticatable $manager;

    public function __invoke()
    {
        abort_if(!auth($this->managersGuard)->check(), 404);



//        $file =    public_path('/robots.txt');

        dd(
            Storage::disk('publicity')->exists('/robots.txt'),
//            file_exists($file)
        );

//        $fileName = '/sitemap.xml';
//        $generator = new SitemapGenerator();
//        $compareTime = Carbon::now()->subDay()->timestamp;
//        $disk = Storage::disk('public');
//
//        if (!$disk->exists($fileName) || $disk->lastModified($fileName) < $compareTime) {
//            $mapContent = $generator->generateMap();
//            $disk->put($fileName, $mapContent);
//        } else {
//            $mapContent = $disk->get($fileName);
//        }
//        return response($mapContent, 200, ['Content-Type' => 'application/xml']);
    }
}
