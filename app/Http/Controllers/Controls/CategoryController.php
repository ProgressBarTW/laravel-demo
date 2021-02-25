<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Controls\CategoryRequest;
use \Illuminate\Database\QueryException;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('order_index', 'ASC')->get();

        return view('controls.categories.index', [
            "categories" => $categories
        ]);
    }

    public function create()
    {
        return view('controls.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
        } catch (QueryException $e) {
            return redirect()->route('controls.categories.index')->withErrors("Create a category failed!");
        }

        return redirect()
            ->route('controls.categories.index');
    }

    public function edit($id, Request $request)
    {
        $category = Category::find($id);

        return view('controls.categories.edit', [
            'category' => $category
        ]);
    }

    public function update($id, CategoryRequest $request)
    {
        $category = Category::find($id);

        if ($category->update($request->validated())){
            return redirect()->route('controls.categories.index');
        } else {
            return redirect()->route('controls.categories.index')->withErrors("Update a category failed!");
        }
    }

    public function destroy($id, Request $request)
    {
        $category = Category::find($id);
        if ( $category->subcategories()->count() !== 0 ){
            return redirect()->route('controls.categories.index')->withErrors("Category can't be deleted if subcategory relating.");
        } 
        
        $category->delete();
        
        return redirect()
            ->route('controls.categories.index');
    }
}
