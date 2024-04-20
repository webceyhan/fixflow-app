<?php

use App\Models\Transaction;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all transactions', function () {
    $user = User::factory()->create();
    $transactions = Transaction::factory(2)->create();

    $response = $this->actingAs($user)->get('/transactions');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Transactions/Index')
                ->has('transactions', 2)
                ->has(
                    'transactions.0',
                    fn (Assert $page) => $page
                        ->where('id', $transactions->first()->id)
                        ->where('invoice_id', $transactions->first()->invoice_id)
                        ->where('amount', $transactions->first()->amount)
                        ->where('note', $transactions->first()->note)
                        ->where('method', $transactions->first()->method->value)
                        ->where('type', $transactions->first()->type->value)
                        ->etc()
                )
        );
});
