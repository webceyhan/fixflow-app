<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\CreateTicket;
use App\Actions\Tickets\DeleteTicket;
use App\Actions\Tickets\QueryTickets;
use App\Actions\Tickets\UpdateTicket;
use App\Http\Requests\Tickets\TicketStoreRequest;
use App\Http\Requests\Tickets\TicketUpdateRequest;
use App\Models\Device;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryTickets $queryTickets): Response
    {
        $tickets = $queryTickets->handle()
            ->with(['device', 'customer', 'assignee'])
            ->paginate();

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets,
            'filter' => request('filter'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Device $device): Response
    {
        $ticket = new Ticket(['device_id' => $device->id]);
        $device->load('customer');

        return Inertia::render('tickets/Create', [
            'ticket' => $ticket,
            'device' => $device,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketStoreRequest $request, Device $device, CreateTicket $createTicket): RedirectResponse
    {
        $ticket = $createTicket->handle([
            ...$request->validated(),
            'device_id' => $device->id,
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): Response
    {
        $ticket->load(['device', 'customer', 'assignee']);

        return Inertia::render('tickets/Show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket): Response
    {
        $ticket->load(['device', 'customer', 'assignee']);

        return Inertia::render('tickets/Edit', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketUpdateRequest $request, Ticket $ticket, UpdateTicket $updateTicket): RedirectResponse
    {
        $updateTicket->handle($ticket, $request->validated());

        return redirect()->route('tickets.show', $ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, DeleteTicket $deleteTicket): RedirectResponse
    {
        $deleteTicket->handle($ticket);

        return redirect()->route('tickets.index');
    }
}
