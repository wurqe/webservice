<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'type'          => ['regex:(received|sent)'],
        'search'        => '',
        'pageSize'      => 'numeric',
      ]);

      $user           = $request->user();
      $user_id        = $user ? $user->id : 0;
      $search         = $request->search;
      // $orderBy        = $request->orderBy;
      $pageSize       = $request->pageSize;
      $type           = $request->type ?? 'received';

      if ($type == 'received') $invitations = $user->invitations();
      else $invitations = $user->received_invitations();

      // if ($search) $invitations->where('title', 'LIKE', '%'.$search.'%');

      return $invitations->with('bids')->paginate($pageSize ?? null);
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
        'message'             => '',
        'service_id'          => 'required|int',
        'receiver_id'         => 'required|int',
        'bid_amount'          => 'numeric',
      ]);

      $user         = $request->user();
      $receiver_id  = $request->receiver_id;
      $bid_amount   = $request->bid_amount;
      $receiver     = $user->findOrFail($receiver_id);
      $service      = \App\Service::findOrFail($request->service_id);

      // check pending invitation
      $pending    = $user->pending_invitations()->where('service_id', $service->id)->first();
      if($pending) return ['status' => false, 'invitation' => $pending->load('bids'), 'message' => trans('msg.invitation.pending')];

      // message interface
      // --------->

      $invitation = $user->invite($service, $receiver, $bid_amount);

      return ['status' => true, 'invitation' => $invitation, 'message' => trans('msg.invitation.sent')];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function show(Invitation $invitation)
    {
      $this->authorize('view', $invitation);
      return $invitation->load('bids');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invitation $invitation)
    {
      $this->authorize('update', $invitation);
      $request->validate([
        'action'     => [
          Rule::requiredIf(function () use ($request) {
            return !$request->bid_id && !$request->bid_amount;
          }),
          'regex:(accepted|rejected|canceled)'
        ],
        'bid_id'     => 'int',
        'bid_amount' => 'numeric',
        'bid_action' => [
          Rule::requiredIf(function () use ($request) {
            return !!$request->bid_id;
          }),
          'regex:(accepted|rejected|canceled)'
        ],
      ]);

      $user       = $request->user();
      $action     = $request->action;
      $bid_action = $request->bid_action;
      $bid_id     = $request->bid_id;
      $bid        = null;
      if($bid_id) $bid = $invitation->edits()->findOrFail($bid_id);
      $bid_amount = $request->bid_amount;
      if($action){
      // if the user is the invitation sender
        if ($invitation->isSender($user)) {
          // sender can only cancel invitation
          if ($action == 'canceled') $invitation->cancel();
          else $this->unauthorizedExe(trans('msg.invitation.only_cancel'));
        } else {
          // receiver can only accept or decline invitation
          if (in_array($action, ['accepted', 'rejected'])) $invitation->attempt($action);
          else $this->unauthorizedExe(trans('msg.invitation.only_attempt'));
        }
      }
      // attempt bid
      if($action == 'accepted' || ($bid_amount || $bid_action)) {
        $invitation->attemptBid($user, $bid, $bid_action ?? $action, $bid_amount);
      }

      return ['status' => true, 'message' => trans('msg.invitation.updated'), 'invitation' => $invitation];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
