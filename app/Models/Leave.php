<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'start_date', 'end_date', 'reason', 'status', 'requested_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}