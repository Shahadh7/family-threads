<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        
        return inertia('MemoryItem/Index',[
            'memoryItems' => $memoryItems,
            'flash' => session()->get('flash')
        ]);
    }
}
