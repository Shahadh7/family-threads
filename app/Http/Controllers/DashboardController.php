<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MemoryItem;

class DashboardController extends Controller
{
    public function index() 
    {

        $family_id = auth()->user()->family()->first()->id;
        $current_user_id = auth()->user()->id;

        $memoryItems = MemoryItem::where(function ($query) use ($family_id, $current_user_id) {
            // Condition 1: Public items viewable by all family members
            $query->where('family_id', $family_id)
                ->where('public', 1);

            // Condition 2: Private items where current user is in can_be_viewed_by
            $query->orWhere(function ($q) use ($family_id, $current_user_id) {
                $q->where('family_id', $family_id)
                ->where('public', 0)
                ->where('can_be_viewed_by', 'like', '%"value":"'.$current_user_id.'"%');
            });

            // Condition 3: Items added by the current user
            $query->orWhere('added_by_user_id', $current_user_id);
        })->with(['file', 'memoryThread', 'timeCapsule', 'keepSake.passedOnUser', 'user'])->orderBy('created_at', 'desc')->get();

        
        return inertia('Dashboard',[
            'memoryItems' => $memoryItems,
            'flash' => session()->get('flash')
        ]);
    }

    public function memoryItemsByTag(Request $request, $tag)
    {
        if ($request->wantsJson()) {
            $familyId = auth()->user()->family()->first()->id;
            $currentUserId = auth()->user()->id;

            $memoryItems = MemoryItem::where(function ($query) use ($familyId, $currentUserId) {
                // Condition 1: Public items viewable by all family members
                $query->where('family_id', $familyId)
                    ->where('public', 1);

                // Condition 2: Private items where the current user is in `can_be_viewed_by`
                $query->orWhere(function ($q) use ($familyId, $currentUserId) {
                    $q->where('family_id', $familyId)
                    ->where('public', 0)
                    ->where('can_be_viewed_by', 'like', '%"value":"'.$currentUserId.'"%');
                });

                // Condition 3: Items added by the current user
                $query->orWhere('added_by_user_id', $currentUserId);
            })
            // Apply type filter only if $tag is not 'AllItems'
            ->when($tag !== 'AllItems', function ($query) use ($tag) {
                $query->where('type', $tag);
            })
            ->with(['file', 'memoryThread', 'timeCapsule', 'keepSake.passedOnUser', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json($memoryItems);
        }
    }

}
