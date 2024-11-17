<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FamilyTree;

class FamilyTreeController extends Controller
{
    public function index()
    {
        $family = auth()->user()->family()->first()->id;
        $tree_data = FamilyTree::where('family_id', $family)->select('tree_data')->first();

        return Inertia::render('FamilyTree/Index', [
            'treeData' => $tree_data
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'tree_data' => 'required'
        ]);

        $family = $request->user()->family()->first();
        
        FamilyTree::create([
            'tree_data' => $request->tree_data,
            'family_id' => $family->id
        ]);

        return redirect()->route('family-tree.index');
    }

    public function update(Request $request) {
        $request->validate([
            'tree_data' => 'required'
        ]);

        $family = $request->user()->family()->first();

        FamilyTree::where('family_id', $family->id)->update([
            'tree_data' => json_encode($request->tree_data)
        ]);

        return redirect()->route('family-tree.index');
    }
}
