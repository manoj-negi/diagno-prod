<?php

namespace App\Http\Controllers\Api\Paypal;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionInventory;
use App\Models\User;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Validator;

class PaymentController extends Controller
{
    private $gateway;


    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function pay(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|numeric|exists:subscriptions,id',
            'amount' => 'required|numeric',
            'doctor_id' => 'required|numeric|exists:users,id,is_doctor,1'
        ]);

        if ($validator->fails())
            return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
            
        try {

            $subscription_inventory = new SubscriptionInventory;
            $subscription_inventory->subscription_id = $request->subscription_id;
            $subscription_inventory->user_id = $request->doctor_id;
            $subscription_inventory->save();

                $subscription_day = Subscription::where('id', $subscription_inventory->subscription_id)->first();

                $subscription_inventory->transection_id = '123';
                $subscription_inventory->payer_id = '123';
                $subscription_inventory->payer_email = 'qweq@sdfkl.com';
                $subscription_inventory->amount = $request->amount;
                $subscription_inventory->currency = env('PAYPAL_CURRENCY');
                $subscription_inventory->pay_status = 'success';
                $subscription_inventory->status = 1;
                $subscription_inventory->start_date = date('Y-m-d H:i:s');
                $subscription_inventory->end_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . "+ " . $subscription_day->days . " days"));
                $subscription_inventory->save();

                User::where('id', $subscription_inventory->user_id)->update(['subscription_inventory_id' => $subscription_inventory->id]);

                $this->response = [
                    'email' => $subscription_inventory->payer_email,
                    'amount' => $subscription_inventory->amount,
                    'transection_id' => $subscription_inventory->transection_id,
                    'status' => $subscription_inventory->pay_status
                ];
                return \ResponseBuilder::success(trans('messages.PAY_SUCCESS'), $this->success, $this->response);

            $response = $this->gateway->purchase([
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('/api/success/' . $subscription_inventory->id),
                'cancelUrl' => url('/api/pay-cancel')
            ])->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function success(Request $request, $id)
    {
        if ($request->paymentId && $request->PayerID) {
            $transaction = $this->gateway->completePurchase([
                'payer_id' => $request->PayerID,
                'transactionReference' => $request->paymentId
            ]);

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $data = $response->getData();

                $sub = SubscriptionInventory::where('id', $id)->first();

                $subscription_day = Subscription::where('id', $sub->subscription_id)->first();

                $sub->transection_id = $data['id'];
                $sub->payer_id = $data['payer']['payer_info']['payer_id'];
                $sub->payer_email = $data['payer']['payer_info']['email'];
                $sub->amount = $data['transactions'][0]['amount']['total'];
                $sub->currency = env('PAYPAL_CURRENCY');
                $sub->pay_status = $data['state'];
                $sub->status = $data['state'] == 'approved' ? 1 : 0;
                $sub->start_date = date('Y-m-d H:i:s');
                $sub->end_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . "+ " . $subscription_day->days . " days"));
                $sub->save();

                User::where('id', $sub->user_id)->update(['subscription_inventory_id' => $sub->id]);

                $this->response = [
                    'email' => $sub->payer_email,
                    'amount' => $sub->amount,
                    'transection_id' => $sub->transection_id,
                    'status' => $sub->pay_status
                ];
                return \ResponseBuilder::success(trans('messages.PAY_SUCCESS'), $this->success, $this->response);
            } else {
                return \ResponseBuilder::fail($response->getMessage(), $this->badRequest);
            }

        } else {
            return \ResponseBuilder::fail('Payment declined!!', $this->badRequest);
        }
    }

    public function paycancel(Request $request)
    {
        return \ResponseBuilder::fail(trans('messages.PAY_CANCEL'), $this->badRequest);
    }


}
