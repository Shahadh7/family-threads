<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\MemoryItemRequest;
use App\Http\Requests\MemoryItemUpdateRequest;
use App\Models\MemoryItem;
use App\Models\MemoryThread;
use App\Models\TimeCapsule;
use App\Models\Keepsake;
use App\Models\File;
use App\Services\FileUploadService;
use Carbon\Carbon;

class MemoryItemController extends Controller
{
    public function index() 
    {

        $family_id = auth()->user()->family()->first()->id;

        $memoryItems = MemoryItem::where([
            'family_id' => $family_id, 
            'added_by_user_id' => auth()->user()->id,
            'type' => 'MemoryThread'
        ])->with(['file', 'memoryThread'])->get();

        return inertia('MemoryItem/Index',[
            'memoryItems' => $memoryItems,
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
            'public' => $validated['public'],
            'file_id' => null,
            'can_be_viewed_by' => isset($validated['can_be_viewed_by']) ? json_encode($validated['can_be_viewed_by']) : null,
        ]);

        if ($request->hasFile('file')) {
            $fileUrl = $this->uploadFile($request);
            $filename = basename($fileUrl);
            $file = File::create([
                'name' => $request->file('file')->getClientOriginalName(),
                'url' => $fileUrl,
                'path' => $filename,
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

        $memoryItem = MemoryItem::where('id', $id)
            ->first();

        $relationships = [];

        if ($memoryItem->type == 'MemoryThread') {
            $relationships[] = 'memoryThread';
        } elseif ($memoryItem->type == 'Keepsake') {
            $relationships[] = 'keepSake';
        } elseif ($memoryItem->type == 'TimeCapsule') {
            $relationships[] = 'timeCapsule';
        }

        $memoryItem = MemoryItem::where('id', $id)
            ->with($relationships)
            ->first();

        return Inertia::render('MemoryItem/Edit', [
            'memoryItem' => $memoryItem ]
        ); 
    }

    public function update(MemoryItemUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        $memoryItem = MemoryItem::findOrFail($id);

        $typeChanged = $memoryItem->type !== $validated['type'];

        $memoryItem->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'public' => $validated['public'],
            'can_be_viewed_by' => isset($validated['can_be_viewed_by']) ? json_encode($validated['can_be_viewed_by']) : null,
        ]);

        if ($typeChanged) {
            switch ($memoryItem->type) {
                case 'MemoryThread':
                    MemoryThread::where('memory_item_id', $memoryItem->id)->delete();
                    break;
                case 'TimeCapsule':
                    TimeCapsule::where('memory_item_id', $memoryItem->id)->delete();
                    break;
                case 'Keepsake':
                    Keepsake::where('memory_item_id', $memoryItem->id)->delete();
                    break;
            }

            switch ($validated['type']) {
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
        } else {
            switch ($memoryItem->type) {
                case 'MemoryThread':
                    $this->updateMemoryThread($validated, $memoryItem->id);
                    break;
                case 'TimeCapsule':
                    $this->updateTimeCapsule($validated, $memoryItem->id);
                    break;
                case 'Keepsake':
                    $this->updateKeepsake($validated, $memoryItem->id);
                    break;
            }
        }

        if ($request->hasFile('file')) {
            if ($memoryItem->file_id) {
                $file = File::find($memoryItem->file_id);
                if ($file) {
                    $fileUploadService = new FileUploadService();
                    $fileUploadService->deleteFileFromSpaces($file->path);
                    $file->delete();
                }
            }

            $fileUrl = $this->uploadFile($request);
            $filename = basename($fileUrl);
            $file = File::create([
                'name' => $request->file('file')->getClientOriginalName(),
                'url' => $fileUrl,
                'path' => $filename,
                'mime_type' => $request->file('file')->getClientMimeType(),
                'size' => $request->file('file')->getSize(),
            ]);

            $memoryItem->file_id = $file->id;
            $memoryItem->save();
        }

        return redirect()->route('memory-items.index')->with('flash', 'Memory item updated successfully');
    }

    public function destroy($id)
    {
        try {
            $memoryItem = MemoryItem::findOrFail($id);

            if ($memoryItem->file_id) {
                $file = File::find($memoryItem->file_id);
                if ($file) {
                    $fileUploadService = new FileUploadService();
                    $fileUploadService->deleteFileFromSpaces($file->path);
                    $file->delete();
                }
            }

            switch ($memoryItem->type) {
                case 'MemoryThread':
                    MemoryThread::where('memory_item_id', $memoryItem->id)->delete();
                    break;
                case 'TimeCapsule':
                    TimeCapsule::where('memory_item_id', $memoryItem->id)->delete();
                    break;
                case 'Keepsake':
                    Keepsake::where('memory_item_id', $memoryItem->id)->delete();
                    break;
            }

            $memoryItem->delete();


            return response()->json(['message' => 'Memory item deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('memory-items.index')->with('flash', 'Error deleting memory item');
        }
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

    private function updateMemoryThread($item, $memoryItemId) {
        $memoryThread = MemoryThread::where('memory_item_id', $memoryItemId)->first();
        if ($memoryThread) {
            $localDate = Carbon::parse($item['open_date']);
            $utcDate = $localDate->setTimezone('Asia/Colombo');
            
            $memoryThread->location = $item['location'];
            $memoryThread->date = $utcDate;
            $memoryThread->save();
        }
    }
    
    private function updateTimeCapsule($item, $memoryItemId) {
        $timeCapsule = TimeCapsule::where('memory_item_id', $memoryItemId)->first();
        if ($timeCapsule) {
            $localDate = Carbon::parse($item['open_date']);
            $utcDate = $localDate->setTimezone('Asia/Colombo');
            
            $timeCapsule->open_date = $utcDate;
            $timeCapsule->save();
        }
    }
    
    private function updateKeepsake($item, $memoryItemId) {
        $keepsake = Keepsake::where('memory_item_id', $memoryItemId)->first();
        if ($keepsake) {
            $keepsake->given_to_user_id = $item['given_to_user_id'];
            $keepsake->save();
        }
    }
    

    private function uploadFile($request) {
        $fileUploadService = new FileUploadService();
        $file = $fileUploadService->uploadToSpaces($request->file('file')); 
        return $file;
    }

    public function getMemoryItemByType(Request $request, $type) {

        if ($request->wantsJson()) { 
            
            if($type == 'MemoryThread') {
                $memoryItems = MemoryItem::where([
                    'family_id' => auth()->user()->family()->first()->id,
                    'added_by_user_id' => auth()->user()->id,
                    'type' => $type
                ])->with(['file', 'memoryThread'])->get();
            }elseif($type == 'TimeCapsule') {
                $memoryItems = MemoryItem::where([
                    'family_id' => auth()->user()->family()->first()->id,
                    'added_by_user_id' => auth()->user()->id,
                    'type' => $type
                ])->with(['file', 'timeCapsule'])->get();
            }elseif($type == 'Keepsake') {
                $memoryItems = MemoryItem::where([
                    'family_id' => auth()->user()->family()->first()->id,
                    'added_by_user_id' => auth()->user()->id,
                    'type' => $type
                ])->with(['file', 'keepSake'])->get();
            }

            return response()->json($memoryItems);
        }
        
    }
}
