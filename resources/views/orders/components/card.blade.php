<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-2 py-2">
        <h3 class="text-md leading-6 font-medium text-gray-900 py-4">
            訂單編號: {{ $order->order_number }}
        </h3>
        <hr />
        <p class="py-1">建立於: {{ $order->created_at}}</p>
        <p class="py-1">訂單狀態: {{ $order->orderStatusStr() }}</p>
    </div>
    <div class="px-2 py-1 border-t border-gray-200">
        <a href="{{ route('orders.show', $order)}}">
            <x-button type='button'>
                顯示細節
            </x-button>
        </a>
    </div>
</div>
