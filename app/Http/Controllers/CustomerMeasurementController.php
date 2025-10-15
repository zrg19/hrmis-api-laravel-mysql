<?php

namespace App\Http\Controllers;

use App\Models\CustomerMeasurement;
use App\Http\Requests\CreateCustomerMeasurementRequest;
use App\Http\Requests\UpdateCustomerMeasurementRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Customer Measurements",
 *     description="API endpoints for managing customer measurements"
 * )
 */
class CustomerMeasurementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customer-measurements",
     *     summary="Get all customer measurements",
     *     description="Retrieve a paginated list of all customer measurements",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for name, code, or phone",
     *         required=false,
     *         @OA\Schema(type="string", example="Faizan")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CustomerMeasurement")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = CustomerMeasurement::with(['creator', 'updater']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $customerMeasurements = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($customerMeasurements);
    }

    /**
     * @OA\Post(
     *     path="/api/customer-measurements",
     *     summary="Create a new customer measurement",
     *     description="Create a new customer measurement record",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateCustomerMeasurementRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer measurement created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer measurement created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CustomerMeasurement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(CreateCustomerMeasurementRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        $customerMeasurement = CustomerMeasurement::create($data);
        $customerMeasurement->load(['creator', 'updater']);

        return response()->json([
            'message' => 'Customer measurement created successfully',
            'data' => $customerMeasurement
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/customer-measurements/{id}",
     *     summary="Get a specific customer measurement",
     *     description="Retrieve a specific customer measurement by ID",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer measurement ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/CustomerMeasurement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer measurement not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $customerMeasurement = CustomerMeasurement::with(['creator', 'updater'])->findOrFail($id);

        return response()->json([
            'data' => $customerMeasurement
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/customer-measurements/{id}",
     *     summary="Update a customer measurement",
     *     description="Update an existing customer measurement record",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer measurement ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCustomerMeasurementRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer measurement updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer measurement updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CustomerMeasurement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer measurement not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(UpdateCustomerMeasurementRequest $request, string $id): JsonResponse
    {
        $customerMeasurement = CustomerMeasurement::findOrFail($id);
        
        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $customerMeasurement->update($data);
        $customerMeasurement->load(['creator', 'updater']);

        return response()->json([
            'message' => 'Customer measurement updated successfully',
            'data' => $customerMeasurement
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/customer-measurements/{id}",
     *     summary="Delete a customer measurement",
     *     description="Soft delete a customer measurement record",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer measurement ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer measurement deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer measurement deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer measurement not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $customerMeasurement = CustomerMeasurement::findOrFail($id);
        $customerMeasurement->delete();

        return response()->json([
            'message' => 'Customer measurement deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/customer-measurements/trashed",
     *     summary="Get soft deleted customer measurements",
     *     description="Retrieve a list of soft deleted customer measurements",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CustomerMeasurement")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function trashed(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $customerMeasurements = CustomerMeasurement::onlyTrashed()
            ->with(['creator', 'updater'])
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);

        return response()->json($customerMeasurements);
    }

    /**
     * @OA\Post(
     *     path="/api/customer-measurements/{id}/restore",
     *     summary="Restore a soft deleted customer measurement",
     *     description="Restore a soft deleted customer measurement",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer measurement ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer measurement restored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer measurement restored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CustomerMeasurement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer measurement not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function restore(string $id): JsonResponse
    {
        $customerMeasurement = CustomerMeasurement::onlyTrashed()->findOrFail($id);
        $customerMeasurement->restore();
        $customerMeasurement->load(['creator', 'updater']);

        return response()->json([
            'message' => 'Customer measurement restored successfully',
            'data' => $customerMeasurement
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/customer-measurements/{id}/force-delete",
     *     summary="Permanently delete a customer measurement",
     *     description="Permanently delete a customer measurement from the database",
     *     tags={"Customer Measurements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer measurement ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer measurement permanently deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer measurement permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer measurement not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function forceDelete(string $id): JsonResponse
    {
        $customerMeasurement = CustomerMeasurement::onlyTrashed()->findOrFail($id);
        $customerMeasurement->forceDelete();

        return response()->json([
            'message' => 'Customer measurement permanently deleted'
        ]);
    }
}
