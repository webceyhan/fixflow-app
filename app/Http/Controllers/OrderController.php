<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CreateOrder;
use App\Actions\Orders\DeleteOrder;
use App\Actions\Orders\QueryOrders;
use App\Actions\Orders\UpdateOrder;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryOrders $queryOrders): Response
    {
        $orders = $queryOrders->handle()
            ->with(['ticket.device', 'ticket.customer', 'assignee'])
            ->paginate();

        return Inertia::render('orders/Index', [
            'orders' => $orders,
            'filter' => request('filter'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Ticket $ticket): Response
    {
        $order = new Order(['ticket_id' => $ticket->id]);
        $ticket->load(['device', 'customer']);

        return Inertia::render('orders/Create', [
            'order' => $order,
            'ticket' => $ticket,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request, Ticket $ticket, CreateOrder $createOrder): RedirectResponse
    {
        $order = $createOrder->handle([
            ...$request->validated(),
            'ticket_id' => $ticket->id,
        ]);

        return redirect()->route('orders.show', $order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        $order->load(['ticket', 'ticket.device', 'ticket.customer', 'assignee']);

        return Inertia::render('orders/Show', [
            'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): Response
    {
        $order->load(['ticket', 'ticket.device', 'ticket.customer', 'assignee']);

        return Inertia::render('orders/Edit', [
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, Order $order, UpdateOrder $updateOrder): RedirectResponse
    {
        $updateOrder->handle($order, $request->validated());

        return redirect()->route('orders.show', $order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, DeleteOrder $deleteOrder): RedirectResponse
    {
        $deleteOrder->handle($order);

        return redirect()->route('orders.index');
    }
}
