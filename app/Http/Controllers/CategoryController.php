<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create_category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
            'category_code' => 'required',
            'status'        => 'required',
        ], [
            'category_name.required' => 'Category name is required',
            'category_code.required' => 'Category code is required',
            'status.required'        => 'Please Select Status',
        ]);

        Category::create([
            'user_id'       => Auth::id(),
            'category_name' => $request->category_name,
            'category_code' => $request->category_code,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $categories = Category::where('user_id', Auth::id())
            ->findOrFail($category->id);

        return view('show_category', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('user_id', Auth::id())
            ->findOrFail($category->id);

        return view('edit_category', compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required',
            'category_code' => 'required',
            'status'        => 'required',
        ], [
            'category_name.required' => 'Category name is required',
            'category_code.required' => 'Category code is required',
            'status.required'        => 'Please Select Status',
        ]);

        $category = Category::where('user_id', Auth::id())
            ->findOrFail($category->id);

        $category->update([
            'category_name' => $request->category_name,
            'category_code' => $request->category_code,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category = Category::where('user_id', Auth::id())
            ->findOrFail($category->id);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}