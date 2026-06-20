<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserManual;
use App\Helpers\AppHelper;
use App\Traits\ApiResponseTrait;

class UserManualController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', null);
            $status = $request->input('status', 'all');

            $query = UserManual::query();

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('url', 'like', "%$search%");
                });
            }

            // Status filter
            if ($status !== 'all') {
                $query->where('is_active', $status === 'active' ? 1 : 0);
            }

            $data = $query->latest()->paginate($perPage);

            $result = [
                'items' => $data->items(),
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ];

            return $this->successResponse($result, 'User manuals fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed to fetch user manuals.', 500, $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|string|max:500',
                'is_active' => 'nullable|boolean',
            ]);

            // If setting as active, deactivate all others
            if (!empty($validated['is_active']) && $validated['is_active'] === true) {
                UserManual::query()->update(['is_active' => false]);
            }

            $data = UserManual::create($validated);

            AppHelper::userLog(
                $request->user()->id,
                "Created new user manual '{$data->title}' (ID: {$data->id})."
            );

            return $this->successResponse($data, 'User manual created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user manual.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = UserManual::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|string|max:500',
                'is_active' => 'nullable|boolean',
            ]);

            $data->update($validated);

            AppHelper::userLog(
                $request->user()->id,
                "Updated user manual '{$data->title}' (ID: {$id})."
            );

            return $this->successResponse($data, 'User manual updated successfully', 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update user manual.', 500, $e->getMessage());
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $data = UserManual::findOrFail($id);

            $title = $data->title;
            $data->delete();

            AppHelper::userLog(
                $request->user()->id,
                "Deleted user manual '{$title}' (ID: {$id})."
            );

            return $this->successResponse(null, 'User manual deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete user manual.', 500, $e->getMessage());
        }
    }

    public function getActive(Request $request)
    {
        try {
            $data = UserManual::where('is_active', true)->first();

            if (!$data) {
                return $this->errorResponse('No active user manual found.', 404);
            }

            return $this->successResponse($data, 'Active user manual fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch active user manual.', 500, $e->getMessage());
        }
    }
}
