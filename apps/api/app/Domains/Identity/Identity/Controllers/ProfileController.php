<?php

namespace App\Domains\Identity\Identity\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Identity\Services\ProfileService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function __construct(protected ProfileService $profileService) {}

    public function show(Request $request): JsonResponse
    {
        return $this->success($request->user(), 'Profile retrieved');
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:5',
            'job_title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $user = $this->profileService->updateProfile($request->user(), $data);
        return $this->success($user, 'Profile updated');
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $preferences = $request->validate([
            'preferences' => 'required|array',
        ]);

        $user = $this->profileService->updatePreferences($request->user(), $preferences['preferences']);
        return $this->success($user, 'Preferences updated');
    }
}
