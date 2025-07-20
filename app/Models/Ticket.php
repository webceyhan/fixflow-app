<?php

namespace App\Models;

use App\Casts\Progress;
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([TicketObserver::class])]
class Ticket extends Model
{
    /**
     * @use HasFactory<\Database\Factories\TicketFactory>
     * @use HasStatus<\App\Enums\TicketStatus>
     */
    use Assignable, HasDueDate, HasFactory, HasPriority, HasProgress, HasStatus;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => TicketStatus::New,
        'pending_tasks_count' => 0,
        'complete_tasks_count' => 0,
        'total_tasks_count' => 0,
        'pending_orders_count' => 0,
        'complete_orders_count' => 0,
        'total_orders_count' => 0,
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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'task_progress',
        'order_progress',
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the task progress percentage.
     */
    protected function taskProgress(): Attribute
    {
        return Progress::using('pending_tasks_count', 'complete_tasks_count');
    }

    /**
     * Get the order progress percentage.
     */
    protected function orderProgress(): Attribute
    {
        return Progress::using('pending_orders_count', 'complete_orders_count');
    }

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

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Fill task counts for the ticket.
     */
    public function fillTaskCounts(): self
    {
        $taskCounts = $this->tasks()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingTasksCount = collect(TaskStatus::pendingCases())
            ->map(fn ($case) => $taskCounts[$case->value] ?? 0)
            ->sum();

        $completeTasksCount = collect(TaskStatus::completeCases())
            ->map(fn ($case) => $taskCounts[$case->value] ?? 0)
            ->sum();

        $totalTasksCount = $taskCounts->sum();

        return $this->forceFill([
            'pending_tasks_count' => $pendingTasksCount,
            'complete_tasks_count' => $completeTasksCount,
            'total_tasks_count' => $totalTasksCount,
        ]);
    }

    /**
     * Fill order counts for the ticket.
     */
    public function fillOrderCounts(): self
    {
        $orderCounts = $this->orders()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingOrdersCount = collect(OrderStatus::pendingCases())
            ->map(fn ($case) => $orderCounts[$case->value] ?? 0)
            ->sum();

        $completeOrdersCount = collect(OrderStatus::completeCases())
            ->map(fn ($case) => $orderCounts[$case->value] ?? 0)
            ->sum();

        $totalOrdersCount = $orderCounts->sum();

        return $this->forceFill([
            'pending_orders_count' => $pendingOrdersCount,
            'complete_orders_count' => $completeOrdersCount,
            'total_orders_count' => $totalOrdersCount,
        ]);
    }
}
