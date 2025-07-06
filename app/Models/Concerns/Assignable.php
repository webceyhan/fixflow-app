<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Assignable
{
    private const ASSIGNEE_ID = 'assignee_id';

    /**
     * Determine if the model is assigned.
     */
    public function isAssigned(): bool
    {
        return $this->{static::ASSIGNEE_ID} !== null;
    }

    /**
     * Assign the model to a user.
     */
    public function assignTo(User $user): void
    {
        $this->assignee()->associate($user)->save();
    }

    /**
     * Unassign the model from a user.
     */
    public function unassign(): void
    {
        $this->assignee()->dissociate()->save();
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the assignee (user) for the model.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, static::ASSIGNEE_ID);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models that are already assigned.
     */
    public function scopeAssigned(Builder $query): void
    {
        $query->whereNotNull(static::ASSIGNEE_ID);
    }

    /**
     * Scope a query to only include models that are unassigned.
     */
    public function scopeUnassigned(Builder $query): void
    {
        $query->whereNull(static::ASSIGNEE_ID);
    }
}
