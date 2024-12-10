<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Family;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function getAllUsers(Request $request) {

        if ($request->wantsJson()) {
            $currentUserFamilyId = auth()->user()->family_id;
            $users = User::where('family_id', $currentUserFamilyId)
                ->whereNot('id', auth()->user()->id)->get();
            
            return response()->json($users);
        }
        
    }

    public function getCurrentUser(Request $request) { 

        if ($request->wantsJson()) {
            $currentUser = auth()->user();
            return response()->json($currentUser);
        }
        
    }

    public function inviteUser(Request $request) {

        if ($request->wantsJson()) {

            $family_code = Family::where('id', auth()->user()->family_id)->get('family_code')->first();
    
            if ($family_code) {
                $userEmail = $request->input('email');
    
                if ($userEmail) {
                    Mail::to($userEmail)->send(new InvitationMail($family_code));
    
                    return response()->json([
                        'message' => 'Invitation sent successfully.',
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Invalid email address.',
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Invalid family code.',
                ], 404);
            }
        }

    }
}
