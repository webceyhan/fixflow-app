<?php

namespace App\Http\Controllers;

use App\Actions\Adjustments\CreateAdjustment;
use App\Actions\Adjustments\DeleteAdjustment;
use App\Actions\Adjustments\QueryAdjustments;
use App\Actions\Adjustments\UpdateAdjustment;
use App\Http\Requests\Adjustments\AdjustmentStoreRequest;
use App\Http\Requests\Adjustments\AdjustmentUpdateRequest;
use App\Models\Adjustment;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryAdjustments $queryAdjustments): Response
    {
        $adjustments = $queryAdjustments->handle()
            ->with(['invoice.ticket.customer', 'invoice.ticket.device'])
            ->paginate();

        return Inertia::render('adjustments/Index', [
            'adjustments' => $adjustments,
            'filter' => request('filter'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice): Response
    {
        $adjustment = new Adjustment(['invoice_id' => $invoice->id]);
        $invoice->load(['ticket.device', 'ticket.customer']);

        return Inertia::render('adjustments/Create', [
            'adjustment' => $adjustment,
            'invoice' => $invoice,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdjustmentStoreRequest $request, Invoice $invoice, CreateAdjustment $createAdjustment): RedirectResponse
    {
        $adjustment = $createAdjustment->handle([
            ...$request->validated(),
            'invoice_id' => $invoice->id,
        ]);

        return redirect()->route('invoices.show', $invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Adjustment $adjustment): Response
    {
        return $this->edit($adjustment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adjustment $adjustment): Response
    {
        $adjustment->load(['invoice', 'invoice.ticket.device', 'invoice.ticket.customer']);

        return Inertia::render('adjustments/Edit', [
            'adjustment' => $adjustment,
            'invoice' => $adjustment->invoice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdjustmentUpdateRequest $request, Adjustment $adjustment, UpdateAdjustment $updateAdjustment): RedirectResponse
    {
        $updateAdjustment->handle($adjustment, $request->validated());

        return redirect()->route('invoices.show', $adjustment->invoice_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adjustment $adjustment, DeleteAdjustment $deleteAdjustment): RedirectResponse
    {
        $deleteAdjustment->handle($adjustment);

        return redirect()->route('invoices.show', $adjustment->invoice_id);
    }
}
