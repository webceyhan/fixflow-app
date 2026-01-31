<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the transactions list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $transactions = Transaction::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('transactions.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Index')
        ->has('transactions.data', 3)
        ->has('transactions.links')
        ->has('filter')
    );
});

it('allows searching transactions', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Transaction::factory()->count(2)->create(['note' => "{$uid} Payment received"]);
    Transaction::factory()->create(['note' => 'Deposit payment']);

    // Act
    $response = actingAs($viewer)->get(route('transactions.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Index')
        ->has('transactions.data', 2)
    );
});

it('allows filtering transactions by method', function () {
    // Arrange
    $viewer = User::factory()->create();
    Transaction::factory()->count(2)->create(['method' => TransactionMethod::Cash]);
    Transaction::factory()->count(3)->create(['method' => TransactionMethod::Card]);

    // Act
    $response = actingAs($viewer)->get(route('transactions.index', ['filter' => ['method' => TransactionMethod::Cash->value]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Index')
        ->has('transactions.data', 2)
    );
});

it('allows filtering transactions by type', function () {
    // Arrange
    $viewer = User::factory()->create();
    Transaction::factory()->count(2)->create(['type' => TransactionType::Payment]);
    Transaction::factory()->count(3)->create(['type' => TransactionType::Deposit]);

    // Act
    $response = actingAs($viewer)->get(route('transactions.index', ['filter' => ['type' => TransactionType::Payment->value]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Index')
        ->has('transactions.data', 2)
    );
});

it('allows sorting transactions by amount', function () {
    // Arrange
    $viewer = User::factory()->create();
    Transaction::factory()->create(['amount' => 100]);
    Transaction::factory()->create(['amount' => 300]);
    Transaction::factory()->create(['amount' => 200]);

    // Act
    $response = actingAs($viewer)->get(route('transactions.index', ['sort' => 'amount']));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Index')
        ->where('transactions.data.0.amount', 100)
        ->where('transactions.data.1.amount', 200)
        ->where('transactions.data.2.amount', 300)
    );
});

it('defaults to sorting by created_at descending', function () {
    // Arrange
    $viewer = User::factory()->create();
    $old = Transaction::factory()->create(['created_at' => now()->subDays(2)]);
    $newest = Transaction::factory()->create(['created_at' => now()]);
    $middle = Transaction::factory()->create(['created_at' => now()->subDay()]);

    // Act
    $response = actingAs($viewer)->get(route('transactions.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('transactions/Index')
        ->where('transactions.data.0.id', $newest->id)
        ->where('transactions.data.1.id', $middle->id)
        ->where('transactions.data.2.id', $old->id)
    );
});
