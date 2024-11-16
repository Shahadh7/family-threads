<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FamilyTreeController extends Controller
{
    public function index()
    {
        return Inertia::render('FamilyTree/Index');
    }

}
