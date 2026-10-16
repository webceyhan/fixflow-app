<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum AttachmentCategory: string
{
    use Collectible,
        HasNextCase;

    /**
     * Device or repair photos uploaded manually.
     *
     * @default
     */
    case Photo = 'photo';

    /**
     * Other manually uploaded files that are not photos.
     *
     * System-generated documents (receipts, QR codes, invoices) are tracked separately.
     */
    case Other = 'other';
}
