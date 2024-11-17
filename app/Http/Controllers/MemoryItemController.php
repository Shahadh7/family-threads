<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\MemoryItem;

class MemoryItemController extends Controller
{
    public function index() {
        return inertia('MemoryItem/Index');
    }

    public function create()
    {
        return Inertia::render('MemoryItem/Create');
    }

    public function store(Request $request) {
        //
    }

    public function show($id) 
    {
        return Inertia::render('MemoryItem/Edit', [
            'memoryItem' => $id ]
        );    
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }
}
