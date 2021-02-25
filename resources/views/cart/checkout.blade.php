@extends('layouts.app')

@section('content')

<form method='post'
action='{{ $actionUrl }}'>
    <input type='text' name='MerchantID' value='{{ $merchantID }}'><br/>
    <input type='text' name='TradeInfo' value='{{ $tradeInfo }}'><br/>
    <input type='text' name='TradeSha' value='{{ $tradeSha }}'><br/>
    <input type='text' name='Version' value='{{ $version }}'><br/>
    <input type='submit' value='Submit'>
</form>
@endsection

@section('inline_js')
    @parent
@endsection