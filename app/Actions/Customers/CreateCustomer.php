<?php

namespace App\Actions\Customers;

use App\Enums\CustomerType;
use App\Models\Customer;

class CreateCustomer
{
    public function handle(array $data): Customer
    {
        $isBusiness = ($data['type'] ?? null) === CustomerType::Business->value;

        return Customer::create([
            'name' => $data['name'],
            'company' => $isBusiness ? ($data['company'] ?? null) : null,
            'tax_number' => $isBusiness ? ($data['tax_number'] ?? null) : null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'note' => $data['note'] ?? null,
            'type' => $data['type'] ?? null,
            'status' => $data['status'] ?? null,
        ]);
    }
}
