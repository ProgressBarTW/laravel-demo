<h5 class='px-2 py-4'>Options</h5>
<div class='px-2 py-4'>
    <button 
        type='button' 
        class="add_button inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
    >
        +
    </button>
</div>

<div class='product_options_container'>
</div>

<div style='display:none;' class='product_option_item px-2 pb-6 grid grid-cols-8 gap-6'>
    <div class="col-span-8 sm:col-span-8 lg:col-span-4">
        <label class="block text-sm font-medium text-gray-700">
            Name
            <input
                type="text"
                attr-name='name'
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                disabled
            >
        </label>
    </div>
    <div class="col-span-8 sm:col-span-5 lg:col-span-3">
        <label class="block text-sm font-medium text-gray-700">
            Price
            <input
                type="number"
                attr-name='price'
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                disabled
            >
        </label>
    </div>
    <div class="col-span-8 sm:col-span-3 lg:col-span-1">
        <label class="block text-sm font-medium text-gray-700">
            Enabled
            <input 
                type="checkbox" 
                attr-name='enabled'
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                disabled
            >
        </label>
    </div>
    <div class="product_option_preview_image col-span-6 sm:col-span-6 lg:col-span-7">
        <img style='width: 300px;' />
        <input 
            type="file" 
            attr-name='image'
            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            disabled
        >
    </div>
    <div class="col-span-2 sm:col-span-2 lg:col-span-1">
        <button 
            type='button' 
            data-target='.product_option_item'
            class="inline-flex items-center px-2 py-1 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
        >
            X
        </button>
    </div>
</div>
<script>
    var add_button = document.querySelector('.add_button');
    var container = document.querySelector('.product_options_container');
    var item = document.querySelector('.product_option_item');
    initProductOptionsTable(
        add_button, 
        container, 
        item,
        '{!! json_encode($product_options, true) !!}'
    )
</script>