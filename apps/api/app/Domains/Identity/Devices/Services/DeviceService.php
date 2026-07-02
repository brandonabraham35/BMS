<?php

namespace App\Domains\Identity\Devices\Services;

use App\Models\User;
use App\Models\TrustedDevice;
use Illuminate\Support\Collection;
use App\Services\AuditLogger;

class DeviceService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function getDevices(User $user): Collection
    {
        return TrustedDevice::where('user_id', $user->id)->get();
    }

    public function recordDevice(User $user, array $deviceData): TrustedDevice
    {
        $device = TrustedDevice::updateOrCreate(
            ['user_id' => $user->id, 'device_id' => $deviceData['device_id']],
            [
                'name' => $deviceData['name'] ?? null,
                'browser' => $deviceData['browser'] ?? null,
                'os' => $deviceData['os'] ?? null,
                'ip_address' => request()->ip(),
                'last_active_at' => now(),
            ]
        );

        return $device;
    }

    public function trustDevice(User $user, string $deviceId): void
    {
        TrustedDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->update(['is_trusted' => true]);

        $this->auditLogger->log('device_trusted', $user, null, ['device_id' => $deviceId]);
    }

    public function removeDevice(User $user, string $deviceId): void
    {
        TrustedDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->delete();

        $this->auditLogger->log('device_removed', $user, null, ['device_id' => $deviceId]);
    }
}
