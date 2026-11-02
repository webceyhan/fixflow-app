<?php

namespace App\Actions\Attachments;

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use App\Support\Attachments\AttachmentThumbnailPath;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class GenerateAttachmentThumbnail
{
    public function handle(Attachment $attachment): void
    {
        if ($attachment->category !== AttachmentCategory::Photo) {
            return;
        }

        $disk = Storage::disk();

        if (! $disk->exists($attachment->path)) {
            throw new RuntimeException('Attachment file does not exist.');
        }

        $sourceImage = imagecreatefromstring($disk->get($attachment->path));

        if ($sourceImage === false) {
            throw new RuntimeException('Unable to decode image for thumbnail generation.');
        }

        try {
            $thumbnailImage = $this->createSquareThumbnail($sourceImage, 320);

            ob_start();
            imagejpeg($thumbnailImage, null, 82);
            $binary = ob_get_clean();

            if ($binary === false) {
                throw new RuntimeException('Unable to encode thumbnail image.');
            }

            $disk->put(AttachmentThumbnailPath::fromAttachment($attachment), $binary);

            imagedestroy($thumbnailImage);
        } finally {
            imagedestroy($sourceImage);
        }
    }

    /**
     * @param  int<1, 500>  $size
     */
    private function createSquareThumbnail(\GdImage $sourceImage, int $size): \GdImage
    {
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        $scale = max($size / $sourceWidth, $size / $sourceHeight);
        $resizedWidth = (int) round($sourceWidth * $scale);
        $resizedHeight = (int) round($sourceHeight * $scale);
        $offsetX = (int) floor(($size - $resizedWidth) / 2);
        $offsetY = (int) floor(($size - $resizedHeight) / 2);

        $thumbnailImage = imagecreatetruecolor($size, $size);

        if ($thumbnailImage === false) {
            throw new RuntimeException('Unable to initialize thumbnail canvas.');
        }

        $background = imagecolorallocate($thumbnailImage, 0, 0, 0);
        imagefill($thumbnailImage, 0, 0, (int) $background);

        imagecopyresampled(
            $thumbnailImage,
            $sourceImage,
            $offsetX,
            $offsetY,
            0,
            0,
            $resizedWidth,
            $resizedHeight,
            $sourceWidth,
            $sourceHeight,
        );

        return $thumbnailImage;
    }
}
