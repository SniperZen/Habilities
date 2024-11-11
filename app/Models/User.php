<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'last_name',
        'first_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'contact_number',
        'home_address',
        'password',
        'usertype',
        'account_type',
        'profile_image',
        'specialization',
        'name',
        'avatar',
        'availability',
        'account_status',
        'teletherapist_link' => 'nullable|url',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    protected static function boot()
    {
        parent::boot();
    
        static::saving(function ($user) {
            $user->name = $user->full_name;
    
            // Update avatar if profile_image is changed
            if ($user->isDirty('profile_image')) {
                $user->avatar = basename($user->profile_image);
            }
        });
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function therapistAppointments()
    {
        return $this->hasMany(Appointment::class, 'therapist_id');
    }
    
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'recipient_id');
    }
    
    public function sentFeedback()
    {
        return $this->hasMany(Feedback::class, 'sender_id');
    }
    public function scopeActive($query)
    {
        return $query->where('account_status', 'active');
    }
    public function feedbacks()
{
    return $this->hasMany(PatientFeedback::class);
}


}
