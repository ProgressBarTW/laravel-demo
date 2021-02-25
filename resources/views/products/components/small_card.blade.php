<a href="{{ route('products.show', $product)}}">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div>
            <img src='{{ asset($product->image) }}' style='height: 120px; width: 100%; object-fit: cover;'>
        </div>
        <div class="px-2 py-2">
            <h3 class="text-md leading-6 font-medium text-gray-900">
                {{ $product->name }}
            </h3>
        </div>
        <div class="px-2 py-1 border-t border-gray-200">
            <span>價錢</span>
            @if ( count($product->prices()) == 0)
                <span>沒有標價</span>
            @elseif ( count($product->prices()) == 1)
                <span>${{ $product->prices()[0] }}</span>
            @else
                <span>${{ $product->prices()->min() }} ~ ${{ $product->prices()->max() }}</span>
            @endif
        </div>
    </div>
</a>