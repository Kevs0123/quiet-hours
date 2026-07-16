@extends('layouts.app')
@section('title', 'Edit — ' . $room->name)
@section('content')

<div class="card" style="max-width:600px;">
    <div class="page-header" style="margin-bottom:24px;">
        <h1>Edit Room</h1>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>

    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <label>Room Category <span style="color:var(--danger);">*</span></label>
            <select name="room_category_id" @class(['is-invalid' => $errors->has('room_category_id')])>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(old('room_category_id', $room->room_category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('room_category_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label>Room Name <span style="color:var(--danger);">*</span></label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $room->name) }}"
                   @class(['is-invalid' => $errors->has('name')])>
            @error('name')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label>Description</label>
            <textarea name="description"
                      rows="3"
                      @class(['is-invalid' => $errors->has('description')])>{{ old('description', $room->description) }}</textarea>
            @error('description')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label>Room Photo <span style="font-weight:400;font-size:12px;color:var(--muted);">(optional, JPG/PNG/WEBP, max 2MB)</span></label>
            @if ($room->image_path)
                <div style="margin-bottom:10px;">
                    <img src="{{ $room->image_url ?? 'https://via.placeholder.com/600x420?text=No+Photo' }}"
                        onerror="this.onerror=null;this.src='https://via.placeholder.com/600x420?text=No+Photo'"
                        alt="{{ $room->name }}"
                        style="max-width:220px;width:100%;border-radius:8px;border:1px solid var(--cream2);">
                    <p style="font-size:12px;margin-top:4px;">Current photo — upload a new one below to replace it.</p>
                </div>
            @endif
            <div class="file-drop-zone" id="roomImageDropZone">
                <input type="file" name="image" id="roomImageInput" accept=".jpg,.jpeg,.png,.webp">
                <div class="file-drop-icon">🖼️</div>
                <div class="file-drop-text">
                    <strong>Click to choose a photo</strong> or drag and drop here
                </div>
                <div id="room-image-name-display" style="margin-top:10px;font-size:13px;color:var(--success);font-weight:500;"></div>
            </div>
            @error('image')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="field">
                <label>Price per Night (₱) <span style="color:var(--danger);">*</span></label>
                <input type="number"
                       step="0.01"
                       min="0"
                       name="price_per_night"
                       value="{{ old('price_per_night', $room->price_per_night) }}"
                       @class(['is-invalid' => $errors->has('price_per_night')])>
                @error('price_per_night')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div class="field">
                <label>Capacity <span style="color:var(--danger);">*</span></label>
                <input type="number"
                       min="1"
                       max="20"
                       name="capacity"
                       value="{{ old('capacity', $room->capacity) }}"
                       @class(['is-invalid' => $errors->has('capacity')])>
                @error('capacity')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="field">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;text-transform:none;letter-spacing:0;font-weight:500;">
                <input type="checkbox"
                       name="is_available"
                       value="1"
                       @checked(old('is_available', $room->is_available))
                       style="width:18px;height:18px;flex-shrink:0;">
                Room is available for booking
            </label>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Update Room
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const zone    = document.getElementById('roomImageDropZone');
    const input   = document.getElementById('roomImageInput');
    const display = document.getElementById('room-image-name-display');
    if (!zone || !input) return;

    function showFile(file) {
        if (!file) { display.textContent = ''; return; }
        display.style.color = '';
        display.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
    }

    zone.addEventListener('click', function (e) {
        if (e.target !== input) input.click();
    });

    input.addEventListener('change', function () {
        showFile(input.files[0]);
    });

    ['dragenter', 'dragover'].forEach(evt => zone.addEventListener(evt, function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.classList.add('dragover');
    }));

    ['dragleave', 'dragend'].forEach(evt => zone.addEventListener(evt, function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.classList.remove('dragover');
    }));

    zone.addEventListener('drop', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            showFile(files[0]);
        }
    });
});
</script>

@endsection
