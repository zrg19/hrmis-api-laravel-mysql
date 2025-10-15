<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="CustomerMeasurement",
 *     title="Customer Measurement",
 *     description="Customer Measurement model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Faizan"),
 *     @OA\Property(property="code", type="string", example="1122"),
 *     @OA\Property(property="phone", type="string", example="03123456789"),
 *     @OA\Property(property="address", type="string", example="123 Main Street", nullable=true),
 *     @OA\Property(property="kameezlength", type="string", example="42", nullable=true),
 *     @OA\Property(property="teera", type="string", example="24", nullable=true),
 *     @OA\Property(property="baazo", type="string", example="19", nullable=true),
 *     @OA\Property(property="chest", type="string", example="21", nullable=true),
 *     @OA\Property(property="neck", type="string", example="15", nullable=true),
 *     @OA\Property(property="daman", type="string", example="23", nullable=true),
 *     @OA\Property(property="kera", type="string", example="gol", nullable=true),
 *     @OA\Property(property="shalwar", type="string", example="42", nullable=true),
 *     @OA\Property(property="pancha", type="string", example="12", nullable=true),
 *     @OA\Property(property="pocket", type="string", example="2", nullable=true),
 *     @OA\Property(property="note", type="string", example="Stitching", nullable=true),
 *     @OA\Property(property="created_by", type="integer", example=1, nullable=true),
 *     @OA\Property(property="updated_by", type="integer", example=1, nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
 * )
 * 
 * @OA\Schema(
 *     schema="CreateCustomerMeasurementRequest",
 *     title="Create Customer Measurement Request",
 *     description="Request body for creating a new customer measurement",
 *     required={"name", "code", "phone"},
 *     @OA\Property(property="name", type="string", example="Faizan"),
 *     @OA\Property(property="code", type="string", example="1122"),
 *     @OA\Property(property="phone", type="string", example="03123456789"),
 *     @OA\Property(property="address", type="string", example="123 Main Street"),
 *     @OA\Property(property="kameezlength", type="string", example="42"),
 *     @OA\Property(property="teera", type="string", example="24"),
 *     @OA\Property(property="baazo", type="string", example="19"),
 *     @OA\Property(property="chest", type="string", example="21"),
 *     @OA\Property(property="neck", type="string", example="15"),
 *     @OA\Property(property="daman", type="string", example="23"),
 *     @OA\Property(property="kera", type="string", example="gol"),
 *     @OA\Property(property="shalwar", type="string", example="42"),
 *     @OA\Property(property="pancha", type="string", example="12"),
 *     @OA\Property(property="pocket", type="string", example="2"),
 *     @OA\Property(property="note", type="string", example="Stitching")
 * )
 * 
 * @OA\Schema(
 *     schema="UpdateCustomerMeasurementRequest",
 *     title="Update Customer Measurement Request",
 *     description="Request body for updating a customer measurement",
 *     @OA\Property(property="name", type="string", example="Faizan"),
 *     @OA\Property(property="code", type="string", example="1122"),
 *     @OA\Property(property="phone", type="string", example="03123456789"),
 *     @OA\Property(property="address", type="string", example="123 Main Street"),
 *     @OA\Property(property="kameezlength", type="string", example="42"),
 *     @OA\Property(property="teera", type="string", example="24"),
 *     @OA\Property(property="baazo", type="string", example="19"),
 *     @OA\Property(property="chest", type="string", example="21"),
 *     @OA\Property(property="neck", type="string", example="15"),
 *     @OA\Property(property="daman", type="string", example="23"),
 *     @OA\Property(property="kera", type="string", example="gol"),
 *     @OA\Property(property="shalwar", type="string", example="42"),
 *     @OA\Property(property="pancha", type="string", example="12"),
 *     @OA\Property(property="pocket", type="string", example="2"),
 *     @OA\Property(property="note", type="string", example="Stitching")
 * )
 */
class CustomerMeasurement extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'phone',
        'address',
        'kameezlength',
        'teera',
        'baazo',
        'chest',
        'neck',
        'daman',
        'kera',
        'shalwar',
        'pancha',
        'pocket',
        'note',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created this measurement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this measurement.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
