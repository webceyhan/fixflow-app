<?php

namespace App\Actions\Devices;

use App\Models\Device;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryDevices
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Device::class)
            ->with('customer')
            ->allowedFilters([
                AllowedFilter::search(['model', 'brand', 'imei', 'serial_number']),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('customer_id'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['model', 'brand', 'purchase_date', 'warranty_expire_date', 'created_at']);
    }
}
