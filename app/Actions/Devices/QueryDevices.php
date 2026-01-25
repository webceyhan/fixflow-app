<?php

namespace App\Actions\Devices;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Device;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryDevices
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Device::class)
            ->with('customer')
            ->allowedFilters([
                $this->searchFilter(['model', 'brand', 'imei', 'serial_number']),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('customer_id'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['model', 'brand', 'purchase_date', 'warranty_expire_date', 'created_at'])
            ->defaultSort('-created_at');
    }
}
