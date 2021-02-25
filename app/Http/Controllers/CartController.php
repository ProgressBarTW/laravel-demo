<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOption;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function index(Request $request){

        $cartItems = $this->getCartItemsArray($request);
        $endPrice = $this->getEndPrice($request);

        return view('cart.index', [
            "cartItems" => $cartItems,
            "endPrice" => $endPrice
        ]);
    }

    public function checkout(Request $request){

        $order = $this->createOrderByCart($request);
        if (empty($order)){
            return redirect()->route('cart.index')->withErrors('購物車是空的');
        }
        
        $hashKey = env('MPG_HashKey', '');
        $hashIV = env('MPG_HashIV', '');
        $expireDaysToPlus = env('MPG_ExpireDate', '');
        $tradeInfoAry = [
            'MerchantID' => env('MPG_MerchantID', ''),
            'Version' => env('MPG_Version', ''),
            'RespondType' => env('MPG_RespondType', ''),
            'TimeStamp' => time(),
            'LangType' => env('MPG_LangType', ''),
            'MerchantOrderNo' => $order->order_number,
            'Amt' => $order->amount,
            'ItemDesc' => '一堆水果',
            'TradeLimit' => env('MPG_TradeLimit', ''),
            'ExpireDate' => date('Ymd', strtotime(date(''). "+ $expireDaysToPlus days")),
            'Email' => $order->user->email,
            'EmailModify' => env('MPG_EmailModify', ''),
            'LoginType' => env('MPG_LoginType', ''),
            'OrderComment' => '收到請冷藏',
            'CREDIT' => env('MPG_CREDIT', ''),
            'InstFlag' => env('MPG_InstFlag', ''),
            'WEBATM' => env('MPG_WEBATM', ''),
            'VACC' => env('MPG_VACC', ''),
            'CVS' => env('MPG_CVS', ''),
            'BARCODE' => env('MPG_BARCODE', ''),
            //'CVSCOM' => '3',
            'ReturnURL' => env('APP_URL') . env('MPG_ReturnURL', ''),
            'NotifyURL' => env('APP_URL') . env('MPG_NotifyURL', ''),
            'CustomerURL' => env('APP_URL') . env('MPG_CustomerURL', ''),
            'ClientBackURL' => env('APP_URL') . env('MPG_ClientBackURL', ''),
        ];

        $tradeInfo = $this->create_mpg_aes_encrypt($tradeInfoAry, $hashKey, $hashIV); 
        $tradeSha = strtoupper(hash("sha256", "HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));
        $actionUrl = 'https://ccore.newebpay.com/MPG/mpg_gateway';

        return view('cart.checkout', [
            'actionUrl' => $actionUrl,
            'merchantID' => $tradeInfoAry['MerchantID'],
            'tradeInfo' => $tradeInfo,
            'tradeSha' => $tradeSha,
            'version' => $tradeInfoAry['Version'],
        ]);
    }

    public function addToCart(Request $request) {
        
        $current_user = $request->user();

        if ($current_user) {
            $this->addToDBCart($request);
        } else {
            $this->addToCookieCart($request);
        }

        if(empty($request->header('referer'))) {
            return redirect()->route('cart.index');
        } else {
            return redirect()->back();
        }
    }

    public function deleteCartItem(Request $request){
        $current_user = $request->user();

        if ($current_user) {
            if ($this->deleteFromDBCart($request)) {
                return response('success');
            } else {
                return response('failed');
            }
        } else {
            if ($this->deleteFromCookieCart($request)) {
                return response('success');
            } else {
                return response('failed');
            }
        }
    }

    public function updateCartItems(Request $request){
        $current_user = $request->user();

        if ($current_user) {
            $this->updateToDBCart($request);
        } else {
            $this->updateToCookieCart($request);
        }

        if(empty($request->header('referer'))) {
            return redirect()->route('cart.index');
        } else {
            return redirect()->back();
        }
    }

    private function updateToDBCart(Request $request){
        if ($request->has('product_options')){
            $product_options = $request->input('product_options');

            if (is_array($product_options)){
                $cart = $request->user()->getPurchaseCartOrCreate();

                $cartItemIdsToDelete = [];
                $inputProductOptionIds = array_keys($product_options);
                foreach ($cart->cartItems as $cartItem){
                    if(!in_array($cartItem->product_option_id, $inputProductOptionIds)){
                        array_push( $cartItemIdsToDelete, $cartItem->id);
                    }
                }
                DB::table('cart_items')->whereIn('id', $cartItemIdsToDelete)->delete();

                foreach($product_options as $productOptionId => $value){
                    if (isset($value['quantity'])){
                        $quantity = intval($value['quantity']);
                        if ($quantity > 0){
                            $product_option = ProductOption::findIfEnabled($productOptionId);
                            if ($product_option) {
                                $cartItem = $cart->cartItems()->where('product_option_id', $productOptionId)->first();
                                if ($cartItem){
                                    $cartItem->quantity = $quantity;
                                    $cartItem->save();
                                } else {
                                    $cart->cartItems()->save(
                                        new CartItem([
                                            'product_option_id' => $productOptionId,
                                            'quantity' => $quantity
                                        ])
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    } 

    private function updateToCookieCart(Request $request){
        if ($request->has('product_options')){
            $product_options = $request->input('product_options');

            if (is_array($product_options)){
                $cookieCart = [];
                foreach($product_options as $productOptionId => $value){
                    if (isset($value['quantity'])){
                        $quantity = intval($value['quantity']);
                        if ($quantity > 0){
                            $product_option = ProductOption::findIfEnabled($productOptionId);
                            if ($product_option) {
                                $cookieCart[$productOptionId] = $quantity;
                            }
                        }
                    }
                }
                $this->saveCookieCart($cookieCart);
            }
        }
    }

    private function deleteFromDBCart(Request $request){
        if ($request->has('product_option_id')){
            $productOptionId = intval($request->input('product_option_id'));
            $cart = $request->user()->getPurchaseCartOrCreate();
            $cartItem = $cart->cartItems()->where('product_option_id', $productOptionId)->first();

            if ( $cartItem ){
                $cartItem->delete();
                return true;
            }
        }
        return false;
    }

    private function deleteFromCookieCart(Request $request){
        if ($request->has('product_option_id')){
            $productOptionId = intval($request->input('product_option_id'));
            $cookieCart = $this->getCartFromCookie();

            if ( isset($cookieCart[$productOptionId]) ){
                unset($cookieCart[$productOptionId]);
                $this->saveCookieCart($cookieCart);
                return true;
            }
        }
        return false;
    }

    private function addToDBCart(Request $request){
        $cart = $request->user()->getPurchaseCartOrCreate();
        
        foreach($request->input() as $key => $value){
            if (preg_match('/^product_option_[0-9]+$/', $key)){
                $quantity = intval($value);
                $productOptionId = intval(str_replace('product_option_', '', $key));
                if ($quantity && $productOptionId){
                    $product_option = ProductOption::findIfEnabled($productOptionId);
                    if ($product_option){
                        $cartItem = $cart->cartItems()->where('product_option_id', $productOptionId)->first();
                        if ($cartItem){
                            $cartItem->quantity += $quantity;
                            $cartItem->save();
                        } else {
                            $cart->cartItems()->save(
                                new CartItem([
                                    'product_option_id' => $productOptionId,
                                    'quantity' => $quantity
                                ])
                            );
                        }
                    }
                }
            }
        }
    }

    private function addToCookieCart(Request $request){
        $cookieCart = $this->getCartFromCookie();

        foreach($request->input() as $key => $value){
            if (preg_match('/^product_option_[0-9]+$/', $key)){
                $quantity = intval($value);
                $productOptionId = intval(str_replace('product_option_', '', $key));
                if ($quantity && $productOptionId){
                    $product_option = ProductOption::findIfEnabled($productOptionId);
                    if ($product_option){
                        if (isset($cookieCart[$productOptionId])){
                            $cookieCart[$productOptionId] += $quantity;
                        } else {
                            $cookieCart[$productOptionId] = $quantity;
                        }
                    }
                }
            }
        }

        $this->saveCookieCart($cookieCart);
    }

    private function getCartFromCookie(){
        $jsonCart = Cookie::get('cart');
        return (!is_null($jsonCart)) ? json_decode($jsonCart, true) : [];
    }

    private function saveCookieCart($cookieCart){
        $cartToJson = empty($cookieCart) ? "{}" : json_encode($cookieCart, true);
        Cookie::queue(
            Cookie::make('cart', $cartToJson, 60 * 24 * 7, null, null, false, false)
        );
    }

    private function getCartItemsArray(Request $request) {
        $cartItemsAry = [];

        $current_user = $request->user();
        if ($current_user){
            $this->syncCookieCartToDBCart($current_user);

            $cartItems = $current_user->getPurchaseCartOrCreate()->cartItems;

            foreach($cartItems as $cartItem){
                $productOption = ProductOption::findIfEnabled($cartItem->product_option_id);
                if ($productOption && $cartItem->quantity > 0) {
                    array_push($cartItemsAry, [
                        "productOption" => $productOption,
                        "quantity" => $cartItem->quantity,
                    ]);
                } else {
                   $cartItem->delete();
                }
            }
        } else {
            $cookieCart = $this->getCartFromCookie();

            foreach($cookieCart as $productOptionId => $quantity){
                $productOption = ProductOption::findIfEnabled($productOptionId);
                if ($productOption) {
                    array_push($cartItemsAry, [
                        "productOption" => $productOption,
                        "quantity" => $quantity,
                    ]);
                } else {
                    unset($cookieCart[$productOptionId]);
                }
            }
            $this->saveCookieCart($cookieCart);
        }

        return $cartItemsAry;
    }

    private function syncCookieCartToDBCart(User $user){
        if ($user) {
            $cookieCart = $this->getCartFromCookie();
            $cart = $user->getPurchaseCartOrCreate();
            foreach($cookieCart as $productOptionId => $quantity){
                $productOption = ProductOption::findIfEnabled($productOptionId);
                if ($productOption) {
                    $cartItem = $cart->cartItems()->where('product_option_id', $productOptionId)->first();
                    if ($cartItem){
                        $cartItem->quantity += $quantity;
                        $cartItem->save();
                    } else {
                        $cart->cartItems()->save(
                            new CartItem([
                                'product_option_id' => $productOptionId,
                                'quantity' => $quantity
                            ])
                        );
                    }
                }
            }
            $this->saveCookieCart([]);
        }
    }

    private function getEndPrice(Request $request){
        return array_reduce(
            $this->getCartItemsArray($request),
            function ($currentValue, $cartItemObj){
                $productOption = $cartItemObj["productOption"];
                $quantity = $cartItemObj["quantity"];
                return $currentValue + intval($quantity) * $productOption->price;
            },
            0
        );
    }

    private function createOrderByCart(Request $request){

        $current_user = $request->user();
        $cart = $current_user->getPurchaseCartOrCreate();
        if ($cart->cartItems()->count() == 0) {
            return null;
        }
        
        $amount = $this->getEndPrice($request);
        $order = null;
        DB::transaction(function () use ($current_user, $cart, $amount, &$order){
            $order = Order::create([
                'amount' => $amount,
                'address' => 'testing....',
                'user_id' => $current_user->id,
            ]);

            $order->orderItems()->saveMany($cart->cartItems->map(function($cartItem){
                return new OrderItem([
                    'name' => $cartItem->productOption->fullName(),
                    'price' => $cartItem->productOption->price,
                    'quantity' => $cartItem->quantity,
                    'product_option_id' => $cartItem->product_option_id
                ]);
            }));

            $cart->cartItems()->delete();
        });

        return $order;
    }

    private function create_mpg_aes_encrypt ($parameter = "" , $key = "", $iv = "") {
        $return_str = '';
        if (!empty($parameter)) {
            //將參數經過 URL ENCODED QUERY STRING
            $return_str = http_build_query($parameter);
        }
        return trim(bin2hex(openssl_encrypt($this->addpadding($return_str), 'aes-256-cbc', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv)));
    }

    private function addpadding($string, $blocksize = 32) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }
}
