@extends('controls.layouts.app')

@section('content')
<div class="container mx-auto max-w-screen-sm px-2 py-6">
        <div class="lg:flex lg:items-center lg:justify-between py-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Product
                </h1>
            </div>
        </div>
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <form method="POST" action="{{ route('controls.products.update', $product) }}" enctype="multipart/form-data">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" autocomplete="name" value='{{ $product->name }}' class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="mb-3">
                        <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                        <select id="brand_id" name="brand_id" autocomplete="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach ($brands as $brand)
                            <option 
                                value='{{ $brand->id }}' 
                                {{ ($product->brand_id == $brand->id) ? "selected" : "" }}
                            >{{ $brand->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_id" class="block text-sm font-medium text-gray-700">Subcategory</label>
                        <select id="subcategory_id" name="subcategory_id" autocomplete="subcategory_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach ($subcategories as $subcategory)
                                <option 
                                    value='{{ $subcategory->id }}'
                                    {{ ($product->subcategory_id == $subcategory->id) ? "selected" : "" }}
                                >{{ @$subcategory->category->name }} | {{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $product->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="product_image_preview relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <img class='m-auto' src='{{ asset($product->image) }}'/>
                                        <input id="file-upload" name="image" type="file" >
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>
                    <fieldset class="mb-3">
                        <div>
                            <legend class="text-base font-medium text-gray-900">Published status</legend>
                        </div>
                        <div class="mt-4 space-y-4">
                            @foreach ($published_statuses as $index => $published_status)
                                <div class="flex items-center">
                                    <input id="published_status_{{ $index }}" name="published_status" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value='{{ $index }}' {{  ($index == $product->published_status) ? "checked" : "" }}>
                                    <label for="published_status_{{ $index }}" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ ucfirst($published_status) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
                <hr />
                @include('controls/products/options/itemList', ['product_options' => $product->product_options])

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <x-button class="ml-3">
                        {{ __('Submit') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('inline_js')
    @parent
    <style>
        .product_image_preview img{
            height: 150px;
        }
        #file-upload{
            position: absolute;
            opacity: 0;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <script>
        const product_image_previews = document.querySelectorAll('.product_image_preview')
        imageUploader(product_image_previews)
    </script>
@endsection