<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Upload action
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'docs' => 'required|file',
            'dire' => 'string|min:3|max:10',
        ]);

        $file = $request->file('docs');
        $path = $file->store($request->get('dire'), ['disk' => 'spaces', 'visibility' => 'public']);

        /** @var \Illuminate\Filesystem\FilesystemManager $disk */
        $disk = Storage::disk('spaces');
        $link = $disk->url($path);

        return $this->successResponse([
            'path' => $path,
            'link' => $link,
        ], 202);
    }
}
