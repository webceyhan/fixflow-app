<?php

namespace Database\Factories\States;

trait HasNoteStates
{
    private const NOTE = 'note';

    /**
     * Indicate that the model has no note.
     */
    public function withoutNote(): self
    {
        return $this->state(fn (array $attributes) => [
            static::NOTE => null,
        ]);
    }

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
