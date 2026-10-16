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

        if ($directory === $attachment->path) {
            return "{$filename}_thumb.jpg";
        }

        return "{$directory}/{$filename}_thumb.jpg";
    }
}
