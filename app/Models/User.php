<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasStatus;
use App\Observers\UserObserver;
use Carbon\CarbonImmutable;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $note
 * @property UserRole $role
 * @property UserStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $email_verified_at
 * @property CarbonImmutable|null $two_factor_confirmed_at
 * @property-read Collection<int, Order> $assignedOrders
 * @property-read int|null $assigned_orders_count
 * @property-read Collection<int, Task> $assignedTasks
 * @property-read int|null $assigned_tasks_count
 * @property-read Collection<int, Ticket> $assignedTickets
 * @property-read int|null $assigned_tickets_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read mixed $ref
 */
#[Fillable(['name', 'email', 'phone', 'password', 'note', 'role', 'status', 'email_verified_at'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /**
     * @use HasActiveStatus<static>
     * @use HasFactory<UserFactory>
     * @use HasRef<static>
     * @use HasStatus<static, UserStatus>
     */
    use HasActiveStatus,
        HasFactory,
        HasRef,
        HasStatus,
        Notifiable,
        PasskeyAuthenticatable,
        TwoFactorAuthenticatable;

    protected string $refPrefix = 'USR';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => UserRole::Technician,
        'status' => UserStatus::Active,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // ATTRIBUTES //////////////////////////////////////////////////////////////////////////////////

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the tickets assigned to the user.
     *
     * @return HasMany<Ticket, $this>
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assignee_id');
    }

    /**
     * Get the tasks assigned to the user.
     *
     * @return HasMany<Task, $this>
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    /**
     * Get the orders assigned to the user.
     *
     * @return HasMany<Order, $this>
     */
    public function assignedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'assignee_id');
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include users with the specified role.
     *
     * @param  Builder<static>  $query
     */
    public function scopeOfRole(Builder $query, UserRole $role): void
    {
        $query->where('role', $role->value);
    }

    /**
     * Scope a query to only include assignable users (active users).
     *
     * @param  Builder<static>  $query
     */
    public function scopeAssignable(Builder $query): void
    {
        $query->active()->orderBy('name');
    }

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Determine if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Determine if the user is a manager.
     */
    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }
}
