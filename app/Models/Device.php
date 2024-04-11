<?php

namespace App\Models;

use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $model
 * @property string|null $brand
 * @property string|null $serial_number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static DeviceFactory factory(int $count = null, array $state = [])
 */
class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'brand',
        'serial_number',
    ];
}
