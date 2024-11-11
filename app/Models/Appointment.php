<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'mode',
        'note',
        'status',
        'start_time',
        'end_time',
        'cancellation_reason',
        'cancellation_note',
        'patient_reason',
        'patient_note',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];


// In App\Models\Appointment.php
public function therapist()
{
    return $this->belongsTo(User::class, 'therapist_id')->where('usertype', 'therapist');
}

public function patient()
{
    return $this->belongsTo(User::class, 'patient_id')->where('usertype', 'user');
}


    public function user(): BelongsTo
    {
        return $this->patient();
    }
}
