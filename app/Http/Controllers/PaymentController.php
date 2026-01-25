<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PayPal\Checkout\Http\OrderCaptureRequest;
use PayPal\Checkout\Http\OrderCreateRequest;
use PayPal\Checkout\Http\PayPalClient;
use PayPal\Checkout\Http\PaypalRequest;

class PaymentController extends Controller
{

      /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    public function createOrder(Request $request): mixed
    {
        $request->validate([
            'cart_id' => 'required|string|exists:carts,id',
        ]);

        $cart = Cart::where('id', $request->cart_id)->firstOrFail();
        $paymentSummary = $cart->getPaymentSummary();

        try {
            // Create cart order
            $order = new CartOrder($paymentSummary);

            // Prepare order request
            $orderRequest = new OrderCreateRequest($order);

            // Resolve PayPal client
            $paypalClient = resolve(PayPalClient::class);

            // Apply negative testing headers if provided
            $orderRequest = $this->withNegativeTestingHeaders($request, $orderRequest);

            // Send request to PayPal
            $response = $paypalClient->send($orderRequest);
            $result = json_decode((string) $response->getBody());

            // Save transaction
            Transaction::updateOrCreate(
                ['cart_id' => $cart->id],
                [
                    'type' => 'withdrawal',
                    'user_id' => User::first()->id,
                    'amount' => $paymentSummary['totalCostCents'],
                    'order_id' => $result->id,
                    'paypal_order_processed_date' => now(),
                    'order_processed_at' => now(),
                    'provider_type' => 'paypal',
                    'provider_data' => json_encode($result),
                ]
            );

            return $response;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }


    private function withNegativeTestingHeaders(Request $http_request, PaypalRequest $paypal_request): PaypalRequest
    {
        if (config('paypal.settings.mode') == 'sandbox' &&
            $http_request->hasHeader('PayPal-Mock-Response')
        ) {
            $paypal_request = $paypal_request->withHeader(
                'PayPal-Mock-Response',
                $http_request->header('PayPal-Mock-Response')
            );
        }

        return $paypal_request;
    }


    /**
     * @param Request $request
     * @param string $id
     * @return mixed
     * @throws ValidationException
     */
    public function capture(Request $request, string $id): mixed
    {

        $transaction = Transaction::where('order_id', $request->id)->firstOrFail();
        if(!$transaction)
        {
            throw ValidationException::withMessages([
                'message' => 'Payment could not be captured !',
            ]);
        }
        $cart = $transaction->cart;

        try {
            // Prepare capture request
            $orderRequest = new OrderCaptureRequest($id);

            // Resolve PayPal client
            $paypalClient = resolve(PayPalClient::class);

            // Apply negative testing headers if provided
            $orderRequest = $this->withNegativeTestingHeaders($request, $orderRequest);

            // Send request to PayPal
            $response = $paypalClient->send($orderRequest);
            $result = json_decode((string) $response->getBody());

            // Update transaction and cart status
            $transaction->update([
                'paypal_order_processed_date' => now(),
                'order_processed_at' => now(),
                'provider_type' => 'paypal',
                'provider_data' => json_encode($result),
            ]);

            $cart->update(['status' => 'paid']);

            return $response;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
}