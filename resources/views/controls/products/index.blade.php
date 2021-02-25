@extends('controls.layouts.app')

@section('content')
<div class="container mx-auto max-w-screen-lg px-2 py-6">
    <div class="lg:flex lg:items-center lg:justify-between py-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Products
            </h1>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <a href="{{ route('controls.products.create') }}">
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
                                    image
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    brand
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    subcategory
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    published status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    product options
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Edit / Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody  class="bg-white divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><img style='max-width: 120px;' src='{{ asset($product->image) }}' /></td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ @$product->brand->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ @$product->subcategory->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->publish_status_name() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            @foreach ($product->product_options as $product_option)
                                                <div class="py-1">
                                                    <x-button type='button' class="{{ ($product_option->enabled ? 'bg-blue-500 hover:bg-blue-700' : 'bg-blue-100 hover:bg-blue-200') }}">
                                                    {{ $product_option->name }}
                                                    </x-button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="py-3">
                                            <a href="{{ route('controls.products.edit', $product)}}">
                                                <x-button type="button" class="bg-yellow-300 hover:bg-yellow-500">
                                                    Edit
                                                </x-button>
                                            </a>
                                        </p>
                                        <p>
                                            <form method="POST" action="{{ route('controls.products.destroy', $product) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type='submit' class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 bg-red-500 hover:bg-red-700" {{ ($product->is_draft() ? '' : 'disabled') }}>
                                                    Delete
                                                </button>
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