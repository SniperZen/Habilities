<?php

namespace App\Http\Controllers;
use App\Models\Appointment;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all(); 
        return view('patient.dash', compact('appointments'));
    }

    public function showAppointmentsDash()
    {
        $appointments = Appointment::all(); 
        return view('patient.dash', compact('appointments'));
    }
}
