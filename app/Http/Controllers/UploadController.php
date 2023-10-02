<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Rules\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Upload action
     */
    public function upload(Request $request)
    {
        try {
            $this->validate($request, [
                'docs' => ['required', 'file'],
                'dire' => ['string', 'min:3', 'max:10'],
                'user_id'   => ['required', 'integer'],
                'user_type'   => ['required', 'in:admin,merchant,driver,client', new UserType],
            ]);

            $file = $request->file('docs');
            $path = $file->store($request->get('dire'), ['disk' => 'spaces', 'visibility' => 'public']);

            $dimensions = $this->getFileDimension($file);

            /** @var \Illuminate\Filesystem\FilesystemManager $disk */
            $disk = Storage::disk('spaces');
            $link = $disk->url($path);

            $model = new Document();
            $model->link = $link;
            $model->name = $file->hashName();
            $model->mime_type = $file->getMimeType();
            $model->size = $file->getSize();
            $model->width = $dimensions['width'];
            $model->height = $dimensions['height'];
            $model->user_id = $request->get('user_id');
            $model->user_type = $request->get('user_type');
            $model->saveOrFail();

            return $this->successResponse($model->getAttributes(), 202);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Get file dimension
     * @param  \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function getFileDimension(UploadedFile $file): array
    {
        $size = getimagesize($file->getRealPath());
        return [
            'width'     => $size[0] ?? 0,
            'height'    => $size[1] ?? 0,
        ];
    }
}
