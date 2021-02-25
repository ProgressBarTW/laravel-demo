@extends('layouts.app')

@section('content')

<h1>Member Log in</h1>
<form 
    method="post" 
    action="{{ route('members.session.store') }}"
>
@csrf
<div>
    <label>
        Email: 
        <p><input type="email" name="email"/></p>
    </label>
</div>
<div>
    <label>
        Password: 
        <p><input type="password" name="password"/></p>
    </label>
</div>
<div>
    <button type="submit">Submit<button>
</div>
</form>
@endsection

@section('inline_js')
    @parent
@endsection