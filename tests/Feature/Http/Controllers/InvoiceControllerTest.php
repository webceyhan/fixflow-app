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
