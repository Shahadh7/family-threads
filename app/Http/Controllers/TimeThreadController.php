<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemoryItem;
use Inertia\Inertia;

class TimeThreadController extends Controller
{
    public function index() {

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
                ->whereRaw('JSON_CONTAINS(can_be_viewed_by, \'{"value": "'.$current_user_id.'"}\')');
            });

            // Condition 3: Items added by the current user
            $query->orWhere('added_by_user_id', $current_user_id);
        })
        ->with(['file', 'memoryThread', 'timeCapsule', 'keepSake.passedOnUser', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

        return inertia('TimeThread/Index', [
            'memoryItems' => $memoryItems
        ]);
    }
}
