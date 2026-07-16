@extends('layouts.app')
@section('title', 'New Room Category')
@section('content')

<div class="card" style="max-width:560px;">
    <div class="page-header" style="margin-bottom:24px;">
        <h1>New Room Category</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" novalidate>
        @csrf

        <div class="field">
            <label>Category Name <span style="color:var(--danger);">*</span></label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   placeholder="e.g. Deluxe Room"
                   @class(['is-invalid' => $errors->has('name')])>
            @error('name')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label>Description <span style="font-weight:400;font-size:12px;color:var(--muted);">(optional)</span></label>
            <textarea name="description"
                      rows="4"
                      placeholder="Brief description of this room category..."
                      @class(['is-invalid' => $errors->has('description')])>{{ old('description') }}</textarea>
            @error('description')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Save Category
            </button>
        </div>
    </form>
</div>

@endsection
