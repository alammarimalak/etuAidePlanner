@extends('layouts.app')

@push('styles')
    <style>
        .category-color-cell {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .category-color-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid rgba(16, 25, 53, 0.14);
            flex-shrink: 0;
        }

        .category-color-input {
            width: 56px;
            height: 42px;
            padding: 6px;
            border-radius: 12px;
            cursor: pointer;
        }

        body.theme-dark .category-color-dot {
            border-color: rgba(238, 242, 255, 0.2);
        }
    </style>
@endpush

@section('content')
    <h1>Categories</h1>

    <div class="card">
        <form method="POST" action="{{ route('categories.store') }}" class="form-grid">
            @csrf
            <div>
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div>
                <label>Color</label>
                <input type="color" name="color" class="category-color-input" value="{{ old('color', '#3B82F6') }}">
            </div>
            <button type="submit">Add Category</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Color</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if ($category->color)
                                <span class="category-color-cell">
                                    <span class="category-color-dot" style="background-color: {{ $category->color }};"></span>
                                    <span>{{ $category->color }}</span>
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="actions">
                            <form method="POST" action="{{ route('categories.update', $category) }}" class="actions">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="name" value="{{ $category->name }}">
                                <input type="color" name="color" class="category-color-input" value="{{ $category->color ?: '#3B82F6' }}">
                                <button type="submit">Update</button>
                            </form>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="secondary">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="muted">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

