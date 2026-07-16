<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomCategoryRequest;
use App\Http\Requests\UpdateRoomCategoryRequest;
use App\Models\RoomCategory;
use Illuminate\Support\Str;

class RoomCategoryController extends Controller
{
    public function index()
    {
        $categories = RoomCategory::withCount('rooms')->latest()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreRoomCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        RoomCategory::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Room category created successfully.');
    }

    public function show(RoomCategory $category)
    {
        $category->load('rooms');

        return view('categories.show', compact('category'));
    }

    public function edit(RoomCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateRoomCategoryRequest $request, RoomCategory $category)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Room category updated successfully.');
    }

    public function destroy(RoomCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Room category deleted successfully.');
    }
}
