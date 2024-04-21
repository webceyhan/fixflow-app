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

it('can view a transaction', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->create();

    $response = $this->actingAs($user)->get('/transactions/' . $transaction->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Transactions/Show')
                ->has(
                    'transaction',
                    fn (Assert $page) => $page
                        ->where('id', $transaction->id)
                        ->where('invoice_id', $transaction->invoice_id)
                        ->where('amount', $transaction->amount)
                        ->where('note', $transaction->note)
                        ->where('method', $transaction->method->value)
                        ->where('type', $transaction->type->value)
                        ->etc()
                )
        );
});

it('can delete a transaction', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->create();

    $response = $this->actingAs($user)->delete('/transactions/' . $transaction->id);

    $response->assertRedirect('/transactions');

    $this->assertNull($transaction->fresh());
});
