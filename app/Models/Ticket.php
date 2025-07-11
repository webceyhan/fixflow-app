<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use Assignable, HasDueDate, HasFactory, HasPriority, HasProgress;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => TicketStatus::New,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TicketStatus::class,
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the device for the ticket.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the customer for the ticket via the device.
     */
    public function customer(): BelongsTo
    {
        return $this->device->customer();
    }

    /**
     * Get the tasks associated with the ticket.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the orders associated with the ticket.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the invoice associated with the ticket.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include tickets with the specified status.
     */
    public function scopeOfStatus(Builder $query, TicketStatus $status): void
    {
        $query->where('status', $status->value);
    }
}
