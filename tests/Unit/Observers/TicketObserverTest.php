<?php

use App\Models\Device;
use App\Models\Ticket;
use App\Observers\TicketObserver;

beforeEach(function () {
    $this->observer = new TicketObserver;

    // Helpers

    $this->mockTicket = function (bool $syncDevice = false) {
        $ticket = mock(Ticket::class);

        if ($syncDevice) {
            $device = mock(Device::class);
            $device->shouldReceive('fillTicketCounts')->once()->andReturnSelf();
            $device->shouldReceive('save')->once()->andReturn(true);

            $ticket->shouldReceive('load')->once()->with('device')->andReturnSelf();
            $ticket->shouldReceive('getAttribute')->once()->with('device')->andReturn($device);
        }

        return $ticket;
    };

    $this->mockTicketWithUpdates = function (bool $statusChanged = false) {
        $ticket = $this->mockTicket(syncDevice: $statusChanged);

        $ticket->shouldReceive('wasChanged')
            ->once()
            ->with(['status'])
            ->andReturn($statusChanged);

        return $ticket;
    };
});

it('updates device ticket-counts on creation', function () {
    // Arrange
    $ticket = $this->mockTicket(syncDevice: true);

    // Act
    $this->observer->created($ticket);
});

it('does nothing when status was not changed', function () {
    // Arrange
    $ticket = $this->mockTicketWithUpdates();

    // Act
    $this->observer->updated($ticket);
});

it('updates device ticket-counts when status was changed', function () {
    // Arrange
    $ticket = $this->mockTicketWithUpdates(statusChanged: true);

    // Act
    $this->observer->updated($ticket);
});

it('updates device ticket-counts on deletion', function () {
    // Arrange
    $ticket = $this->mockTicket(syncDevice: true);

    // Act
    $this->observer->deleted($ticket);
});
