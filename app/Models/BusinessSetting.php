<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'business_hours'
    ];

    protected $casts = [
        'business_hours' => 'array'
    ];

    // Helper method to get business hours for a specific day
    public function getHoursForDay($day)
    {
        $hours = $this->business_hours[strtolower($day)] ?? null;
        return $hours;
    }

    // Helper method to check if business is open for a specific day
    public function isOpenOnDay($day)
    {
        $hours = $this->getHoursForDay($day);
        return $hours && !($hours['is_closed'] ?? true);
    }

    // Helper method to get all settings
    public static function getSettings()
    {
        return self::first();
    }
}