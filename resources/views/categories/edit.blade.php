@extends('layouts.app')
@section('title', 'Edit — ' . $category->name)
@section('content')

<div class="card" style="max-width:560px;">
    <div class="page-header" style="margin-bottom:24px;">
        <h1>Edit Category</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <label>Category Name <span style="color:var(--danger);">*</span></label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $category->name) }}"
                   @class(['is-invalid' => $errors->has('name')])>
            @error('name')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label>Description <span style="font-weight:400;font-size:12px;color:var(--muted);">(optional)</span></label>
            <textarea name="description"
                      rows="4"
                      @class(['is-invalid' => $errors->has('description')])>{{ old('description', $category->description) }}</textarea>
            @error('description')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Update Category
            </button>
        </div>
    </form>
</div>

@endsection
