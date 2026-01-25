<?php

namespace App\Actions\Devices;

use App\Models\Device;

class CreateDevice
{
    public function handle(array $data): Device
    {
        return Device::create([
            'customer_id' => $data['customer_id'],
            'model' => $data['model'],
            'brand' => $data['brand'],
            'color' => $data['color'] ?? null,
            'imei' => $data['imei'] ?? null,
            'serial_number' => $data['serial_number'] ?? null,
            'passcode' => $data['passcode'] ?? null,
            'purchase_date' => $data['purchase_date'] ?? null,
            'warranty_expire_date' => $data['warranty_expire_date'] ?? null,
            'note' => $data['note'] ?? null,
            'type' => $data['type'] ?? null,
            'status' => $data['status'] ?? null,
        ]);
    }
}
