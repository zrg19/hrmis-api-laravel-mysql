<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     title="Login Request",
 *     description="Request body for user login",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123")
 * )
 * 
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="Register Request",
 *     description="Request body for user registration",
 *     required={"name", "email", "password", "department", "designation"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 *     @OA\Property(property="department", type="string", example="IT"),
 *     @OA\Property(property="designation", type="string", example="Developer"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="role", type="string", example="Employee", enum={"Admin", "Manager", "Employee"})
 * )
 * 
 * @OA\Schema(
 *     schema="LoginResponse",
 *     title="Login Response",
 *     description="Response for successful login",
 *     @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *     @OA\Property(property="token_type", type="string", example="bearer"),
 *     @OA\Property(property="expires_in", type="integer", example=3600)
 * )
 * 
 * @OA\Schema(
 *     schema="RegisterResponse",
 *     title="Register Response",
 *     description="Response for successful registration",
 *     @OA\Property(property="message", type="string", example="User successfully registered"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="access_token", type="string")
 * )
 * 
 * @OA\Schema(
 *     schema="LogoutResponse",
 *     title="Logout Response",
 *     description="Response for successful logout",
 *     @OA\Property(property="message", type="string", example="Successfully logged out")
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     title="Error Response",
 *     description="Standard error response",
 *     @OA\Property(property="error", type="string", example="Unauthorized"),
 *     @OA\Property(property="message", type="string", example="Unauthenticated"),
 *     @OA\Property(property="exception", type="string"),
 *     @OA\Property(property="file", type="string"),
 *     @OA\Property(property="line", type="integer"),
 *     @OA\Property(property="trace", type="array", @OA\Items(type="object"))
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     title="Validation Error Response",
 *     description="Response for validation errors",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(property="errors", type="object", additionalProperties=@OA\Schema(type="array", @OA\Items(type="string")))
 * )
 */
class SwaggerSchemas
{
    // This class is only used for Swagger documentation
}
