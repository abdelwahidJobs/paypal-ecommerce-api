<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Http\Resources\DeliveryOptionResource;
use App\Models\Cart;
use App\Models\DeliveryOption;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeliveryOptionController extends Controller
{

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $deliveryOptions = DeliveryOption::all();
        return DeliveryOptionResource::collection($deliveryOptions);
    }



}
