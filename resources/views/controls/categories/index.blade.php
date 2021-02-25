@extends('controls.layouts.app')

@section('content')
<div class="container mx-auto max-w-screen-lg px-2 py-6">
    <div class="lg:flex lg:items-center lg:justify-between py-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Category
            </h1>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <a href="{{ route('controls.categories.create') }}">
                <x-button type='button'>
                    Create
                </x-button>
            </a>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Search key
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order index
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Show in list
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subcategory
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Edit / Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody  class="bg-white divide-y divide-gray-200">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->search_key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->order_index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->show_in_list }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            @foreach ($category->subcategoriesInOrder as $subcategory)
                                                <div class="py-1">

                                                
                                                    <a href="{{ route('controls.categories.subcategories.edit', [$category, $subcategory]) }}">
                                                        <x-button type='button' class="{{ ($subcategory->show_in_list ? 'bg-blue-500 hover:bg-blue-700' : 'bg-blue-100 hover:bg-blue-200') }}">
                                                        {{ $subcategory->name }}
                                                        </x-button>
                                                    </a>
                                                </div>
                                            @endforeach
                                            <hr class="py-1"/>
                                            <div>
                                                <a href="{{ route('controls.categories.subcategories.create', $category) }}">
                                                    <x-button type='button' class="bg-green-300 hover:bg-green-500">
                                                        Create
                                                    </x-button>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="py-3">
                                            <a href="{{ route('controls.categories.edit', $category)}}">
                                                <x-button type="button" class="bg-yellow-300 hover:bg-yellow-500">
                                                    Edit
                                                </x-button>
                                            </a>
                                        </p>
                                        <p>
                                            <form method="POST" action="{{ route('controls.categories.destroy', $category) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-button  class="bg-red-500 hover:bg-red-700">
                                                    Delete
                                                </x-button>
                                            </form>
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div>
@endsection

@section('inline_js')
    @parent
@endsection
