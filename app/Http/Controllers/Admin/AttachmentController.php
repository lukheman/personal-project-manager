<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectAttachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    /**
     * Download an attachment.
     */
    public function download(ProjectAttachment $attachment): StreamedResponse
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download(
            $attachment->path,
            $attachment->filename
        );
    }
}
