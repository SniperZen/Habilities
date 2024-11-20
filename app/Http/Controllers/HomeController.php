<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function home()
    {
        if (Auth::check()) {
            $usertype = Auth::user()->usertype;
            
            $redirectPaths = [
                'admin' => '/admin/dash',
                'therapist' => '/therapist/dash',
                'user' => '/patient/dash'
            ];
            
            return redirect($redirectPaths[$usertype] ?? '/dashboard');
        }
        
        return view('welcome');
    }
    
}
