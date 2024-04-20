<?php

use App\Models\Device;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all devices', function () {
    $user = User::factory()->create();
    $devices = Device::factory(2)->create();

    $response = $this->actingAs($user)->get('/devices');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Devices/Index')
                ->has('devices', 2)
                ->has(
                    'devices.0',
                    fn (Assert $page) => $page
                        ->where('id', $devices->first()->id)
                        ->where('customer_id', $devices->first()->customer_id)
                        ->where('model', $devices->first()->model)
                        ->where('brand', $devices->first()->brand)
                        ->where('serial_number', $devices->first()->serial_number)
                        ->where('warranty_expire_date', $devices->first()->warranty_expire_date)
                        ->where('type', $devices->first()->type->value)
                        ->where('status', $devices->first()->status->value)
                        ->etc()
                )
        );
});
