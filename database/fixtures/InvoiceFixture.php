<?php

namespace Database\Fixtures;

class InvoiceFixture extends Fixture
{
    protected static string $name = 'invoices';

    /**
     * Return a random note for the given invoice status.
     */
    public static function randomNote(string $status): string
    {
        $statusNotes = static::data('notes')[$status];

        return fake()->randomElement($statusNotes);
    }
}
