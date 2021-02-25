<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Http\Requests\Controls\ProductRequest;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('controls.products.index', [
            "products" => $products
        ]);
    }

    public function create()
    {
        $brands = Brand::all();
        $subcategories = Subcategory::all();
        $published_statuses = Product::published_statuses;

        return view('controls.products.create', [
            "brands" => $brands,
            "subcategories" => $subcategories,
            "published_statuses" => $published_statuses
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if (isset($validatedData['image'])){
                unset($validatedData["image"]);
        
                $name = time().'_'.$request->file('image')->getClientOriginalName();
                $path = '/storage/'. $request->file('image')->storeAs(
                    'products', 
                    $name,
                    'public'
                );
        
                $validatedData["image"] = $path;
            }
            $product = Product::create($validatedData);
            $this->updateProductOptions($product, $validatedData);

        } catch (QueryException $e) {
            return redirect()->route('controls.products.index')->withErrors("Create a product failed!");
        }

        return redirect()
            ->route('controls.products.index');
    }

    public function edit($id)
    {
        $product = Product::find($id);

        $brands = Brand::all();
        $subcategories = Subcategory::all();
        $published_statuses = Product::published_statuses;

        return view('controls.products.edit', [
            'product' => $product,
            "brands" => $brands,
            "subcategories" => $subcategories,
            "published_statuses" => $published_statuses,
        ]);
    }

    public function update($id, ProductRequest $request)
    {
        $product = Product::find($id);

        $validatedData = $request->validated();

        if (isset($validatedData['image'])){
            unset($validatedData["image"]);

            Storage::disk('public')->delete(
                str_replace(
                    '/storage/',
                    '',
                    $product->image
                )
            );

            $name = time().'_'.$request->file('image')->getClientOriginalName();
            $path = '/storage/'. $request->file('image')->storeAs(
                'products', 
                $name,
                'public'
            );
    
            $validatedData["image"] = $path;
        }

        if ($product->update($validatedData)){

            $this->updateProductOptions($product, $validatedData);
            
            return redirect()->route('controls.products.index');
        } else {
            return redirect()->route('controls.products.index')->withErrors("Update a product failed!");
        }
    }

    public function destroy($id, Request $request)
    {
        $product = Product::find($id);
        
        if (!$product->is_draft()){
            return redirect()->route('controls.products.index')->withErrors("Only a draft product can be deleted.");
        }

        Storage::disk('public')->delete(
            str_replace(
                '/storage/',
                '',
                $product->image
            )
        );

        $product->delete();
        
        return redirect()
            ->route('controls.products.index');
    }

    private function updateProductOptions($product, $validatedData)
    {
        $productOptionsIdsShoudBeRemoved = $product->product_options->map(
            function($product_option){
                return $product_option->id;
        })->toArray();

        if (isset($validatedData['product_options'])){
            $product_options_data = $validatedData['product_options'];
            $newProductOptions = [];

            foreach( $product_options_data as $id => $product_option_data){
                $validator = Validator::make($product_option_data, [
                    'name' => 'required|string|max:255',
                    'price' => 'required|integer|min:0',
                    'enabled' => 'nullable',
                    'image' => 'nullable|image',
                ]);
                if (!$validator->fails()){
                    if (isset($product_option_data['image'])){
                        $name = mt_rand().'_'.$product_option_data['image']->getClientOriginalName();

                        $path = '/storage/'. Storage::disk('public')->putFileAs(
                            'product_options', 
                            $product_option_data['image'], 
                            $name
                        );
                
                        $product_option_data["image"] = $path;
                    }

                    if (isset($product_option_data['enabled'])){
                        $product_option_data['enabled'] = ($product_option_data['enabled'] == 'on');
                    } else {
                        $product_option_data['enabled'] = false;
                    }
                    
                    if (strpos($id, 'new_') !== false) {
                        array_push($newProductOptions, new ProductOption($product_option_data));
                    } else {
                        $currentProductOption = ProductOption::where('id', $id)->where('product_id', $product->id)->first();
                        if ($currentProductOption) {
                            if (isset($product_option_data['image'])){
                                Storage::disk('public')->delete(
                                    str_replace(
                                        '/storage/',
                                        '',
                                        $currentProductOption->image
                                    )
                                );
                            }

                            $currentProductOption->update($product_option_data);
                            $productOptionsIdsShoudBeRemoved = array_diff(
                                $productOptionsIdsShoudBeRemoved, 
                                [ $currentProductOption->id ]
                            );
                        }
                    }
                }
            }
            $product->product_options()->saveMany($newProductOptions);
        }        
        DB::table('product_options')->whereIn('id', $productOptionsIdsShoudBeRemoved)->delete();
    }
}
