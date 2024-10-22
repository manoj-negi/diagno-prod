<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Example: you can dynamically get these values from request or order
        $amount = 1000; // Amount in paise, e.g. 1000 = ₹10.00
        $order = $api->order->create([
            'receipt' => 'order_rcptid_11',
            'amount' => $amount,
            'currency' => 'INR'
        ]);

        $order_id = $order['id'];

        return view('razorpay.paymentPage', ['order_id' => $order_id, 'amount' => $amount]);
    }

    public function paymentCallback(Request $request)
    {
        $input = $request->all();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $attributes = [
                'razorpay_order_id' => $input['razorpay_order_id'],
                'razorpay_payment_id' => $input['razorpay_payment_id'],
                'razorpay_signature' => $input['razorpay_signature']
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Payment successful, process accordingly
            // Save the payment details, update the order status, etc.

            return redirect()->back()->with('success', 'Payment successful.');
        } catch (Exception $e) {
            // Payment failed
            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }
    }
}
