<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\TimeThread;
use Inertia\Inertia;

class TimeThreadController extends Controller
{
    public function index() {
        return inertia('TimeThread/Index');
    }
}
