<?php

use App\Models\Invoice;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all invoices', function () {
    $user = User::factory()->create();
    $invoices = Invoice::factory(2)->create();

    $response = $this->actingAs($user)->get('/invoices');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Invoices/Index')
                ->has('invoices', 2)
                ->has(
                    'invoices.0',
                    fn (Assert $page) => $page
                        ->where('id', $invoices->first()->id)
                        ->where('ticket_id', $invoices->first()->ticket_id)
                        ->where('total', $invoices->first()->total)
                        ->where('is_paid', $invoices->first()->is_paid)
                        // ->where('due_date', $invoices->first()->due_date)
                        ->where('total_paid', (int)$invoices->first()->total_paid)
                        ->where('total_refunded', (int)$invoices->first()->total_refunded)
                        ->etc()
                )
        );
});

it('can view an invoice', function () {
    $user = User::factory()->create();
    $invoice = Invoice::factory()->create();

    $response = $this->actingAs($user)->get('/invoices/' . $invoice->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Invoices/Show')
                ->has('invoice')
                ->where('invoice.id', $invoice->id)
                ->where('invoice.ticket_id', $invoice->ticket_id)
                ->where('invoice.total', $invoice->total)
                ->where('invoice.is_paid', $invoice->is_paid)
                // ->where('invoice.due_date', $invoice->due_date)
                ->where('invoice.total_paid', (int)$invoice->total_paid)
                ->where('invoice.total_refunded', (int)$invoice->total_refunded)
        );
});
