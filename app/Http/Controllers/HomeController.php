<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function  home(){
        $settings = BusinessSetting::first();
        return view('welcome', compact('settings'));
     }
}
