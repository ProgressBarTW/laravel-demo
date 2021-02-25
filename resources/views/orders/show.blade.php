@extends('layouts.app')

@section('content')

<div class="mx-auto my-8 py-8 px-8 bg-white shadow overflow-hidden sm:rounded-lg" style='max-width: 1024px;'>
    <h1 class="text-md leading-6 font-medium text-gray-900 py-4" style='font: 28px bold;'>
        訂單編號: {{ $order->order_number }}
    </h1>
    <p class='py-3'>訂單狀態: {{ $order->orderStatusStr() }}</p>
    <p class='py-3'>建立於: {{ $order->created_at }}</p>
    <p class='py-3'>最後更新於: {{ $order->updated_at }}</p>
    <p class='py-3'>金額:{{ $order->amount }}</p>
    <p class='py-3'>寄送地址{{ $order->address }}</p>
    <div class='py-3'>
        <hr />
    </div>
    @foreach($order->orderItems as $orderItem)
        <div class='grid grid-cols-12'>
            <div class='col-span-3 '>
                <img src="{{ @$orderItem->productOption->image }}" />
            </div>
            <div class='col-span-4'>
                <div  class="ml-4 py-6">
                    <label class="block text-lg font-medium text-gray-700">{{ $orderItem->name}}</label>
                </div>
            </div>
            <div class='col-span-3'>
                <div style='text-align: center;' class="py-6">
                    <label class="block text-lg font-medium text-gray-700">$ {{ $orderItem->price }}</label>
                </div>
            </div>
            <div class='col-span-2'>
                <div style='text-align: center;' class="py-6">
                    <label class="block text-lg font-medium text-gray-700">{{ $orderItem->quantity }}</label>
                </div>
            </div>
            <div class='col-span-12'>
                <div  class='py-3'>
                    <hr/>
                </div>
                <div class='py-4'>
                    <a href="{{ route('orders.index') }}">
                        <x-button type='button'>
                            回到所有訂單
                        </x-button>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection

@section('inline_js')
    @parent
@endsection