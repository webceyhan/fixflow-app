<?php

namespace App\Models;

use App\Enums\AttachmentCategory;
use Carbon\CarbonImmutable;
use Database\Factories\AttachmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $attachable_type
 * @property int $attachable_id
 * @property string $name
 * @property string $path
 * @property string $mime_type
 * @property int $size
 * @property AttachmentCategory $category
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Model $attachable
 */
class Attachment extends Model
{
    /**
     * @use HasFactory<AttachmentFactory>
     */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'category' => AttachmentCategory::Other,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'name',
        'path',
        'mime_type',
        'size',
        'category',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'category' => AttachmentCategory::class,
        ];
    }

    /**
     * Get the owning attachable model.
     *
     * @return MorphTo<Model, $this>
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
