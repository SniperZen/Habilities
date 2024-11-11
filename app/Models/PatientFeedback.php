<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientFeedback extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the default naming convention
    protected $table = 'patient_feedback';

    // Specify which fields can be mass-assigned
    protected $fillable = [
        'user_id',
        'feedback',
    ];

    // Specify date fields to be treated as Carbon instances
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // You can add any additional methods here if needed
}
