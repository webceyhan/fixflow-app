<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $assignee_id
 * 
 * @property-read User|null $assignee
 * 
 * @method static Builder|static assignable()
 * @method static Builder|static assigned()
 */
trait Assignable
{
    private const ASSIGNEE_ID = 'assignee_id';

    /**
     * Determine if the model is assignable.
     */
    public function isAssignable(): bool
    {
        return $this->{static::ASSIGNEE_ID} === null;
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

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, static::ASSIGNEE_ID);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models that are assignable.
     */
    public function scopeAssignable(Builder $query): void
    {
        $query->whereNull(static::ASSIGNEE_ID);
    }

    /**
     * Scope a query to only include models that are already assigned.
     */
    public function scopeAssigned(Builder $query): void
    {
        $query->whereNotNull(static::ASSIGNEE_ID);
    }
}
