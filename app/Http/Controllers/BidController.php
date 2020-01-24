<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invitation;
use App\Work;
use App\Service;
use App\Edit;
use Illuminate\Validation\Rule;

class BidController extends Controller
{
  private $workClass = Work::class;
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function attemptBid(Request $request){
      $request->validate([
        'action'     => [
          Rule::requiredIf(function () use ($request) {
            return !$request->bid_id && !$request->bid_amount;
          }),
          'regex:(accepted|rejected|canceled)'
        ],
        'bid_id'     => 'int',
        'bid_amount' => [
          'numeric',
        ],
        'invitation_id' => ['int',
          Rule::requiredIf(function () use ($request) {
            return !$request->job_id;
          }),
        ],
        'job_id' => 'int',
        'bid_action' => [
          Rule::requiredIf(function () use ($request) {
            return !!$request->bid_id;
          }),
          'regex:(accepted|rejected|canceled)'
        ],
      ]);

      $user             = $request->user();
      $invitation       = null;
      $invitation_id    = $request->invitation_id;
      if($invitation_id) {
        $invitation  = Invitation::findOrFail($invitation_id);
        $this->authorize('update', $invitation);
      }
      $job              = null;
      $job_id           = $request->job_id;
      if($job_id) $job  = Work::findOrFail($job_id);
      $bid_id           = $request->bid_id;
      $bid              = null;
      if($bid_id) {
        $bid  = Edit::findOrFail($bid_id);
        $this->authorize('update', $bid);
      }
      $bid_amount       = $request->bid_amount;
      $bid_action       = $request->bid_action;

      if($job){
        $afterBid = $job->attemptBid($user, $bid, $bid_action, $bid_amount);
        if($afterBid && $afterBid instanceof $this->workClass){
          $job = $afterBid;
        }
      }

      if($invitation){
        $afterBid = $invitation->attemptBid($user, $bid, $bid_action, $bid_amount);
        if($afterBid && $afterBid instanceof $this->workClass){
          $job = $afterBid;
        }
      }

      return ['status' => true, 'message' => trans('msg.invitation.updated'),
        'invitation' => $invitation, 'job' => $job,
      ];
    }
}
