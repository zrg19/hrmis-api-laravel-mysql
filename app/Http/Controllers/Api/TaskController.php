<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Task Management",
 *     description="API Endpoints for managing employee tasks and assignments (Admin/Manager only)"
 * )
 */
class TaskController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level in routes/api.php
    }

    /**
     * @OA\Get(
     *     path="/tasks",
     *     operationId="getTasks",
     *     tags={"Task Management"},
     *     summary="Get all tasks",
     *     description="Retrieve a list of all tasks with assigned user information (Admin/Manager access required)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Complete project documentation"),
     *                 @OA\Property(property="description", type="string", example="Write comprehensive documentation for the new feature"),
     *                 @OA\Property(property="priority", type="string", enum={"Low", "Medium", "High", "Critical"}, example="High"),
     *                 @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed", "On Hold"}, example="In Progress"),
     *                 @OA\Property(property="assigned_to", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="assigned_to_user",
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
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Insufficient permissions (Admin/Manager role required)"
     *     )
     * )
     */
    public function index()
    {
        $tasks = Task::with('assignedTo')->get();
        return response()->json($tasks);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     operationId="createTask",
     *     tags={"Task Management"},
     *     summary="Create a new task",
     *     description="Create a new task and assign it to an employee (Admin/Manager access required)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","priority","status","assigned_to"},
     *             @OA\Property(property="title", type="string", example="Complete project documentation", description="Task title"),
     *             @OA\Property(property="description", type="string", example="Write comprehensive documentation for the new feature", description="Detailed task description"),
     *             @OA\Property(property="priority", type="string", enum={"Low", "Medium", "High", "Critical"}, example="High", description="Task priority level"),
     *             @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed", "On Hold"}, example="Pending", description="Current task status"),
     *             @OA\Property(property="assigned_to", type="integer", example=1, description="ID of the user assigned to the task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Complete project documentation"),
     *             @OA\Property(property="description", type="string", example="Write comprehensive documentation for the new feature"),
     *             @OA\Property(property="priority", type="string", example="High"),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="assigned_to", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Insufficient permissions (Admin/Manager role required)"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - Invalid input data"
     *     )
     * )
     */
    public function store(CreateTaskRequest $request)
    {
        $task = Task::create($request->validated());
        return response()->json($task, 201);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     operationId="getTask",
     *     tags={"Task Management"},
     *     summary="Get a specific task",
     *     description="Retrieve details of a specific task by ID (Admin/Manager access required)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Complete project documentation"),
     *             @OA\Property(property="description", type="string", example="Write comprehensive documentation for the new feature"),
     *             @OA\Property(property="priority", type="string", example="High"),
     *             @OA\Property(property="status", type="string", example="In Progress"),
     *             @OA\Property(property="assigned_to", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="assigned_to_user",
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
     *         response=403,
     *         description="Forbidden - Insufficient permissions (Admin/Manager role required)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(Task $task)
    {
        return response()->json($task->load('assignedTo'));
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     operationId="updateTask",
     *     tags={"Task Management"},
     *     summary="Update a task",
     *     description="Update an existing task (Admin/Manager access required)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Complete project documentation"),
     *             @OA\Property(property="description", type="string", example="Write comprehensive documentation for the new feature"),
     *             @OA\Property(property="priority", type="string", enum={"Low", "Medium", "High", "Critical"}, example="High"),
     *             @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed", "On Hold"}, example="Completed"),
     *             @OA\Property(property="assigned_to", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Complete project documentation"),
     *             @OA\Property(property="description", type="string", example="Write comprehensive documentation for the new feature"),
     *             @OA\Property(property="priority", type="string", example="High"),
     *             @OA\Property(property="status", type="string", example="Completed"),
     *             @OA\Property(property="assigned_to", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Insufficient permissions (Admin/Manager role required)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - Invalid input data"
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     operationId="deleteTask",
     *     tags={"Task Management"},
     *     summary="Delete a task",
     *     description="Permanently delete a task (Admin/Manager access required)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Task deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Insufficient permissions (Admin/Manager role required)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     *     )
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}