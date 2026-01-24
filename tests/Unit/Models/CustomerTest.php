<?php

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Customer::class,
    concerns: [
        HasActiveStatus::class,
        HasStatus::class,
        HasType::class,
    ],
    defaults: [
        'type' => CustomerType::Individual,
        'status' => CustomerStatus::Active,
    ],
    fillables: [
        'name',
        'company',
        'tax_number',
        'email',
        'phone',
        'address',
        'note',
        'type',
        'status',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'type' => CustomerType::class,
        'status' => CustomerStatus::class,
    ],
    relations: [
        // TODO
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

// METHOD TESTS ///////////////////////////////////////////////////////////////////////////////////
