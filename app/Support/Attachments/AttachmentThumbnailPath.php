<?php

namespace App\Support\Attachments;

use App\Models\Attachment;
use Illuminate\Support\Str;

class AttachmentThumbnailPath
{
    public static function fromAttachment(Attachment $attachment): string
    {
        $directory = Str::beforeLast($attachment->path, '/');
        $filename = pathinfo($attachment->path, PATHINFO_FILENAME);
        $extension = pathinfo($attachment->path, PATHINFO_EXTENSION);
        $thumbnailFilename = $extension === ''
            ? "{$filename}_thumb"
            : "{$filename}_thumb.{$extension}";

        if ($directory === $attachment->path) {
            return $thumbnailFilename;
        }

        return "{$directory}/{$thumbnailFilename}";
    }
}
