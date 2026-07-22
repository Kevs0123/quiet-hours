<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('category')->latest()->paginate(10);

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $categories = RoomCategory::orderBy('name')->get();

        return view('rooms.create', compact('categories'));
    }

    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        unset($data['image']);

        $image = $request->file('image');
        $hasValidImage = $image instanceof \Illuminate\Http\UploadedFile
            && $image->isValid()
            && $image->getError() === UPLOAD_ERR_OK
            && $image->getSize() > 0
            && !empty($image->getRealPath());

        if ($hasValidImage) {
            $data['image_path'] = $image->store('rooms', 'public');
        }

        Room::create($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load('category');

        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $categories = RoomCategory::orderBy('name')->get();

        return view('rooms.edit', compact('room', 'categories'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();
        unset($data['image']);

        $image = $request->file('image');
        $hasValidImage = $image instanceof \Illuminate\Http\UploadedFile
            && $image->isValid()
            && $image->getError() === UPLOAD_ERR_OK
            && $image->getSize() > 0
            && !empty($image->getRealPath());

        if ($hasValidImage) {
            // Remove the old photo before storing the new one
            if ($room->image_path && Storage::disk('public')->exists($room->image_path)) {
                Storage::disk('public')->delete($room->image_path);
            }
            $data['image_path'] = $image->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->image_path && Storage::disk('public')->exists($room->image_path)) {
            Storage::disk('public')->delete($room->image_path);
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
