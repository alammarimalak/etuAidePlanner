@extends('layouts.app')

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
                <label>Color (hex)</label>
                <input type="text" name="color" placeholder="#3B82F6">
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
                        <td>{{ $category->color ?? '—' }}</td>
                        <td class="actions">
                            <form method="POST" action="{{ route('categories.update', $category) }}" class="actions">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="name" value="{{ $category->name }}">
                                <input type="text" name="color" value="{{ $category->color }}" placeholder="#3B82F6">
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
