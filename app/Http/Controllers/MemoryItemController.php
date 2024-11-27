<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\MemoryItemRequest;
use App\Models\MemoryItem;
use App\Models\MemoryThread;
use App\Models\TimeCapsule;
use App\Models\Keepsake;
use App\Models\File;
use App\Services\FileUploadService;
use Carbon\Carbon;

class MemoryItemController extends Controller
{
    public function index() {
        return inertia('MemoryItem/Index',[
            'flash' => session()->get('flash')
        ]);
    }

    public function create()
    {
        return Inertia::render('MemoryItem/Create');
    }

    public function store(MemoryItemRequest $request) {

        $validated = $request->validated();

        $memoryItem = MemoryItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'added_by_user_id' => auth()->user()->id,
            'family_id' => $request->user()->family()->first()->id,
            'file_id' => null
        ]);

        if ($request->hasFile('file')) {
            $fileUrl = $this->uploadFile($request);
            $file = File::create([
                'name' => $request->file('file')->getClientOriginalName(),
                'url' => $fileUrl,
                'path' => $fileUrl,
                'mime_type' => $request->file('file')->getClientMimeType(),
                'size' => $request->file('file')->getSize(),
            ]);

            $memoryItem->file_id = $file->id;
            $memoryItem->save();
        }

        switch ($request->type) {
            case 'MemoryThread':
                $this->createMemoryThreads($validated, $memoryItem->id);
                break;
            case 'TimeCapsule':
                $this->createTimeCapsule($validated, $memoryItem->id);
                break;
            case 'Keepsake':
                $this->createKeepsake($validated, $memoryItem->id);
                break;
        }


        return redirect()->route('memory-items.index')->with('flash', 'Memory item created successfully');
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

    public function destroy($id) {
        //
    }


    private function createMemoryThreads($item, $memoryItemId) {

        $localDate = Carbon::parse($item['open_date']); // Parse the input date
        $utcDate = $localDate->setTimezone('Asia/Colombo');
        
        $memoryThread = MemoryThread::create([
            'location' => $item['location'],
            'date' => $utcDate,
            'memory_item_id' => $memoryItemId
        ]);

        $memoryThread->save();

        return $memoryThread;
    }

    private function createTimeCapsule($item, $memoryItemId) {

        $localDate = Carbon::parse($item['open_date']); // Parse the input date
        $utcDate = $localDate->setTimezone('Asia/Colombo');

        $timeCapsule = TimeCapsule::create([
            'memory_item_id' => $memoryItemId,
            'open_date' => $utcDate
        ]);

        return $timeCapsule;
    }

    private function createKeepsake($item, $memoryItemId) {
        $keepsake = Keepsake::create([
            'memory_item_id' => $memoryItemId,
            'given_to_user_id' => $item['given_to_user_id']
        ]);

        return $keepsake;
    }

    private function uploadFile($request) {
        $fileUploadService = new FileUploadService();
        $file = $fileUploadService->uploadToSpaces($request->file('file')); 
        return $file;
    }
}
