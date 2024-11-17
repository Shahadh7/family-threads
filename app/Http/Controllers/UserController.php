<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUsers(Request $request) {

        if ($request->wantsJson()) {
            $currentUserFamilyId = auth()->user()->family_id;
            $users = User::where('family_id', $currentUserFamilyId)->get();
            return response()->json($users);
        }
        
    }

    public function getCurrentUser(Request $request) { 

        if ($request->wantsJson()) {
            $currentUser = auth()->user();
            return response()->json($currentUser);
        }
        
    }
}
