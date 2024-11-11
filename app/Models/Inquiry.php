<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'concerns', 
        'elaboration', 
        'identification_card', 
        'birth_certificate', 
        'diagnosis_reports',
        'created_at',
        'completed_at',
    ];

    /**
     * Get the user that owns the inquiry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

