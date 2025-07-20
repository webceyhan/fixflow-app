<?php

namespace App\Observers;

use App\Models\Device;

class DeviceObserver
{
    /**
     * Handle the Device "created" event.
     */
    public function created(Device $device): void
    {
        $device->load('customer');
        $device->customer->fillDeviceCounts()->save();
    }

    /**
     * Handle the Device "updated" event.
     */
    public function updated(Device $device): void
    {
        // Only update counters if status was changed
        if ($device->wasChanged(['status'])) {
            $device->load('customer');
            $device->customer->fillDeviceCounts()->save();
        }
    }

    /**
     * Handle the Device "deleted" event.
     */
    public function deleted(Device $device): void
    {
        $device->load('customer');
        $device->customer->fillDeviceCounts()->save();
    }
}
