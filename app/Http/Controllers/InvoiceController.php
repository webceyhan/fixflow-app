<?php

namespace App\Http\Controllers;

use App\Actions\Invoices\QueryInvoices;
use App\Actions\Invoices\UpdateInvoice;
use App\Http\Requests\Invoices\InvoiceUpdateRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryInvoices $queryInvoices): Response
    {
        $invoices = $queryInvoices->handle()
            ->with(['ticket.device', 'ticket.customer'])
            ->paginate();

        return Inertia::render('invoices/Index', [
            'invoices' => $invoices,
            'filter' => request('filter'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): Response
    {
        $invoice->load(['ticket', 'ticket.device', 'ticket.customer', 'transactions', 'adjustments']);

        return Inertia::render('invoices/Show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): Response
    {
        $invoice->load(['ticket', 'ticket.device', 'ticket.customer']);

        return Inertia::render('invoices/Edit', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceUpdateRequest $request, Invoice $invoice, UpdateInvoice $updateInvoice): RedirectResponse
    {
        $updateInvoice->handle($invoice, $request->validated());

        return redirect()->route('invoices.show', $invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        abort(404);
    }
}
