<?php

namespace App\Domains\Identity\Devices\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Devices\Services\DeviceService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DevicesController extends Controller
{
    use ApiResponse;

    public function __construct(protected DeviceService $deviceService) {}

    public function index(Request $request): JsonResponse
    {
        $devices = $this->deviceService->getDevices($request->user());
        return $this->success($devices, 'Devices retrieved');
    }

    public function update(Request $request, string $deviceId): JsonResponse
    {
        $this->deviceService->trustDevice($request->user(), $deviceId);
        return $this->success(null, 'Device trusted');
    }

    public function destroy(Request $request, string $deviceId): JsonResponse
    {
        $this->deviceService->removeDevice($request->user(), $deviceId);
        return $this->success(null, 'Device removed');
    }
}
