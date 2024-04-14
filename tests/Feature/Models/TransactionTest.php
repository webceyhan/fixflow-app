<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

it('can initialize transaction', function () {
    $transaction = new Transaction();

    expect($transaction->id)->toBeNull();
    expect($transaction->amount)->toBe(0.0);
    expect($transaction->note)->toBeNull();
    expect($transaction->method)->toBe(TransactionMethod::Cash);
    expect($transaction->type)->toBe(TransactionType::Payment);
    expect($transaction->created_at)->toBeNull();
    expect($transaction->updated_at)->toBeNull();
});

it('can create transaction', function () {
    $transaction = Transaction::factory()->create();

    expect($transaction->id)->toBeInt();
    expect($transaction->amount)->toBeFloat();
    expect($transaction->note)->toBeString();
    expect($transaction->method)->toBe(TransactionMethod::Cash);
    expect($transaction->type)->toBe(TransactionType::Payment);
    expect($transaction->created_at)->toBeInstanceOf(Carbon::class);
    expect($transaction->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create transaction without note', function () {
    $transaction = Transaction::factory()->withoutNote()->create();

    expect($transaction->note)->toBeNull();
});

it('can create transaction of method', function (TransactionMethod $method) {
    $transaction = Transaction::factory()->ofMethod($method)->create();

    expect($transaction->method)->toBe($method);
})->with(TransactionMethod::cases());

it('can create transaction of type', function (TransactionType $type) {
    $transaction = Transaction::factory()->ofType($type)->create();

    expect($transaction->type)->toBe($type);
})->with(TransactionType::cases());

it('can update transaction', function () {
    $transaction = Transaction::factory()->create();

    $transaction->update([
        'amount' => 100,
        'note' => 'Partially paid',
        'method' => TransactionMethod::Card,
        'type' => TransactionType::Refund,
    ]);

    expect($transaction->amount)->toBe(100.0);
    expect($transaction->note)->toBe('Partially paid');
    expect($transaction->method)->toBe(TransactionMethod::Card);
    expect($transaction->type)->toBe(TransactionType::Refund);
});

it('can delete transaction', function () {
    $transaction = Transaction::factory()->create();

    $transaction->delete();

    expect(Transaction::find($transaction->id))->toBeNull();
});

// Method //////////////////////////////////////////////////////////////////////////////////////////

it('can filter transactions by method scope', function (TransactionMethod $method) {
    Transaction::factory()->ofMethod($method)->create();

    expect(Transaction::ofMethod($method)->count())->toBe(1);
    expect(Transaction::ofMethod($method)->first()->method)->toBe($method);
})->with(TransactionMethod::cases());

// Type ////////////////////////////////////////////////////////////////////////////////////////////

it('can filter transactions by type scope', function (TransactionType $type) {
    Transaction::factory()->ofType($type)->create();

    expect(Transaction::ofType($type)->count())->toBe(1);
    expect(Transaction::ofType($type)->first()->type)->toBe($type);
})->with(TransactionType::cases());
