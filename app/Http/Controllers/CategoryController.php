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
            ->get();

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
            'color' => ['nullable', 'string', 'max:7'],
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
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('status', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Category deleted.');
    }
}
