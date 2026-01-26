<?php

use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a ticket successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($manager)->delete(route('tickets.destroy', $ticket));

    // Assert
    $response->assertRedirect(route('tickets.index'));
    expect(Ticket::find($ticket->id))->toBeNull();
});
