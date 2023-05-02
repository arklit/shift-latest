<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Imagick;
use Orchid\Attachment\File;
use Orchid\Attachment\Models\Attachment;
use Orchid\Platform\Dashboard;
use Orchid\Platform\Events\UploadedFileEvent;
use Orchid\Platform\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class FilesController extends Controller
{
    /**
     * @var Attachment
     */
    protected $attachment;

    /**
     * AttachmentController constructor.
     */
    public function __construct()
    {
        $this->checkPermission('platform.systems.attachment');
        $this->attachment = Dashboard::modelClass(Attachment::class);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        foreach ($request->files->all() as $file) {
            $fileItem['mimeType'] = $file->getClientMimeType();
            $fileItem['originalName'] = $file->getClientOriginalName();
            $fileItem['pathName'] = $file->getPathname();
            $files[] = $this->cropImage($fileItem, $request->acceptedFiles === 'image/png');
        }

        $files = $files ?? $request->allFiles();

        $attachment = collect($files)
            ->flatten()
            ->map(fn(UploadedFile $file) => $this->createModel($file, $request));

        $response = $attachment->count() > 1 ? $attachment : $attachment->first();

        return response()->json($response);
    }

    public function cropImage($fileItem, $isPng = false)
    {
        $imagickObj = new Imagick();
        $imagickObj->readImage($fileItem['pathName']);
        if (!$isPng) {
            $imagickObj->setImageBackgroundColor('white');
            $imagickObj->setImageFormat('jpg');
            $imagickObj->setImageCompression(Imagick::COMPRESSION_JPEG);
            $fileItem['mimeType'] = 'image/jpeg';
        }
        $imagickObj->writeImage($fileItem['pathName']);
        return new UploadedFile(
            $fileItem['pathName'],
            $fileItem['originalName'],
            $fileItem['mimeType']
        );
    }

    /**
     * @param Request $request
     */
    public function sort(Request $request): void
    {
        collect($request->get('files', []))
            ->each(function ($sort, $id) {
                $attachment = $this->attachment->find($id);
                $attachment->sort = $sort;
                $attachment->save();
            });
    }

    /**
     * Delete files.
     *
     * @param string $id
     * @param Request $request
     */
    public function destroy(string $id, Request $request): void
    {
        $storage = $request->get('storage', 'public');
        $this->attachment->findOrFail($id)->delete($storage);
    }

    /**
     * @param string $id
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function update(string $id, Request $request)
    {
        $attachment = $this->attachment
            ->findOrFail($id)
            ->fill($request->all());

        $attachment->save();

        return response()->json($attachment);
    }

    /**
     * @param UploadedFile $file
     * @param Request $request
     *
     * @return mixed
     * @throws BindingResolutionException
     *
     */
    private function createModel(UploadedFile $file, Request $request)
    {
        $file = resolve(File::class, [
            'file' => $file,
            'disk' => $request->get('storage'),
            'group' => $request->get('group'),
        ]);

        if ($request->has('path')) {
            $file->path($request->get('path'));
        }

        $model = $file->load();
        $model->url = $model->url();

        event(new UploadedFileEvent($model));

        return $model;
    }

    /**
     * @return JsonResponse
     */
    public function media(): JsonResponse
    {
        $attachments = $this->attachment->filters()->paginate(12);
        return response()->json($attachments);
    }
}
