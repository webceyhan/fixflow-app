<?php

namespace App\Actions\Attachments;

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateAttachment
{
    public function handle(Model $attachable, array $data): Attachment
    {
        /** @var UploadedFile $file */
        $file = $data['file'];

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString().($extension ? ".{$extension}" : '');
        $attachableDirectory = Str::of(class_basename($attachable))->snake()->plural()->toString();
        $path = $file->storeAs("{$attachableDirectory}/{$attachable->getKey()}", $filename, 'local');
        $mimeType = $file->getClientMimeType() ?: $file->getMimeType() ?: 'application/octet-stream';
        $category = str_starts_with($mimeType, 'image/')
            ? AttachmentCategory::Photo
            : AttachmentCategory::Other;

        return Attachment::create([
            'attachable_type' => $attachable::class,
            'attachable_id' => $attachable->getKey(),
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $mimeType,
            'size' => $file->getSize() ?: 0,
            'category' => $category,
        ]);
    }
}
