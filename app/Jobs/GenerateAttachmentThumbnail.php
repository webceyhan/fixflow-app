<?php

namespace App\Jobs;

use App\Actions\Attachments\GenerateAttachmentThumbnail as GenerateAttachmentThumbnailAction;
use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAttachmentThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $attachmentId) {}

    /**
     * Execute the job.
     */
    public function handle(GenerateAttachmentThumbnailAction $generateAttachmentThumbnail): void
    {
        $attachment = Attachment::query()->find($this->attachmentId);

        if (! $attachment || $attachment->category !== AttachmentCategory::Photo) {
            return;
        }

        $generateAttachmentThumbnail->handle($attachment);
    }
}
