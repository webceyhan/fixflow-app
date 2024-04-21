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

it('can create a user', function () {
    $admin = User::factory()->asAdmin()->create();

    // render
    $response = $this->actingAs($admin)->get('/users/create');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Users/Edit')
                ->has('user')
        );

    // store
    $data = [
        'name' => 'John Doe',
        'email' => 'j.doa@mail.com',
        'phone' => '1234567890',
    ];

    $response = $this->actingAs($admin)->post('/users', $data);

    $response->assertRedirect('/users/' . User::where($data)->first()->id);
});

it('can update a user', function () {
    $admin = User::factory()->asAdmin()->create();
    $user = User::factory()->create();

    // render
    $response = $this->actingAs($admin)->get('/users/' . $user->id . '/edit');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Users/Edit')
                ->has('user')
        );

    // update
    $data = [
        'name' => 'John Doe',
        'email' => 'j.doa@mail.com',
        'phone' => '1234567890',
    ];

    $response = $this->actingAs($admin)->put('/users/' . $user->id, $data);

    $response->assertRedirect('/users/' . $user->id);
    $this->assertDatabaseHas('users', $data);
});

it('can delete a user', function () {
    $admin = User::factory()->asAdmin()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($admin)->delete('/users/' . $user->id);

    $response->assertRedirect('/users');

    $this->assertNull($user->fresh());
});
