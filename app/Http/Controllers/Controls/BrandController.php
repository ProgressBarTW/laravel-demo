<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Controls\BrandRequest;
use \Illuminate\Database\QueryException;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('order_index', 'ASC')->get();
        
        return view('controls.brands.index',[
            'brands' => $brands
        ]);
    }

    public function create()
    {
        return view('controls.brands.create');
    }

    public function store(BrandRequest $request)
    {
        try {
            $brand = Brand::create($request->validated());
        } catch (QueryException $e) {
            return redirect()->route('controls.brands.index')->withErrors("Create a brand failed!");
        }

        return redirect()->route('controls.brands.index');
    }

    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);

        return view('controls.brands.edit', [
            'brand' => $brand
        ]);
    }

    public function update($id, BrandRequest $request)
    {
        $brand = Brand::find($id);
        if ($brand->update($request->validated())){
            return redirect()->route('controls.brands.index');
        } else {
            return redirect()->route('controls.brands.index')->withErrors("Update a brand failed!");
        }
    }

    public function destroy($id,Request $request)
    {
        $brand = Brand::find($id);

        if ( $brand->products()->count() !== 0 ){
            return redirect()->route('controls.brands.index')->withErrors("Brand can't be deleted if products relating.");
        } 
        
        $brand->delete();

        return redirect()->route('controls.brands.index');
    }
}
