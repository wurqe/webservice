<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Paystack;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'search'        => '',
        'orderBy'       => ['regex:(amount|message|reference|currency_code|status)', 'nullable'],
        'pageSize'      => 'nullable|numeric',
      ]);

      $user           = $request->user();
      $search         = $request->search;
      $orderBy        = $request->orderBy;
      $pageSize       = $request->pageSize;

      $payments = $user->payments();

      if ($search) $payments->where('status', 'LIKE', '%'.$search.'%')
      ->orWhere('amount', 'LIKE', '%'.$search.'%')->orWhere('reference', 'LIKE', '%'.$search.'%')
      ->orWhere('message', 'LIKE', '%'.$search.'%')->orWhere('currency_code', 'LIKE', '%'.$search.'%');

      return $payments->orderBy($orderBy ?? 'id')->paginate($pageSize ?? 15);
    }

    public function transactionHistory(Request $request)
    {
      $request->validate([
        'search'        => ['nullable', 'regex:(deposit|status|amount)'],
        'orderBy'       => ['regex:(deposit|status|amount)', 'nullable'],
        'pageSize'      => 'nullable|numeric',
      ]);

      $user = $request->user();
      $search         = $request->search;
      $orderBy        = $request->orderBy;
      $pageSize       = $request->pageSize;

      $transactions = $user->transactions();

      if ($search) $transactions->where('deposit', 'LIKE', '%'.$search.'%')
      ->orWhere('amount', 'LIKE', '%'.$search.'%')->orWhere('status', 'LIKE', '%'.$search.'%');

      return $transactions->orderBy($orderBy ?? 'id')->paginate($pageSize ?? 15);
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
