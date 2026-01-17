<?php

namespace App\Http\Controllers\Cms\Device;

use App\Actions\Cms\Device\Device\ConnectDeviceAction;
use App\Actions\Cms\Device\Device\DeleteDeviceAction;
use App\Actions\Cms\Device\Device\DisconnectDeviceAction;
use App\Actions\Cms\Device\Device\StatusDeviceAction;
use App\Actions\Cms\Device\Device\StoreDeviceAction;
use App\Actions\Cms\Device\Device\UpdateDeviceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Device\Device\StoreDeviceRequest;
use App\Http\Requests\Cms\Device\Device\UpdateDeviceRequest;
use App\Models\Wuz\Device;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeviceController extends Controller
{
    use WithGetFilterData;

    protected string $resource = Device::class;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view'.$this->resource);

        $order = $request?->order ?? 'desc';
        $orderBy = $request?->orderBy ?? 'created_at';
        $paginate = $request?->paginate ?? 10;
        $searchBySpecific = $request?->searchBySpecific ?? '';
        $search = $request?->search ?? '';
        $model = $this->getDataWithFilter(
            model: new Device,
            searchBy: [
                'name',
                'jid',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/device/device/Index', [
            'data' => $model,
            'order' => $order,
            'orderBy' => $orderBy,
            'paginate' => $paginate,
            'searchBySpecific' => $searchBySpecific,
            'search' => $search,
            'resource' => $this->resource,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create'.$this->resource);

        return inertia('cms/device/device/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request, StoreDeviceAction $storeAction, ConnectDeviceAction $connectAction)
    {
        Gate::authorize('create'.$this->resource);

        $device = $storeAction->handle($request->validated());
        $connectAction->handle($device);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Device $device, ConnectDeviceAction $connectAction, StatusDeviceAction $statusAction)
    {
        Gate::authorize('update'.$this->resource);

        // Initiate connection only if not polling for status
        if (! $request->header('X-Inertia-Partial-Data')) {
            $connectAction->handle($device);
        }

        // Fetch status (including QR)
        $status = $statusAction->handle($device);

        // Ensure status has default structure if empty
        if (empty($status)) {
            $status = [
                'success' => false,
                'code' => 500,
                'data' => [
                    'connected' => false,
                    'loggedIn' => false,
                    'qrcode' => null,
                ],
            ];
        }

        return inertia('cms/device/device/Connect', [
            'device' => $device,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/device/device/Edit', [
            'device' => $device,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Device $device, UpdateDeviceAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($device, $request->validated());

        return back();
    }

    /**
     * Disconnect the specified resource.
     */
    public function disconnect(Device $device, DisconnectDeviceAction $disconnectAction, StatusDeviceAction $statusAction)
    {
        Gate::authorize('update'.$this->resource);

        $disconnectAction->handle($device);
        $statusAction->handle($device);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device, DeleteDeviceAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($device);

        return back();
    }
}
