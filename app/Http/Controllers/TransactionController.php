<?php

namespace App\Http\Controllers;

use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\DeleteTransaction;
use App\Actions\Transactions\QueryTransactions;
use App\Actions\Transactions\UpdateTransaction;
use App\Http\Requests\Transactions\TransactionStoreRequest;
use App\Http\Requests\Transactions\TransactionUpdateRequest;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryTransactions $queryTransactions): Response
    {
        $transactions = $queryTransactions->handle()
            ->with(['invoice.ticket.customer', 'invoice.ticket.device'])
            ->paginate();

        return Inertia::render('transactions/Index', [
            'transactions' => $transactions,
            'filter' => request('filter'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice): Response
    {
        $transaction = new Transaction(['invoice_id' => $invoice->id]);
        $invoice->load(['ticket.device', 'ticket.customer']);

        return Inertia::render('transactions/Create', [
            'transaction' => $transaction,
            'invoice' => $invoice,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request, Invoice $invoice, CreateTransaction $createTransaction): RedirectResponse
    {
        $transaction = $createTransaction->handle([
            ...$request->validated(),
            'invoice_id' => $invoice->id,
        ]);

        return redirect()->route('invoices.show', $invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): Response
    {
        return $this->edit($transaction);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): Response
    {
        $transaction->load(['invoice', 'invoice.ticket.device', 'invoice.ticket.customer']);

        return Inertia::render('transactions/Edit', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionUpdateRequest $request, Transaction $transaction, UpdateTransaction $updateTransaction): RedirectResponse
    {
        $updateTransaction->handle($transaction, $request->validated());

        return redirect()->route('invoices.show', $transaction->invoice_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction, DeleteTransaction $deleteTransaction): RedirectResponse
    {
        $deleteTransaction->handle($transaction);

        return redirect()->route('invoices.show', $transaction->invoice_id);
    }
}
