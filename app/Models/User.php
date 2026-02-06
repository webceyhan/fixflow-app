<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasActiveStatus, HasFactory, HasStatus, Notifiable, TwoFactorAuthenticatable;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'role' => UserRole::Technician,
        'status' => UserStatus::Active,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'note',
        'role',
        'status',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assignee_id');
    }

    /**
     * Get the tasks assigned to the user.
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    /**
     * Get the orders assigned to the user.
     */
    public function assignedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'assignee_id');
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include users with the specified role.
     */
    public function scopeOfRole(Builder $query, UserRole $role): void
    {
        $query->where('role', $role->value);
    }

    /**
     * Scope a query to only include assignable users (active users).
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
