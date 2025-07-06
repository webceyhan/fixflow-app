<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('models', [
    'User' => [User::class],
    'Customer' => [Customer::class],
]);

dataset('optional_models', [
    // User email is not nullable, so we skip it for
    // mailable tests and contactable which makes use of email
    'Customer' => [Customer::class],
]);

it('can determine if model is mailable', function (string $modelClass) {
    // Arrange
    $mailableModel = $modelClass::factory()->mailable()->create();
    $notMailableModel = $modelClass::factory()->notMailable()->create();

    // Assert
    expect($mailableModel->isMailable())->toBeTrue();
    expect($mailableModel->email)->not->toBeNull();
    expect($notMailableModel->isMailable())->toBeFalse();
    expect($notMailableModel->email)->toBeNull();
})->with('optional_models');

it('can determine if model is callable', function (string $modelClass) {
    // Arrange
    $callableModel = $modelClass::factory()->callable()->create();
    $notCallableModel = $modelClass::factory()->notCallable()->create();

    // Assert
    expect($callableModel->isCallable())->toBeTrue();
    expect($callableModel->phone)->not->toBeNull();
    expect($notCallableModel->isCallable())->toBeFalse();
    expect($notCallableModel->phone)->toBeNull();
})->with('models');

it('can determine if model is contactable', function (string $modelClass) {
    // Arrange
    $contactableModel = $modelClass::factory()->contactable()->create();
    $notContactableModel = $modelClass::factory()->notContactable()->create();

    // Assert
    expect($contactableModel->isContactable())->toBeTrue();
    expect($notContactableModel->isContactable())->toBeFalse();
})->with('optional_models');

it('can filter records by mailable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->mailable()->create();
    $modelClass::factory(1)->notMailable()->create();

    // Act
    $mailableModels = $modelClass::query()->mailable()->get();

    // Assert
    expect($mailableModels)->toHaveCount(2);
    expect($mailableModels->first()->isMailable())->toBeTrue();
})->with('optional_models');

it('can filter records by callable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->callable()->create();
    $modelClass::factory(1)->notCallable()->create();

    // Act
    $callableModels = $modelClass::query()->callable()->get();

    // Assert
    expect($callableModels)->toHaveCount(2);
    expect($callableModels->first()->isCallable())->toBeTrue();
})->with('models');

it('can filter records by contactable scope', function (string $modelClass) {
    // Arrange
    $modelClass::factory(2)->contactable()->create();
    $modelClass::factory(1)->notContactable()->create();

    // Act
    $contactableModels = $modelClass::query()->contactable()->get();

    // Assert
    expect($contactableModels)->toHaveCount(2);
    expect($contactableModels->first()->isContactable())->toBeTrue();
})->with('optional_models');
