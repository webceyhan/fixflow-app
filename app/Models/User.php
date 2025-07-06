<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Concerns\Contactable;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     *  @use HasFactory<\Database\Factories\UserFactory>
     *  @use HasStatus<\App\Enums\UserStatus>
     */
    use Contactable, HasFactory, HasStatus, Notifiable;

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
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the tickets assigned to the user.
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assignee_id');
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include users with the specified role.
     */
    public function scopeOfRole(Builder $query, UserRole $role): void
    {
        $query->where('role', $role->value);
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
     * Determine if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }
}
