<?php

namespace Database\Factories\States;

trait NoteStates
{
    private const NOTE = 'note';

    /**
     * Indicate that the model has a specified note.
     */
    public function withNote(string $note): self
    {
        return $this->state(fn (array $attributes) => [
            static::NOTE => $note,
        ]);
    }
}
