@extends('layouts.app')

@section('content')
<h1>正在重導向到付款頁面...</h1>
<form method='post'
id='submitForm'
action='{{ $actionUrl }}'>
    <input type='hidden' name='MerchantID' value='{{ $merchantID }}'><br/>
    <input type='hidden' name='TradeInfo' value='{{ $tradeInfo }}'><br/>
    <input type='hidden' name='TradeSha' value='{{ $tradeSha }}'><br/>
    <input type='hidden' name='Version' value='{{ $version }}'><br/>
</form>
@endsection

@section('inline_js')
    @parent
    <script>
        setTimeout(function(){
            document.querySelector('#submitForm').submit();
        }, 500)
    </script>
@endsection