<?php

use App\Models\User;
use Illuminate\Support\Carbon;

it('can initialize user', function () {
    $user = new User();

    expect($user->id)->toBeNull();
    expect($user->name)->toBeNull();
    expect($user->email)->toBeNull();
    expect($user->password)->toBeNull();
    expect($user->remember_token)->toBeNull();
    expect($user->created_at)->toBeNull();
    expect($user->updated_at)->toBeNull();
    expect($user->email_verified_at)->toBeNull();
});

it('can create user', function () {
    $user = User::factory()->create();

    expect($user->id)->toBeInt();
    expect($user->name)->toBeString();
    expect($user->email)->toBeString();
    expect($user->password)->toBeString();
    expect($user->remember_token)->toBeString();
    expect($user->created_at)->toBeInstanceOf(Carbon::class);
    expect($user->updated_at)->toBeInstanceOf(Carbon::class);
    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
});

it('can create user unverified', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

it('can update user', function () {
    $user = User::factory()->create();

    $user->update([
        'name' => 'Bill Gates',
        'email' => 'bill.gates@mail.com',
    ]);

    expect($user->name)->toBe('Bill Gates');
    expect($user->email)->toBe('bill.gates@mail.com');
});

it('can delete user', function () {
    $user = User::factory()->create();

    $user->delete();

    expect(User::find($user->id))->toBeNull();
});
