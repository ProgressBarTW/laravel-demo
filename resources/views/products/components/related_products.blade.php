<div class='pb-4'>
    <p class='pb-4' style='font: 24px bold;'>{{ $title }}</p>
    <div class='grid grid-cols-12 gap-6'>
        @foreach($related_products as $related_product)
            <div class="col-span-6 sm:col-span-3">
                @include('products/components/small_card', ['product' => $related_product])
            </div>
        @endforeach
    </div>
</div>