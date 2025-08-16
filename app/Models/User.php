<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="department", type="string", example="IT"),
 *     @OA\Property(property="designation", type="string", example="Developer"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="role", type="string", example="Employee", enum={"Admin", "Manager", "Employee"}),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="CreateUserRequest",
 *     title="Create User Request",
 *     description="Request body for creating a new user",
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
 *     schema="UpdateUserRequest",
 *     title="Update User Request",
 *     description="Request body for updating a user",
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="department", type="string", example="IT"),
 *     @OA\Property(property="designation", type="string", example="Developer"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="role", type="string", example="Employee", enum={"Admin", "Manager", "Employee"}),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The guard name for the Spatie Permission package
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'designation',
        'phone',
        'address',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'requested_by');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }
}
