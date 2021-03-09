<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccessEmail;
use App\Jobs\SendOrderEmail;

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

        // SendOrderEmail::dispatch()->onQueue('one');

        dispatch(function () {
            Log::error("Closures three");
        })->onQueue('three');
        
        dispatch(function () {
            Log::error("Closures two");
        })->onQueue('two');

        dispatch(function () {
            Log::error("Closures one");
        })->onQueue('one');
       

        
        // Mail::to("demo.progressbar.tw@gmail.com")->queue(
        //     new OrderSuccessEmail($order)
        // );

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function success(Request $request){
        return view('orders.success', [
        ]);
    }

    public function not_finished(Request $request){
        return view('orders.not_finished', [
        ]);
    }

    public function mpg_return(Request $request){
        $result = $this->validateMPGCallbackValues($request);
        if ( is_array($result)){     
            if (
                $result["PaymentType"] == 'CREDIT' &&
                $result["RespondCode"] == '00' &&
                isset($result["PayTime"])
            ){
                $merchantOrderNo = $result["MerchantOrderNo"];
                $order = Order::where('order_number', $merchantOrderNo)->first();
                if ($order){
                    $order->setToPaid();
                    Auth::guard('web')->login($order->user);
                    return redirect()->route('orders.success');
                }
            } else if (
                $result["PaymentType"] == 'WEBATM' &&
                isset($result["PayTime"])
            ){
                $merchantOrderNo = $result["MerchantOrderNo"];
                $order = Order::where('order_number', $merchantOrderNo)->first();
                if ($order){
                    $order->setToPaid();
                    Auth::guard('web')->login($order->user);
                    return redirect()->route('orders.success');
                }
            }
        }
        return redirect('/')->withErrors($result);
    }

    public function notify(Request $request){
        $result = $this->validateMPGCallbackValues($request);
        if ( is_array($result)){           
            Log::debug("notify: ".json_encode($result));

            $merchantOrderNo = $result["MerchantOrderNo"];
            $order = Order::where('order_number', $merchantOrderNo)->first();
            if ($order){
                if (
                    in_array($result["PaymentType"], [
                        'VACC',
                        'CVS',
                        'BARCODE',
                    ]) &&
                    isset($result["PayTime"])
                ){
                    $order->setToPaid();
                }
            }
            return;
        }

        Log::debug("notify: ". $result);
        return;
    }

    public function pendingPaymentType(Request $request){
        $result = $this->validateMPGCallbackValues($request);
        if ( is_array($result)){           
            if (
                in_array($result["PaymentType"], [
                    'VACC',
                    'CVS',
                    'BARCODE',
                ])
            ){
                $merchantOrderNo = $result["MerchantOrderNo"];
                $order = Order::where('order_number', $merchantOrderNo)->first();
                if ($order){
                    $order->setToPending();
                    Auth::guard('web')->login($order->user);
                    // var_dump($result);
                    return redirect()->route('orders.not_finished');
                }
            }
        }
        return redirect('/')->withErrors($result);
    }

    
    private function validateMPGCallbackValues(Request $request){
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
                return $tradeInfoAry["Result"];
            }

        return "MPG 錯誤 $status";
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
