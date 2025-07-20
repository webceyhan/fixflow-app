<?php

namespace App\Models;

use App\Casts\Progress;
use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\TicketStatus;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Concerns\HasWarranty;
use App\Observers\DeviceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([DeviceObserver::class])]
class Device extends Model
{
    /**
     * @use HasFactory<\Database\Factories\DeviceFactory>
     * @use HasStatus<\App\Enums\DeviceStatus>
     * @use HasType<\App\Enums\DeviceType>
     */
    use HasFactory, HasProgress, HasStatus, HasType, HasWarranty;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => DeviceType::Other,
        'status' => DeviceStatus::Received,
        'pending_tickets_count' => 0,
        'complete_tickets_count' => 0,
        'total_tickets_count' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'brand',
        'serial_number',
        'purchase_date',
        'warranty_expire_date',
        'type',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'device_progress',
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket progress percentage.
     */
    protected function ticketProgress(): Attribute
    {
        return Progress::using('pending_tickets_count', 'complete_tickets_count');
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the customer that owns the device.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the tickets associated with the device.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Fill ticket counts for the device.
     */
    public function fillTicketCounts(): self
    {
        $ticketCounts = $this->tickets()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingTicketsCount = collect(TicketStatus::pendingCases())
            ->map(fn ($case) => $ticketCounts[$case->value] ?? 0)
            ->sum();

        $completeTicketsCount = collect(TicketStatus::completeCases())
            ->map(fn ($case) => $ticketCounts[$case->value] ?? 0)
            ->sum();

        $totalTicketsCount = $ticketCounts->sum();

        return $this->forceFill([
            'pending_tickets_count' => $pendingTicketsCount,
            'complete_tickets_count' => $completeTicketsCount,
            'total_tickets_count' => $totalTicketsCount,
        ]);
    }
}
