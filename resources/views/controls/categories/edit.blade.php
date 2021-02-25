@extends('controls.layouts.app')

@section('content')
<div class="container mx-auto max-w-screen-sm px-2 py-6">
    <div class="lg:flex lg:items-center lg:justify-between py-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Edit Category
            </h1>
        </div>
    </div>
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <form method="POST" action="{{ route('controls.categories.update', $category) }}">
            @csrf
            @method('PATCH')
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div class="mb-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" autocomplete="name" value='{{  $category->name }}' class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-3">
                    <label for="search_key" class="block text-sm font-medium text-gray-700">Search key</label>
                    <input type="text" name="search_key" id="search_key" autocomplete="search_key" value='{{  $category->search_key }}' class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-3">
                    <label for="order_index" class="block text-sm font-medium text-gray-700">Index</label>
                    <input type="number" name="order_index" id="order_index" autocomplete="order_index" min='0' max='9999' value='{{ $category->order_index ?? 3000}}' class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <fieldset class="mb-3">
                    <div>
                        <legend class="text-base font-medium text-gray-900">Show in list</legend>
                    </div>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-center">
                        <input id="push_everything" name="show_in_list" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value='1' {{ ($category->show_in_list ? "checked" : "") }} >
                        <label for="push_everything" class="ml-3 block text-sm font-medium text-gray-700">
                        Show
                        </label>
                        </div>
                        <div class="flex items-center">
                        <input id="push_email" name="show_in_list" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value='0' {{ ($category->show_in_list ? "" : "checked") }} >
                        <label for="push_email" class="ml-3 block text-sm font-medium text-gray-700">
                        Hide
                        </label>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <x-button class="ml-3">
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('inline_js')
    @parent
@endsection