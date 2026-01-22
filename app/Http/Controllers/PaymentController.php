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
            'cart_id' => 'required'
        ]);

        $cart = Cart::where('id', $request->get('cart_id'))->first();

        $paymentSummary = $cart->getPaymentSummary();

        try {

            // create cart order
            $order = new CartOrder($paymentSummary);

            // create order request
            $order_request = new OrderCreateRequest($order);
            // resolve paypal client from container
            $paypal_client = resolve(PayPalClient::class);

            // Add negative testing headers if they exist for testing purposes
            $order_request = $this->withNegativeTestingHeaders($request, $order_request);

            // send request to paypal and get response
            $response = $paypal_client->send($order_request);

            // parse response here
            $result = json_decode((string)$response->getBody());

            Transaction::updateOrCreate([
                'cart_id' => $cart->id
            ], [
                'type' => 'withdrawal',
                'user_id' => User::first()->id,
                'amount' => $paymentSummary['totalCostCents'],
                'order_id' => $result->id,
                'paypal_order_processed_date' => Carbon::now(),
                'order_processed_at' => Carbon::now(),
                'provider_type' => 'paypal',
                'provider_data' => json_encode($result),
            ]);

            return $response;
        } catch (Exception $e) {
            $errors = $e->getMessage();
            Log::error($errors);
            throw ValidationException::withMessages(['message' => $errors]);
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
        $transaction = Transaction::where('order_id', $id)->first();
        if (!$transaction) {
            throw ValidationException::withMessages(['message' => 'Cannot capture your payment !']);
        }

        $cart = $transaction->cart;

        try {

            $order_request = new OrderCaptureRequest($id);
            // resolve paypal client from container
            $paypal_client = resolve(PayPalClient::class);

            // Add negative testing headers if they exist for testing purposes
            $order_request = $this->withNegativeTestingHeaders($request, $order_request);
            // send request to paypal
            $response = $paypal_client->send($order_request);
            // parse response here
            $result = json_decode((string)$response->getBody());
            // return response to paypal client sdk. (don't change)
            $transaction->update([
                'paypal_order_processed_date' => now(),
                'order_processed_at' => now(),
                'provider_type' => 'paypal',
                'provider_data' => json_encode($result)
            ]);
            $cart->update(['status' => 'paid']);
            return $response;
        } catch (Exception $e) {
            $errors = $e->getMessage();
            Log::error($errors);
            throw ValidationException::withMessages(['message' => $errors]);
        }

    }
}