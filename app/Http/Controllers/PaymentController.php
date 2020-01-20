<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Paystack;

class PaymentController extends Controller
{
  /**
    * Redirect the User to Paystack Payment Page
    * @return Url
    */
   public function redirectToGateway(Request $request)
   {
     // validate incase of form manipulation
      // start payment in db
      Payment::create([
        'status' => 'pending',
        // 'user_id' => auth()->user()->id,
        'amount' => $totalCommissionFloated,
      ]);

       // return Paystack::getAuthorizationUrl()->redirectNow();
   }

   /**
    * Obtain Paystack payment information
    * @return void
    */
   public function handleGatewayCallback()
   {
       $paymentDetails = Paystack::getPaymentData();

       if (!$paymentDetails['status']) {
         // code...
         return response()->json(['status' => false, 'message' => $paymentDetails['message']]);
       }

       if ($paymentDetails['status']) {
         // code...
         $payment = Payment::where('status', 'pending')->where('branch_id', auth()->user()->id)->latest();

         $payment->update([
           'status' => $paymentDetails['status'],
           'reference' => $paymentDetails['data']['reference'],
           'authorization_code' => $paymentDetails['data']['authorization']['authorization_code'],
           'currency_code' => $paymentDetails['data']['currency'],
           'payed_at' => NOW(),//$paymentDetails['data']['paidAt'],
         ]);

       }

       return redirect('/payment/status')->with([
         'status' => $paymentDetails['status'],
         'message' => $paymentDetails['message'],
       ]);

       return response()->json(['status' => true, 'message' => $paymentDetails['message']]);
       // dd($paymentDetails);
       // Now you have the payment details,
       // you can store the authorization_code in your db to allow for recurrent subscriptions
       // you can then redirect or do whatever you want
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'amount'        => 'required|numeric',
      ]);
      $user             = $request->user();
      $amount           = $request->amount;
      // $auth_url         = Paystack::getAuthorizationUrl();

      return $user->payments()->create([
        'amount' => $amount,
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
      $request->validate([
        // 'amount'          => 'required',
        'payment_details'    => 'required|array'
      ]);
      $user             = $request->user();
      $paymentDetails   = $request->payment_details;

      // check for callbacked payment
      if($payment->status !== 'pending')
        return ['status' => false, 'message' => trans('msg.payment.responded'), 'payment' => $payment];

      if (!$paymentDetails['status']) {
        $payment->update([
          'status' => $paymentDetails['status'],
        ]);
      }

      if ($paymentDetails['status']) {
        $payment->update([
          'status'              => $paymentDetails['data']['status'],
          'message'             => $paymentDetails['message'],
          'reference'           => $paymentDetails['data']['reference'],
          'authorization_code'  => $paymentDetails['data']['authorization']['authorization_code'],
          'currency_code'       => $paymentDetails['data']['currency'],
          'payed_at'            => now(),//$paymentDetails['data']['paidAt'],
        ]);
        $user->deposit($paymentDetails['data']['amount']);
      }

      return ['status' => true, 'payment' => $payment];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
