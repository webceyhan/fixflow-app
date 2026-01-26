<?php

namespace App\Http\Controllers;

use App\Actions\Devices\CreateDevice;
use App\Actions\Devices\DeleteDevice;
use App\Actions\Devices\QueryDevices;
use App\Actions\Devices\UpdateDevice;
use App\Http\Requests\Devices\DeviceStoreRequest;
use App\Http\Requests\Devices\DeviceUpdateRequest;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryDevices $queryDevices): Response
    {
        $devices = $queryDevices->handle()->paginate();

        return Inertia::render('devices/Index', [
            'devices' => $devices,
            'filter' => request('filter'),
            'isAdmin' => request()->user()->isAdmin(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer): Response
    {
        $device = new Device(['customer_id' => $customer->id]);

        return Inertia::render('devices/Create', [
            'device' => $device,
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeviceStoreRequest $request, Customer $customer, CreateDevice $createDevice): RedirectResponse
    {
        $device = $createDevice->handle([
            ...$request->validated(),
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('devices.show', $device);
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device): Response
    {
        $device->load(['customer', 'tickets']);

        return Inertia::render('devices/Show', [
            'device' => $device,
            'isAdmin' => request()->user()->isAdmin(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device): Response
    {
        $device->load('customer');

        return Inertia::render('devices/Edit', [
            'device' => $device,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeviceUpdateRequest $request, Device $device, UpdateDevice $updateDevice): RedirectResponse
    {
        $updateDevice->handle($device, $request->validated());

        return redirect()->route('devices.show', $device);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device, DeleteDevice $deleteDevice): RedirectResponse
    {
        $deleteDevice->handle($device);

        return redirect()->route('devices.index');
    }
}
