<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Http\Requests\Controls\SubcategoryRequest;

class SubcategoryController extends Controller
{
    public function create($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        return view('controls.subcategories.create', [
            'category' => $category
        ]);
    }

    public function store( $categoryId, SubcategoryRequest $request)
    {
        $category = Category::find($categoryId);

        $subcategory = $category->subcategories()->create($request->validated());

        return redirect()->route('controls.categories.index');
    }

    public function edit($categoryId, $subcategoryId) 
    {
        $category = Category::find($categoryId);
        $subcategory = $category->subcategories()->find($subcategoryId);

        return view('controls.subcategories.edit', [
            'category' => $category,
            'subcategory' => $subcategory
        ]);
    }

    public function update($categoryId, $subcategoryId, SubcategoryRequest $request)
    {
        $subcategory = Category::find($categoryId)->subcategories()->find($subcategoryId);

        if ($subcategory->update($request->validated())){
            return redirect()->route('controls.categories.index');
        } else {
            return redirect()->route('controls.categories.index')->withErrors("Update a subcategory failed!");
        }
    }

    public function destroy($categoryId, $subcategoryId, Request $request)
    {
        $subcategory = Category::find($categoryId)->subcategories()->find($subcategoryId);
        if ( $subcategory->products()->count() !== 0 ){
            return redirect()->route('controls.categories.index')->withErrors("Subcategory can't be deleted if product relating.");
        } 
        
        $subcategory->delete();
        
        return redirect()
            ->route('controls.categories.index');
    }
}
