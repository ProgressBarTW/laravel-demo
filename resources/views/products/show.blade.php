@extends('layouts.app')

@section('content')

<div class='px-2 py-6 grid grid-cols-12'>
    <div class="col-span-12 sm:col-span-12 lg:col-span-2">
        @include('products/components/sidebar')
    </div>
    <div class="col-span-12 sm:col-span-12 lg:col-span-10">
        <h1 class='pb-4' style='font-size: 36px; bold'>{{ $product->name }}</h1>
        <p class='pb-4'>品牌: {{ @$product->brand->name }} | 類別: {{ @$product->subcategory->category->name }} > {{ @$product->subcategory->name }}</p>
        <div class='px-2 pb-6 grid grid-cols-12 gap-6'>
            <div class="col-span-12 sm:col-span-6 lg:col-span-8">
                <div>
                    <img id='large_image'>
                </div>
                <div class='py-6 grid grid-cols-12'>
                    <div class='col-span-4'>
                        <img class='small_image pr-2 pb-2' src="{{ asset($product->image)}}">
                    </div>
                    @foreach($product->product_options as $product_option)
                        <div class='col-span-4'>
                            <img id='small_image_{{ $product_option->id }}' class='small_image pr-2 pb-2' src="{{ asset($product_option->image)}}">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                <form action="{{ route('cart.addToCart')}}" method='post'>
                    @csrf
                    <p class='pb-4'>選項:</p>
                    <ul>
                        @foreach($product->product_options as $product_option)
                        <li class='pb-6'>
                            <div>
                                <label 
                                    for="product_option_{{ $product_option->id }}" 
                                    class="block text-sm font-medium text-gray-700"
                                    style='font-size: 20px; bold'
                                >   {{ $product_option->name }}: ${{ $product_option->price }}
                                </label>
                                <select 
                                    data-target-id='{{ $product_option->id }}'
                                    id="product_option_{{ $product_option->id }}" 
                                    name="product_option_{{ $product_option->id }}" 
                                    class="product_option_select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    @for ($i = 0; $i <= 10; $i++)
                                        <option value='{{ $i }}'>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        <li>
                        @endforeach
                    </ul>
                    <div>
                        <button id='add_to_cart' type='submit' class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 bg-red-500 hover:bg-red-700" disabled>
                            加入購物車
                        </button>
                    </div>
                </form>
                <div class='py-4'>
                    <p class='py-4' style='font: 16px bold;'>商品敘述:</p>
                    <p style='color: rgba(0, 0, 0, 0.8);'>{{ $product->description }}</p>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-12 lg:col-span-12">
                @include('products/components/related_products', [
                    'title' => '同廠牌商品',
                    'related_products' => $related_products_by_brand
                ])
                
                @include('products/components/related_products', [
                    'title' => '同類別商品',
                    'related_products' => $related_products_by_subcategory
                ])
            <div>
        </div>
    </div>
</div>

@endsection

@section('inline_js')
    @parent
    <script>
        var small_images = document.querySelectorAll('.small_image')
        var large_image = document.querySelector('#large_image')

        for(var index = 0; index < small_images.length; index++){
            var small_image = small_images[index]
            small_image.addEventListener('mouseover', function(e){
                large_image.src = e.target.src
            })

            small_image.addEventListener('click', function(e){
                large_image.src = e.target.src
            })
        }

        if (small_images.length > 0){
            large_image.src = small_images[0].src
        }

        var product_options_select = document.querySelectorAll('select.product_option_select')
        for(var index = 0; index < product_options_select.length; index++){
            var select = product_options_select[index]
            select.addEventListener('click', function(e){
                var id = e.target.getAttribute('data-target-id')
                var small_image = document.querySelector('#small_image_' + id)
                small_image.click()
            })

            var add_to_cart_btn = document.querySelector('#add_to_cart')
            select.addEventListener('change', function(e){
                add_to_cart_btn.disabled = true
                for(var selectIndex = 0; selectIndex < product_options_select.length; selectIndex++){
                    var targetSelect = product_options_select[selectIndex]
                    if (targetSelect.value > 0) {
                        add_to_cart_btn.disabled = false
                        break
                    }
                }
            })
        }
    </script>
@endsection