<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MemoryItemController extends Controller
{
    public function index() {
        return inertia('MemoryItem/Index');
    }

    public function create()
    {
        return Inertia::render('MemoryItem/Create');
    }
}
