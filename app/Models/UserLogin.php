<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id', 'login_at'];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if necessary
    }
}
