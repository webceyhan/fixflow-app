<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all users', function () {
    $user = User::factory()->create();
    User::factory(2)->create();

    $response = $this->actingAs($user)->get('/users');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Users/Index')
                ->has('users', 3)
                ->has(
                    'users.0',
                    fn (Assert $page) => $page
                        ->where('id', $user->id)
                        ->where('name', $user->name)
                        ->where('email', $user->email)
                        ->where('phone', $user->phone)
                        ->where('role', $user->role->value)
                        ->where('status', $user->status->value)
                        ->etc()
                )
        );
});

it('can view a user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/users/' . $user->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Users/Show')
                ->has(
                    'user',
                    fn (Assert $page) => $page
                        ->where('id', $user->id)
                        ->where('name', $user->name)
                        ->where('email', $user->email)
                        ->where('phone', $user->phone)
                        ->where('role', $user->role->value)
                        ->where('status', $user->status->value)
                        ->etc()
                )
        );
});
