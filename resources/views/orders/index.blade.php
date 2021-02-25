@extends('layouts.app')

@section('content')

<div class='px-2 pb-6 grid grid-cols-12'>
    <div class="col-span-12 sm:col-span-12 lg:col-span-12">
        <h1 class='px-6 py-4' style='font-size: 36px; bold'>訂單</h1>
    </div>
    @if (count($orders) == 0)
        <div class="col-span-12 sm:col-span-12 lg:col-span-12 px-6 ">
            <div class='px-6 pb-4' style='font: 28px bold;'>目前沒有訂單</div>
            <div class='px-6'>
                <a href="{{ route('products.index') }}">
                    <x-button type='button'>
                        回到所有商品
                    </x-button>
                </a>
            </div>
        </div>
    @else
        @foreach ($orders as $order)
            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                <div class='px-4'>
                    @include('orders/components/card', ['order' => $order])
                </div>
            </div>
        @endforeach
    @endif
</div>

@endsection

@section('inline_js')
    @parent
@endsection