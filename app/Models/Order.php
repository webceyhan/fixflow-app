<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasApproval;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * @use HasFactory<\Database\Factories\OrderFactory>
     * @use HasStatus<\App\Enums\OrderStatus>
     */
    use Billable, HasApproval, HasFactory, HasProgress, HasStatus;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantity' => 1,
        'status' => OrderStatus::New,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'supplier',
        'quantity',
        'cost',
        'is_billable',
        'status',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'cost' => 'float',
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that the order belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
