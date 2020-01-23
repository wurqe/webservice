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
        'pageSize'      => 'nullable|int',
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

    public function options(Request $request)
    {
      $request->validate([
        'search'        => '',
        'orderBy'       => ['regex:(id|authorization_code|bin|last4|exp_month|exp_year|channel|card_type|bank|country_code|brand|reusable)', 'nullable'],
        'pageSize'      => 'nullable|int',
      ]);

      $user           = $request->user();
      $orderBy        = $request->orderBy;
      $pageSize       = $request->pageSize;

      $payment_options = $user->payment_options();

      return $payment_options->orderBy($orderBy ?? 'id')->paginate($pageSize);
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
      $amount           = $request->amount;
      $user             = $request->user();
      $data             = ["amount" => $amount, "email" => $user->email];
      $response         = Paystack::getAuthorizationResponse($data);
      // $authorization_url= $response['authorization_url'];

      return $user->payments()->create([
        'amount'        => $amount,
        'access_code'   => $response['access_code'],
        'reference'     => $response['reference'],
      ]);
    }

    // android test
    public function Astore(Request $request)
    {
      $amount           = 1000;
      $user             = \App\User::first();
      $data             = ["amount" => $amount, "email" => $user->email];
      $response         = Paystack::getAuthorizationResponse($data);
      // $authorization_url= $response['authorization_url'];
      $user->payments()->create([
        'amount'        => $amount,
        'access_code'   => $response['access_code'],
        'reference'     => $response['reference'],
      ]);
      return $response['access_code'];
    }
    // android test
    public function Averify(Request $request, $code)
    {
      \Request::instance()->query->set('trxref', $code);
      \Request::instance()->query->set('reference', $code);
      $paymentDetails   = Paystack::getPaymentData();
      $payment          = Payment::where('reference', $paymentDetails->reference)->first();

      if ($payment && $payment->status == 'pending') {
        $user           = $payment->user;
        if ($paymentDetails->status != 'success') {
          $payment->update([
            'status' => $paymentDetails->status,
          ]);
        }

        if ($paymentDetails->status == 'success') {
          $payment->update([
            'status'              => $paymentDetails->status,
            'message'             => $paymentDetails->message,
            'reference'           => $paymentDetails->reference,
            'authorization_code'  => $paymentDetails->authorization['authorization_code'],
            'currency_code'       => $paymentDetails->currency,
            'payed_at'            => now(),//$paymentDetails['data']['paidAt'],
          ]);
          $user->deposit($paymentDetails->amount);
          if ($paymentDetails->status = "success" && $paymentDetails->authorization['reusable']) {
            $user->payment_options()->firstOrCreate(
              ['authorization_code' => $paymentDetails->authorization['authorization_code']],
              $paymentDetails->authorization
            );
          }
        }

        return ['status' => true, 'payment' => $payment];
      }
      return ['status' => false, 'payment' => $payment];
    }

    public function verify(Request $request)
    {
      $request->validate([/*'reference' => 'required',*/ 'trxref' => 'required']);
        // \Request::instance()->query->set('trxref', $code);
        // \Request::instance()->query->set('reference', $code);
        $paymentDetails   = Paystack::getPaymentData();
        $payment          = Payment::where('reference', $paymentDetails->reference)->first();

        if ($payment && $payment->status == 'pending') {
          $user           = $payment->user;
          if ($paymentDetails->status != 'success') {
            $payment->update([
              'status' => $paymentDetails->status,
            ]);
          }

          if ($paymentDetails->status == 'success') {
            $payment->update([
              'status'              => $paymentDetails->status,
              'message'             => $paymentDetails->message,
              'reference'           => $paymentDetails->reference,
              'authorization_code'  => $paymentDetails->authorization['authorization_code'],
              'currency_code'       => $paymentDetails->currency,
              'payed_at'            => now(),//$paymentDetails['data']['paidAt'],
            ]);
            $user->deposit($paymentDetails->amount);
            if ($paymentDetails->status = "success" && $paymentDetails->authorization['reusable']) {
              $user->payment_options()->firstOrCreate(
                ['signature' => $paymentDetails->authorization['signature']],
                $paymentDetails->authorization
              );
            }
          }

          return ['status' => true, 'payment' => $payment];
        }
        return ['status' => false, 'payment' => $payment];
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
