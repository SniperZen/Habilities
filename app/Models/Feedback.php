<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if it matches the plural form of the model name)
    protected $table = 'feedback';

    // Define the fillable attributes
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'title',
        'content',
    ];
    protected $dates = ['created_at', 'updated_at'];


    // Define relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
