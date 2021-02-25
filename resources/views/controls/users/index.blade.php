@extends('controls.layouts.app')

@section('content')
<div class="container mx-xl px-2 py-6">
    <h1>Index User</h1>
    <a href="{{ route('controls.users.create') }}">
        <x-button type='button'>
            Create
        </x-button>
    </a>
<div>
@endsection

@section('inline_js')
    @parent
@endsection