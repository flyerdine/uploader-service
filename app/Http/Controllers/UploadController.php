<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
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
                'docs' => 'required|file',
                'dire' => 'string|min:3|max:10',
                'user_id'   => 'required|integer',
                'user_type'   => 'required|string',
            ]);

            $file = $request->file('docs');
            $path = $file->store($request->get('dire'), ['disk' => 'spaces', 'visibility' => 'public']);

            /** @var \Illuminate\Filesystem\FilesystemManager $disk */
            $disk = Storage::disk('spaces');
            $link = $disk->url($path);

            $model = new Document();
            $model->link = $link;
            $model->name = $file->getClientOriginalName();
            $model->mime_type = $file->getMimeType();
            $model->user_id = $request->get('user_id');
            $model->user_type = $request->get('user_type');
            $model->saveOrFail();

            return $this->successResponse($model->getAttributes(), 202);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
}
