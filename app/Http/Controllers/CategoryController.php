<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $user = $this->currentUser();

        $categories = Category::query()
            ->where('user_id', $user->id)
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', [
            'currentUser' => $user,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $data['user_id'] = $user->id;
        $data['is_system'] = false;

        Category::create($data);

        return redirect()->route('categories.index')->with('status', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $category->update($data);

        return redirect()
            ->route('categories.index', $request->only('page'))
            ->with('status', 'Category updated.');
    }

    public function destroy(Request $request, Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('categories.index', $request->only('page'))
            ->with('status', 'Category deleted.');
    }
}
