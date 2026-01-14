<?php

namespace App\Http\Controllers\Cms\Device;

use App\Http\Controllers\Controller;
use App\Models\Wuz\CallbackLog;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CallbackLogController extends Controller
{
    use WithGetFilterData;

    protected string $resource = CallbackLog::class;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view'.$this->resource);

        $order = $request?->order ?? 'desc';
        $orderBy = $request?->orderBy ?? 'callback_logs.created_at';
        $paginate = $request?->paginate ?? 10;
        $searchBySpecific = $request?->searchBySpecific ?? '';
        $search = $request?->search ?? '';

        // Query
        $model = CallbackLog::query()
            ->join('devices', 'callback_logs.device_id', '=', 'devices.id')
            ->select('callback_logs.*', 'devices.name as device_name');

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'callback_logs.event_type',
                'devices.name',
                'callback_logs.ip_address',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/device/callback-log/Index', [
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
     * Display the specified resource.
     */
    public function show(CallbackLog $callbackLog)
    {
        Gate::authorize('show'.$this->resource);

        return inertia('cms/device/callback-log/Show', [
            'callbackLog' => $callbackLog->load('device'),
        ]);
    }
}
