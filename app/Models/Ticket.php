<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use Assignable, HasActiveStatus, HasDueDate, HasFactory, HasPriority, HasStatus;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'priority' => Priority::Medium,
        'status' => TicketStatus::Created,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'device_id',
        'assignee_id',
        'title',
        'note',
        'priority',
        'status',
        'due_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the device that owns the ticket.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the customer for the ticket via the device.
     */
    public function customer(): HasOneThrough
    {
        return $this->hasOneThrough(Customer::class, Device::class, 'id', 'id', 'device_id', 'customer_id');
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////
}
