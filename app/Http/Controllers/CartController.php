<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\DeliveryOptionResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\DeliveryOption;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController  extends Controller
{

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $carts = Cart::get();

        return CartResource::collection($carts);
    }

    public function currentCart(Request $request): CartResource
    {
        $cart = Cart::where('status', '!=', 'paid')
            ->with(['items'])
            ->first();

        if(!$cart){
            $cart =  Cart::create([
                'user_id' => User::first()->id,
                'status' =>  'draft'
            ])->first();
        }
        return new CartResource($cart);
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @return CartResource
     */
    public function show(Request $request, Cart $cart): CartResource
    {
        return new CartResource($cart);
    }

    public function items(Request $request): AnonymousResourceCollection
    {
        $cart = Cart::where('status', '!=', 'paid')
            ->first();

        if(!$cart){
            $cart =  Cart::create([
                'user_id' => User::first()->id,
                'status' =>  'draft'
            ])->first();
        }


        $items = CartItem::with(['product'])
            ->where('cart_id', $cart->id)
            ->get();

        return CartItemResource::collection($items);
    }

    public function addItem(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'product_id' => 'required|string', // exists in product_id
            'quantity' => 'required|int', // exists in product_id
        ]);

        $cart = Cart::where('status', '!=', 'paid')->first();

        $item = $cart->items->where('product_id', $request->get('product_id'))
        ->first();


        if($item)
        {
            $item->update(['quantity' => ($item->quantity + $request->get('quantity'))]);
        }else{
             $cart->items()->create([
                 'cart_id' =>  $cart->id,
                 'product_id' =>  $request->get('product_id'),
                'delivery_option_id' => 1,
                'quantity' => $request->get('quantity')
            ]);

        }


        return CartItemResource::collection($cart->items);
    }

    public function deleteItem(Request $request,Cart $cart, Product $product): AnonymousResourceCollection
    {

        $cart->items()->where('product_id', $product->id)
            ->delete();


        return CartItemResource::collection($cart->items);
    }



    public function updateDeliveryOption(Request $request,Cart $cart, Product $product): AnonymousResourceCollection
    {
        $request->validate([
            'delivery_option_id' => 'required'
        ]);

        $cart->items()->where('product_id', $product->id)
            ->update(['delivery_option_id' => $request->get('delivery_option_id')]);


        return CartItemResource::collection($cart->items);
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function paymentSummary(Request $request, Cart $cart) : JsonResponse
    {
        return response()->json($cart->getPaymentSummary());
    }
}
