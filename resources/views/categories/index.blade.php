@extends('layouts.app')

@push('styles')
    <style>
        .category-create-form {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: end;
        }

        .category-create-field {
            flex: 1 1 220px;
            min-width: 0;
        }

        .category-create-field label,
        .category-edit-field label {
            display: block;
            margin-bottom: 6px;
        }

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

        .category-edit-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .category-edit-field {
            flex: 1 1 180px;
            min-width: 0;
        }

        .category-edit-field.color {
            flex: 0 0 auto;
        }

        .category-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .category-icon-action {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.84);
            color: var(--ink);
            box-shadow: none;
            padding: 0;
        }

        .category-icon-action svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .category-icon-action:hover {
            transform: translateY(-1px);
        }

        .category-icon-action.delete {
            color: #b91c1c;
        }

        .category-table th.action-column,
        .category-table td.action-column {
            width: 84px;
        }

        body.theme-dark .category-color-dot {
            border-color: rgba(238, 242, 255, 0.2);
        }

        body.theme-dark .category-icon-action {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 119, 255, 0.18);
            color: #eef2ff;
        }

        body.theme-dark .category-icon-action.delete {
            color: #fca5a5;
        }

        @media (max-width: 760px) {
            .category-create-form,
            .category-edit-form {
                align-items: stretch;
            }
        }
    </style>
@endpush

@section('content')
    <h1>Categories</h1>

    <div class="card">
        <form method="POST" action="{{ route('categories.store') }}" class="category-create-form">
            @csrf
            <div class="category-create-field">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="category-create-field" style="flex: 0 0 auto;">
                <label>Color</label>
                <input type="color" name="color" class="category-color-input" value="{{ old('color', '#3B82F6') }}">
            </div>
            <button type="submit">Add Category</button>
        </form>
    </div>

    <div class="card">
        <table class="category-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Edit</th>
                    <th class="action-column">Action</th>
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
                        <td>
                            <form method="POST" action="{{ route('categories.update', $category) }}" class="category-edit-form">
                                @csrf
                                @method('PATCH')
                                <div class="category-edit-field">
                                    <input type="text" name="name" value="{{ $category->name }}" aria-label="Edit name for {{ $category->name }}">
                                </div>
                                <div class="category-edit-field color">
                                    <input type="color" name="color" class="category-color-input" value="{{ $category->color ?: '#3B82F6' }}" aria-label="Edit color for {{ $category->name }}">
                                </div>
                                <button type="submit" class="category-icon-action" aria-label="Update {{ $category->name }}" title="Update">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M9.55 18.2 4.8 13.46a.75.75 0 1 1 1.06-1.06l3.69 3.68 8.59-8.58a.75.75 0 0 1 1.06 1.06L10.61 18.2a.75.75 0 0 1-1.06 0Z"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class="action-column">
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')" class="category-actions">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="category-icon-action delete" aria-label="Delete {{ $category->name }}" title="Delete">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h1l.8 11.14A3 3 0 0 0 9.79 21h4.42a3 3 0 0 0 2.99-2.86L18 7h1a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9Zm5 2h-4v0h4v0Zm-5.2 2h6.4l-.78 10.99a1 1 0 0 1-1 .95H9.58a1 1 0 0 1-1-.95L7.8 7Zm1.95 2.25a1 1 0 0 1 1 1v5.5a1 1 0 1 1-2 0v-5.5a1 1 0 0 1 1-1Zm4.5 0a1 1 0 0 1 1 1v5.5a1 1 0 1 1-2 0v-5.5a1 1 0 0 1 1-1Z"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

