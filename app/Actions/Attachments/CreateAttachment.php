<?php

namespace App\Actions\Attachments;

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class CreateAttachment
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Model $attachable, array $data): Attachment
    {
        /** @var UploadedFile $file */
        $file = $data['file'];

        $extension = strtolower($file->getClientOriginalExtension());
        $fileKey = Str::uuid()->toString();
        $temporaryPath = $file->storeAs(
            'tmp/attachments',
            $this->buildFilename($fileKey, $extension),
            'local',
        );
        $mimeType = $file->getClientMimeType() ?: $file->getMimeType() ?: 'application/octet-stream';
        $category = str_starts_with($mimeType, 'image/')
            ? AttachmentCategory::Photo
            : AttachmentCategory::Other;

        $attachment = Attachment::create([
            'attachable_type' => $attachable::class,
            'attachable_id' => $attachable->getKey(),
            'name' => $file->getClientOriginalName(),
            'path' => $temporaryPath,
            'mime_type' => $mimeType,
            'size' => $file->getSize() ?: 0,
            'category' => $category,
        ]);

        $finalPath = $this->pathFromAttachable(
            attachable: $attachable,
            fileKey: $fileKey,
            extension: $extension,
        );

        $moved = Storage::disk()->move($temporaryPath, $finalPath);

        if (! $moved) {
            throw new RuntimeException('Unable to move attachment to final storage path.');
        }

        $attachment->path = $finalPath;
        $attachment->save();

        return $attachment;
    }

    private function pathFromAttachable(Model $attachable, string $fileKey, string $extension): string
    {
        if ($attachable instanceof Ticket) {
            return "tickets/{$attachable->id}/attachments/{$this->buildFilename($fileKey, $extension)}";
        }

        $attachableDirectory = Str::of(class_basename($attachable))->snake()->plural()->toString();

        return "{$attachableDirectory}/{$attachable->getKey()}/{$this->buildFilename($fileKey, $extension)}";
    }

    private function buildFilename(string $fileKey, string $extension): string
    {
        return $extension === ''
            ? $fileKey
            : "{$fileKey}.{$extension}";
    }
}
