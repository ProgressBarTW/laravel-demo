@extends('layouts.app')

@section('content')

<div class='px-2 pb-6 grid grid-cols-12'>
    <div class="col-span-12 sm:col-span-12 lg:col-span-12">
        <h1 class='px-6 py-4' style='font-size: 36px; bold'>Products</h1>
    </div>
    <div class="col-span-12 sm:col-span-12 lg:col-span-2">
        @include('products/components/sidebar')
    </div>
    <div class="col-span-12 sm:col-span-12 lg:col-span-10">
        <div class='px-2 pb-6 grid grid-cols-12 gap-6'>
        @foreach ($products as $product)
            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                @include('products/components/card', ['product' => $product])
            </div>
        @endforeach
        </div>
    </div>
</div>

@endsection

@section('inline_js')
    @parent
@endsection