<?php

use App\Models\Transaction;
use Illuminate\Support\Carbon;

it('can initialize transaction', function () {
    $transaction = new Transaction();

    expect($transaction->id)->toBeNull();
    expect($transaction->amount)->toBe(0.0);
    expect($transaction->note)->toBeNull();
    expect($transaction->created_at)->toBeNull();
    expect($transaction->updated_at)->toBeNull();
});

it('can create transaction', function () {
    $transaction = Transaction::factory()->create();

    expect($transaction->id)->toBeInt();
    expect($transaction->amount)->toBeFloat();
    expect($transaction->note)->toBeString();
    expect($transaction->created_at)->toBeInstanceOf(Carbon::class);
    expect($transaction->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create transaction without note', function () {
    $transaction = Transaction::factory()->withoutNote()->create();

    expect($transaction->note)->toBeNull();
});

it('can update transaction', function () {
    $transaction = Transaction::factory()->create();

    $transaction->update([
        'amount' => 100,
        'note' => 'Partially paid'
    ]);

    expect($transaction->amount)->toBe(100.0);
    expect($transaction->note)->toBe('Partially paid');
});

it('can delete transaction', function () {
    $transaction = Transaction::factory()->create();

    $transaction->delete();

    expect(Transaction::find($transaction->id))->toBeNull();
});
