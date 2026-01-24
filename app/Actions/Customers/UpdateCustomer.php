<?php

namespace App\Actions\Customers;

use App\Enums\CustomerType;
use App\Models\Customer;

class UpdateCustomer
{
    public function handle(Customer $customer, array $data): Customer
    {
        $isBusiness = ($data['type'] ?? $customer->type->value) === CustomerType::Business->value;

        $customer->update([
            'name' => $data['name'],
            'company' => $isBusiness ? ($data['company'] ?? null) : null,
            'tax_number' => $isBusiness ? ($data['tax_number'] ?? null) : null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'note' => $data['note'] ?? null,
            'type' => $data['type'],
            'status' => $data['status'],
        ]);

        return $customer;
    }
}
