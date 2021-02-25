@extends('layouts.app')

@section('content')

<div class='px-2 py-6 grid grid-cols-12'>
    <div class="col-span-12 sm:col-span-12 lg:col-span-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900 pb-4">
            購物車
        </h3>
        <div>
            <div class='bg-white shadow overflow-hidden sm:rounded-sm px-4 py-4'>
                <form action="{{ route('cart.updateCartItems') }}" method='post'>
                @csrf
                @method('patch')

                @if (count($cartItems) == 0)
                    <div class='pb-4' style='font: 28px bold;'>購物車是空的</div>
                    <div>
                        <a href="{{ route('products.index') }}">
                            <x-button type='button'>
                                回到所有商品
                            </x-button>
                        </a>
                    </div>
                @else 
                    @foreach($cartItems as $cartItem)

                        @php
                            $product_option = $cartItem['productOption'];
                            $quantity = $cartItem['quantity'];
                        @endphp
                        
                        <div class='grid grid-cols-12'>
                            <div class='col-span-5 sm:col-span-2 lg:col-span-2 '>
                                <img src="{{ $product_option->image }}" />
                            </div>
                            <div class='col-span-7 sm:col-span-3 lg:col-span-4'>
                                <p class='ml-4'>{{ @$product_option->product->name }} - {{ $product_option->name }}</p>
                            </div>
                            <div class='col-span-4 sm:col-span-2 lg:col-span-2'>
                                <div style='text-align: center;' class="py-6">
                                    <label class="block text-lg font-medium text-gray-700">$ {{ $product_option->price }}</label>
                                </div>
                            </div>
                            <div class='col-span-6 sm:col-span-3 lg:col-span-3'>
                                <div class="py-5">
                                    <div class="stepper mt-1 relative rounded-md shadow-sm" style='max-width: 200px;'>
                                        <x-button 
                                            type='button'
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center"
                                            data-value='1'
                                        >
                                            +
                                        </x-button>
                                        <input 
                                            type="text" 
                                            name="product_options[{{ $product_option->id }}][quantity]" 
                                            id="product_option_{{ $product_option->id }}_quantity" 
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                            value="{{ $quantity }}"
                                            style='text-align: center;'
                                        >
                                        <x-button 
                                            type='button'
                                            class="absolute inset-y-0 right-0 flex items-center"
                                            data-value='-1'
                                        >
                                            -
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                            <div class='col-span-2 sm:col-span-1 lg:col-span-1'>
                                <div style='text-align: center;' class="py-6">
                                   <x-button 
                                        type='button' 
                                        class="cartDeleteBtn bg-red-500 hover:bg-red-700" 
                                        data-id="{{ $product_option->id }}">
                                        X
                                    </x-button>
                                </div>
                            </div>
                            <div class='col-span-12'>
                                <div  class='py-3'>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class='py-4'>
                        <x-button>
                            更新購物車
                        </x-button>
                    </div>
                @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-12 lg:col-span-4 px-8 py-10">
        <div class='bg-white shadow overflow-hidden sm:rounded-sm sm:px-4 py-4'>
            <p class='pb-6 text-center' style='font: 36px bold;'>價錢</p>
            <p class='pb-6 text-center' style='font: 28px bold;'>$ {{ $endPrice }}</p>
            <div class='pb-6 text-center'>
                <a href="{{ route('cart.checkout')}}">
                    <x-button  
                        type='button' 
                        class="bg-red-500 hover:bg-red-700" 
                    >
                        結帳
                    </x-button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('inline_js')
    @parent
    <script>
        initCartDeleteButton("{{ route('cart.deleteCartItem') }}")

        var steppers = document.querySelectorAll('.stepper')
        for(var index = 0; index < steppers.length ; index ++){
            var stepper = steppers[index]
            var input = stepper.querySelector('input')
            input.addEventListener('keypress', onlyNumber)
            input.addEventListener('blur', restoreValueIfEmpty)
            var btns = stepper.querySelectorAll('button')
            for(var btnIndex = 0; btnIndex < btns.length ; btnIndex ++){
                var btn = btns[btnIndex]
                btn.addEventListener('click', function(e){
                    var targetBtn = e.target
                    var input = targetBtn.closest('.stepper').querySelector('input')
                    input.value = Math.max(
                            1, 
                            parseInt(input.value) + parseInt(targetBtn.getAttribute('data-value'))
                            )
                })
            }
        }

        function onlyNumber(event){
            var theEvent = event || window.event;
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\./;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }

        function restoreValueIfEmpty(event){
            var input = event.target
            if (!input.value){
                input.value = input.defaultValue
            }
        }
    </script>
@endsection