<div class='px-2 pb-6 grid grid-cols-8 gap-2'>
    <div class="col-span-8 sm:col-span-4 lg:col-span-8">
        <div class='pb-3 pl-3' >
            <p class='pb-3' style='font: 18px bold;'>品牌:</p>
            <ul>
                @foreach(\App\Models\Brand::getShowInListData() as $brand)
                    <li class='pb-2'>
                        {{ $brand->name }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-span-8 sm:col-span-4 lg:col-span-8">
        <div class='pb-3 pl-3'>
            <p class='pb-3' style='font: 18px bold;'>類別:</p>
            <ul>
                @foreach(\App\Models\Category::getShowInListData() as $category)
                    <li class='pb-2'>
                        <p>{{ $category->name }}</p>
                        <ul class='pt-2 pl-4'>
                            @foreach($category->subcategoriesToShow as $subcategory)
                                <li class='pb-2'>
                                    {{ $subcategory->name }}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>