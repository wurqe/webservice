<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;

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

      return $invitations->paginate($pageSize ?? null);
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
      $bid          = $request->bid_amount;
      $receiver     = $user->findOrFail($receiver_id);
      $service      = \App\Service::findOrFail($request->service_id);

      // check pending invitation
      $pending    = $user->pending_invitations()->where('service_id', $service->id)->first();
      if($pending) return ['status' => false, 'invitation' => $pending->loadBids(), 'message' => trans('msg.invitation.pending')];

      // message interface
      // --------->

      $invitation = $user->invite($service, $receiver, $bid);

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
        //
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
        'action' => ['required', 'regex:(accepted|rejected|canceled)'],
      ]);
      $user = $request->user();
      $action = $request->action;
      // if the user is the invitation sender
      if ($invitation->user_id == $user->id) {
        // sender can only cancel invitation
        if ($action == 'canceled') $invitation->update(['status' => $action]);
        else $this->unauthorizedExe(trans('msg.invitation.only_cancel'));
      } else {
        // receiver can only accept or decline invitation
        if (in_array($action, ['accepted', 'rejected'])) $invitation->update(['status' => $action]);
        else $this->unauthorizedExe(trans('msg.invitation.only_attempt'));
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
