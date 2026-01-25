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
        $cart = Cart::with('items')
            ->where('status', '!=', 'paid')
            ->firstOr(function () {
                return Cart::create([
                    'user_id' =>  User::first()->id,
                    'status' => 'draft',
                ]);
            });

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

        $user = User::first(); // Ideally, use $request->user() if authentication is available

        $cart = Cart::with('items.product')
            ->where('status', '!=', 'paid')
            ->firstOr(function () use ($user) {
                return Cart::create([
                    'user_id' => $user->id,
                    'status' => 'draft',
                ]);
            });

        return CartItemResource::collection($cart->items);
    }

    public function addItem(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,uuid',
            'quantity' => 'required|int|min:1',
        ]);

        $user = User::first();

        // Get or create the cart
        $cart = Cart::with('items.product')
            ->where('status', '!=', 'paid')
            ->firstOr(function () use ($user) {
                return Cart::create([
                    'user_id' => $user->id,
                    'status' => 'draft',
                ]);
            });

        $product = Product::where('uuid', $request->product_id)->firstOrFail();

        // Add or increment item quantity
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'delivery_option_id' => 1,
                'quantity' => $request->quantity,
            ]);
        }

        // Reload items to include the newly added/updated item
        $cart->load('items.product');

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
            'delivery_option_id' => 'required|string|exists:delivery_options,id',
        ]);

        $cart->items()->where('product_id', $product->id)
            ->update(['delivery_option_id' => $request->delivery_option_id]);

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
