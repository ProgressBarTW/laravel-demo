<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request){
        $current_user = $request->user();

        $orders = $current_user->orders()->orderBy('id', 'desc')->get();

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function show($order_number, Request $request){

        $current_user = $request->user();

        $order = $current_user->orders()->where('order_number', $order_number)->first();
        
        if (!$order){
            return redirect()->route('orders.index')->withErrors('沒有這個訂單');
        }

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function mpg_return(Request $request){

        $status = $request->input('Status');
        $merchantID = $request->input('MerchantID');
        $version = $request->input('Version');
        $tradeInfo = $request->input('TradeInfo');
        $tradeSha = $request->input('TradeSha');

        $hashKey = env('MPG_HashKey', '');
        $hashIV = env('MPG_HashIV', '');
        $tradeShaForTest = strtoupper(hash("sha256", "HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));


        
        if (    $status == 'SUCCESS' && 
                $merchantID == env('MPG_MerchantID') &&
                $version == env('MPG_Version') &&
                $tradeSha == $tradeShaForTest
            ){

                $tradeInfoJSONString = $this->create_aes_decrypt($tradeInfo, $hashKey, $hashIV); 
                $tradeInfoAry = json_decode($tradeInfoJSONString, true);

                if (
                    $tradeInfoAry["Result"]["PaymentType"] == 'CREDIT' &&
                    $tradeInfoAry["Result"]["RespondCode"] == '00' 
                ){
                    $merchantOrderNo = $tradeInfoAry["Result"]["MerchantOrderNo"];
                    $order = Order::where('order_number', $merchantOrderNo)->first();
                    if ($order){
                        $order->setToPaid();
                        return redirect()->route('orders.success');
                    }
                } else if (
                    $tradeInfoAry["Result"]["PaymentType"] == 'WEBATM'
                ){

                }
        }

        // return redirect('/')->withErrors("MPG 錯誤 $status");
    }

    public function success(Request $request){
        return view('orders.success', [
        ]);
    }

    public function notify(Request $request){

    }

    public function pendingPaymentType(Request $request){
        $status = $request->input('Status');
        $merchantID = $request->input('MerchantID');
        $version = $request->input('Version');
        $tradeInfo = $request->input('TradeInfo');
        $tradeSha = $request->input('TradeSha');

        $hashKey = env('MPG_HashKey', '');
        $hashIV = env('MPG_HashIV', '');
        $tradeShaForTest = strtoupper(hash("sha256", "HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));
        
        if (    $status == 'SUCCESS' && 
                $merchantID == env('MPG_MerchantID') &&
                $version == env('MPG_Version') &&
                $tradeSha == $tradeShaForTest
            ){

                $tradeInfoJSONString = $this->create_aes_decrypt($tradeInfo, $hashKey, $hashIV); 
                $tradeInfoAry = json_decode($tradeInfoJSONString, true);
                var_dump( $tradeInfoAry);
                if (
                    $tradeInfoAry["Result"]["PaymentType"] == 'ATM'
                ){
                    $merchantOrderNo = $tradeInfoAry["Result"]["MerchantOrderNo"];
                    $order = Order::where('order_number', $merchantOrderNo)->first();
                    if ($order){
                        $order->setToPending();
                        return redirect()->route('orders.success');
                    }
                }
        }
    }

    private function create_aes_decrypt($parameter = "", $key = "", $iv = "") {
        return $this->strippadding(
                openssl_decrypt(
                    hex2bin($parameter),
                    'AES-256-CBC', 
                    $key, 
                    OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, 
                    $iv
                )
            );
    }

    private function strippadding($string) {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
}
