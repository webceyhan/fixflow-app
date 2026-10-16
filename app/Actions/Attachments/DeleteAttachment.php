<?php

namespace App\Actions\Attachments;

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use App\Support\Attachments\AttachmentThumbnailPath;
use Illuminate\Support\Facades\Storage;

class DeleteAttachment
{
    public function handle(Attachment $attachment): void
    {
        Storage::disk('local')->delete($attachment->path);

        if ($attachment->category === AttachmentCategory::Photo) {
            Storage::disk('local')->delete(
                AttachmentThumbnailPath::fromAttachment($attachment),
            );
        }

        $attachment->delete();
    }
}
