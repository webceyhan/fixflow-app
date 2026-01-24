<?php

namespace App\Actions\Customers;

use App\Models\Customer;

class DeleteCustomer
{
    public function handle(Customer $customer): bool
    {
        return $customer->delete();
    }
}
