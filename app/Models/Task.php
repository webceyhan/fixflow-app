<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasApproval;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TaskObserver::class])]
class Task extends Model
{
    /**
     * @use HasFactory<\Database\Factories\TaskFactory>
     * @use HasStatus<\App\Enums\TaskStatus>
     * @use HasType<\App\Enums\TaskType>
     */
    use Billable, HasApproval, HasFactory, HasProgress, HasStatus, HasType;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => TaskType::Repair,
        'status' => TaskStatus::New,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cost',
        'is_billable',
        'note',
        'type',
        'status',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'float',
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that the task belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////
}
