<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Devices/Index', [
            'devices' => Device::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeviceStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device): Response
    {
        return Inertia::render('Devices/Show', [
            'device' => $device,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeviceUpdateRequest $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device): RedirectResponse
    {
        $device->delete();

        return Redirect::route('devices.index');
    }
}
