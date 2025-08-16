<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="HRMIS API Documentation",
 *     description="Human Resource Management Information System API",
 *     @OA\Contact(
 *         email="admin@hrmis.com",
 *         name="HRMIS Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for user management"
 * )
 * 
 * @OA\Tag(
 *     name="Tasks",
 *     description="API Endpoints for task management"
 * )
 * 
 * @OA\Tag(
 *     name="Leaves",
 *     description="API Endpoints for leave management"
 * )
 */
abstract class Controller
{
    //
}
