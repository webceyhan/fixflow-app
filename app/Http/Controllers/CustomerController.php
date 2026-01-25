<?php

namespace App\Http\Controllers;

use App\Actions\Customers\CreateCustomer;
use App\Actions\Customers\DeleteCustomer;
use App\Actions\Customers\QueryCustomers;
use App\Actions\Customers\UpdateCustomer;
use App\Http\Requests\Customers\CustomerStoreRequest;
use App\Http\Requests\Customers\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryCustomers $queryCustomers): Response
    {
        $customers = $queryCustomers->handle()->paginate();

        return Inertia::render('customers/Index', [
            'customers' => $customers,
            'filter' => request('filter'),
            'isAdmin' => request()->user()->isAdmin(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('customers/Create', [
            'customer' => new Customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request, CreateCustomer $createCustomer): RedirectResponse
    {
        $customer = $createCustomer->handle($request->validated());

        return redirect()->route('customers.show', $customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): Response
    {
        $customer->load('devices');

        return Inertia::render('customers/Show', [
            'customer' => $customer,
            'isAdmin' => request()->user()->isAdmin(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): Response
    {
        return Inertia::render('customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer, UpdateCustomer $updateCustomer): RedirectResponse
    {
        $updateCustomer->handle($customer, $request->validated());

        return redirect()->route('customers.show', $customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer, DeleteCustomer $deleteCustomer): RedirectResponse
    {
        $deleteCustomer->handle($customer);

        return redirect()->route('customers.index');
    }
}
