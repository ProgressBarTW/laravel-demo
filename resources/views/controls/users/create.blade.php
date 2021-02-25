@extends('controls.layouts.app')

@section('content')
    <div class="container mx-xl px-2 py-6">
        <h1>Create User</h1>
        <form method="POST" action="{{ route('controls.users.store') }}">
            @csrf
            
            <div class="flex items-center mt-4">
                <x-button class="ml-3">
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </div>
@endsection

@section('inline_js')
    @parent
@endsection