<?php

namespace Database\Fixtures;

class UserFixture extends Fixture
{
    protected static string $name = 'users';

    /**
     * Return a closure that sets the email based on the user's role.
     */
    public static function emailByRole(): \Closure
    {
        $emails = static::data('emails');

        return fn (array $attributes) => [
            'email' => $emails[$attributes['role']->value],
        ];
    }

    /**
     * Return a closure that sets the note based on the user's status.
     * Unverified users (null email_verified_at) use the 'unverified' note key.
     */
    public static function noteByStatus(): \Closure
    {
        $notes = static::data('notes');

        return fn (array $attributes) => [
            'note' => $attributes['email_verified_at'] === null
                ? $notes['unverified']
                : $notes[$attributes['status']->value],
        ];
    }
}
