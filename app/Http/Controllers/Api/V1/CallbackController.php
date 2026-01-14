<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\Callback\StoreCallbackAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $token, StoreCallbackAction $action)
    {
        if (! $action->handle(
            $token,
            $request->all(),
            $request->ip(),
            $request->userAgent()
        )) {
            return $this->responseWithError('Invalid token', 404);
        }

        return $this->responseWithSuccess('Callback received successfully');
    }
}
