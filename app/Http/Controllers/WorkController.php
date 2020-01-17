<?php

namespace App\Http\Controllers;

use App\Work;
use App\Service;
use App\Invitation;
use Illuminate\Http\Request;

class WorkController extends Controller
{
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
        'invitation_id'   => 'required|numeric',
        'payment_method'  => ['regex:(wurqe|cash)'],
        'amount'          => 'numeric',
        // 'amount_currency' => 'numeric',
      ]);
      $invitation_id  = $request->invitation_id;
      $invitation     = Invitation::findOrFail($invitation_id);
      $service_id     = $invitation->service_id;
      $service        = $invitation->service;
      $amount         = $invitation->amount ?? $service->amount ?? 0;
      $payment_method = $request->payment_method ?? $invitation->payment_method ?? 'wurqe';
      $percentage     = 99;//99% e.g

      $this->authorize('work', $invitation);
      $this->authorize('create', Work::class);

      // check started work
      if($work    = $invitation->isWorkStarted()) return ['status' => false, 'work' => $work, 'message' => trans('msg.work.started')];
      $calculated = $amount / (100/$percentage);

      $work = $service->work()->create([
        'invitation_id'     => $invitation_id,
        'amount'            => $calculated,
        'payment_method'    => $payment_method,
      ]);

      return ['status' => true, 'work' => $work, 'message' => trans('msg.work.starts')];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
      $this->authorize('update', $work);

      // $work->complete();
      // return ['status' => true, 'message' => trans('msg.work.completed')];
    }

    /**
     * compelete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, Work $work)
    {
      $this->authorize('complete', $work);

      // check work completed
      if($completed = $work->isCompleted()) return ['status' => false, 'work' => $work, 'message' => trans('msg.work.completed')];

      $work->complete();
      return ['status' => true, 'work' => $work, 'message' => trans('msg.work.completes')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        //
    }

    public function rate(Request $request, Work $work)
    {
      $this->authorize('rate', $work);
      // check if already rated

      $request->validate([
        'rating'         => 'required|digits_between:1,5',
        'title'          => 'min:3|max:99',
        'body'           => 'max:255',
        // rating	title	body	approved	reviewrateable_type	reviewrateable_id	author_type	author_id
      ]);
      if ($work->isRated()) return ['status' => false, 'rating' => $work->rated, 'message' => trans('msg.work.rated')];
      $user     = $request->user();
      $rating   = $request->rating;
      $title    = $request->title;
      $body     = $request->body;

      $rated = $user->rate($work, $work->rateArray($rating, $title, $body));

      return ['status' => true, 'rating' => $rated, 'message' => trans('msg.work.rated')];
    }
}
