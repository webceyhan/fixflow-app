<?php

use App\Models\Device;
use App\Models\Ticket;
use App\Observers\TicketObserver;

beforeEach(function () {
    $this->observer = new TicketObserver;
});

it('updates device ticket-counts on creation', function () {
    // Arrange
    $ticket = mock(Ticket::class);
    $device = mock(Device::class);

    $ticket->shouldReceive('load')
        ->once()
        ->with('device')
        ->andReturnSelf();

    $ticket->shouldReceive('getAttribute')
        ->with('device')
        ->once()
        ->andReturn($device);

    $device->shouldReceive('fillTicketCounts')
        ->once()
        ->andReturnSelf();

    $device->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->created($ticket);
});

it('does nothing when status was not changed', function () {
    // Arrange
    $ticket = mock(Ticket::class);

    $ticket->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(false);

    // device should not be loaded or modified when no relevant changes
    $ticket->shouldNotReceive('load');

    // Act
    $this->observer->updated($ticket);
});

it('updates device ticket-counts when status was changed', function () {
    // Arrange
    $ticket = mock(Ticket::class);
    $device = mock(Device::class);

    $ticket->shouldReceive('wasChanged')
        ->once()
        ->with(['status'])
        ->andReturn(true);

    $ticket->shouldReceive('load')
        ->once()
        ->with('device')
        ->andReturnSelf();

    $ticket->shouldReceive('getAttribute')
        ->with('device')
        ->once()
        ->andReturn($device);

    $device->shouldReceive('fillTicketCounts')
        ->once()
        ->andReturnSelf();

    $device->shouldReceive('save')
        ->once()
        ->andReturn(true);

    $this->observer->updated($ticket);
});

it('updates device ticket-counts on deletion', function () {
    // Arrange
    $ticket = mock(Ticket::class);
    $device = mock(Device::class);

    $ticket->shouldReceive('load')
        ->once()
        ->with('device')
        ->andReturnSelf();

    $ticket->shouldReceive('getAttribute')
        ->with('device')
        ->once()
        ->andReturn($device);

    $device->shouldReceive('fillTicketCounts')
        ->once()
        ->andReturnSelf();

    $device->shouldReceive('save')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->deleted($ticket);
});
