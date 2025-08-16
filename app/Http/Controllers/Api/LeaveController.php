<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Models\Leave;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Leave Management",
 *     description="API Endpoints for managing employee leave requests"
 * )
 */
class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/leaves",
     *     operationId="getLeaves",
     *     tags={"Leave Management"},
     *     summary="Get all leave requests",
     *     description="Retrieve a list of all leave requests with user information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of leave requests retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="reason", type="string", example="Annual vacation"),
     *                 @OA\Property(property="status", type="string", enum={"Pending", "Approved", "Rejected"}, example="Pending"),
     *                 @OA\Property(property="requested_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="requested_by_user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     )
     * )
     */
    public function index()
    {
        $leaves = Leave::with('requestedBy')->get();
        return response()->json($leaves);
    }

    /**
     * @OA\Post(
     *     path="/leaves",
     *     operationId="createLeave",
     *     tags={"Leave Management"},
     *     summary="Create a new leave request",
     *     description="Submit a new leave request for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"start_date","end_date","reason"},
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-08-15", description="Start date of leave"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-20", description="End date of leave"),
     *             @OA\Property(property="reason", type="string", example="Annual vacation", description="Reason for leave request")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Leave request created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *             @OA\Property(property="reason", type="string", example="Annual vacation"),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="requested_by", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - Invalid input data"
     *     )
     * )
     */
    public function store(CreateLeaveRequest $request)
    {
        $leave = Leave::create([
            ...$request->validated(),
            'requested_by' => auth()->id()
        ]);
        return response()->json($leave, 201);
    }

    /**
     * @OA\Get(
     *     path="/leaves/{id}",
     *     operationId="getLeave",
     *     tags={"Leave Management"},
     *     summary="Get a specific leave request",
     *     description="Retrieve details of a specific leave request by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Leave request ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave request details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *             @OA\Property(property="reason", type="string", example="Annual vacation"),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="requested_by", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="requested_by_user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Leave request not found"
     *     )
     * )
     */
    public function show(Leave $leave)
    {
        return response()->json($leave->load('requestedBy'));
    }

    /**
     * @OA\Put(
     *     path="/leaves/{id}",
     *     operationId="updateLeave",
     *     tags={"Leave Management"},
     *     summary="Update a leave request",
     *     description="Update an existing leave request",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Leave request ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *             @OA\Property(property="reason", type="string", example="Annual vacation"),
     *             @OA\Property(property="status", type="string", enum={"Pending", "Approved", "Rejected"}, example="Approved")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave request updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *             @OA\Property(property="reason", type="string", example="Annual vacation"),
     *             @OA\Property(property="status", type="string", example="Approved"),
     *             @OA\Property(property="requested_by", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Leave request not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - Invalid input data"
     *     )
     * )
     */
    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        $leave->update($request->validated());
        return response()->json($leave);
    }

    /**
     * @OA\Delete(
     *     path="/leaves/{id}",
     *     operationId="deleteLeave",
     *     tags={"Leave Management"},
     *     summary="Delete a leave request",
     *     description="Permanently delete a leave request",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Leave request ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave request deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Leave deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Leave request not found"
     *     )
     * )
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();
        return response()->json(['message' => 'Leave deleted successfully']);
    }

    /**
     * @OA\Get(
     *     path="/leaves/user/{id}",
     *     operationId="getLeavesByUserId",
     *     tags={"Leave Management"},
     *     summary="Get leaves by user ID",
     *     description="Retrieve all leave requests for a specific user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User leaves retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="reason", type="string", example="Annual vacation"),
     *                 @OA\Property(property="status", type="string", example="Pending"),
     *                 @OA\Property(property="requested_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     )
     * )
     */
    public function findByUserId($id)
    {
        $leaves = Leave::where('requested_by', $id)->get();
        return response()->json($leaves);
    }

    /**
     * @OA\Get(
     *     path="/leaves/user/{id}/pending",
     *     operationId="getPendingLeavesByUserId",
     *     tags={"Leave Management"},
     *     summary="Get pending leaves by user ID",
     *     description="Retrieve all pending leave requests for a specific user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pending leaves retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="reason", type="string", example="Annual vacation"),
     *                 @OA\Property(property="status", type="string", example="Pending"),
     *                 @OA\Property(property="requested_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     )
     * )
     */
    public function findPendingLeavesByUserId($id)
    {
        $leaves = Leave::where('requested_by', $id)->where('status', 'Pending')->get();
        return response()->json($leaves);
    }

    /**
     * @OA\Get(
     *     path="/leaves/user/{id}/approved",
     *     operationId="getApprovedLeavesByUserId",
     *     tags={"Leave Management"},
     *     summary="Get approved leaves by user ID",
     *     description="Retrieve all approved leave requests for a specific user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Approved leaves retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="reason", type="string", example="Annual vacation"),
     *                 @OA\Property(property="status", type="string", example="Approved"),
     *                 @OA\Property(property="requested_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     )
     * )
     */
    public function findApprovedLeavesByUserId($id)
    {
        $leaves = Leave::where('requested_by', $id)->where('status', 'Approved')->get();
        return response()->json($leaves);
    }

    /**
     * @OA\Get(
     *     path="/leaves/user/{id}/rejected",
     *     operationId="getRejectedLeavesByUserId",
     *     tags={"Leave Management"},
     *     summary="Get rejected leaves by user ID",
     *     description="Retrieve all rejected leave requests for a specific user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rejected leaves retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-08-15"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="reason", type="string", example="Annual vacation"),
     *                 @OA\Property(property="status", type="string", example="Rejected"),
     *                 @OA\Property(property="requested_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     )
     * )
     */
    public function findRejectedLeavesByUserId($id)
    {
        $leaves = Leave::where('requested_by', $id)->where('status', 'Rejected')->get();
        return response()->json($leaves);
    }
}