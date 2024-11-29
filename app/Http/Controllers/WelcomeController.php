<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Add this at the top of your file

class WelcomeController extends Controller
{
// Sa WelcomeController mo, add this temporary debug:
public function index()
{
    $settings = BusinessSetting::first();
    Log::info('Welcome Page Settings:', ['settings' => $settings->toArray()]);
    return view('welcome', compact('settings'));
}



}
