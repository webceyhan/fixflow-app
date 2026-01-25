<?php

namespace App\Actions\Devices;

use App\Models\Device;

class DeleteDevice
{
    public function handle(Device $device): bool
    {
        return $device->delete();
    }
}
