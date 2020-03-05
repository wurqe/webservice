<?php

namespace App\Http\Controllers;

use App\ServiceApplication;
use App\Service;
use Illuminate\Http\Request;

class ServiceApplicationController extends Controller
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
        'pageSize'      => 'int',
        'service_id'    => 'int',
      ]);

      $user           = $request->user();
      $user_id        = $user ? $user->id : 0;
      $service_id     = $request->service_id;
      $pageSize       = $request->pageSize;
      $type           = $request->type ?? 'received';

      if ($type == 'sent') {
        $applications = $user->applications();
      } else {
        $applications = $user->received_applications();
      }

      if($service_id) $applications->where('service_id', $service_id);

      return $applications->paginate($pageSize);
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
      $user       = $request->user();
      $comment    = $request->comment;
      $service_id = $request->service_id;
      $service    = Service::findOrFail($service_id);
      // check if a seeking service
      if(!$service->isSeeking()) return ['status' => false, 'message' => trans('msg.application.cannot_apply_provide')];
      // check pending application
      if($pending = $user->hasPendingApp($service)) return ['status' => false, 'application' => $pending, 'message' => trans('msg.application.pending')];

      $application = $user->applications()->create([
        'service_id'      => $service->id,
        'receiver_id'     => $service->user_id,
        'comment'         => $comment
      ]);

      return ['status' => true, 'application' => $application, 'message' => trans('msg.application.sent')];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceApplication  $serviceApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceApplication $application)
    {
      $this->authorize('update', $application);
      $request->validate([
        'action' => ['required', 'regex:(accepted|rejected|canceled)'],
      ]);
      $user = $request->user();
      $action = $request->action;
      // if the user is the application sender
      if ($application->user_id == $user->id) {
        // sender can only cancel invitaion
        if ($action == 'canceled') $application->update(['status' => $action]);
        else $this->unauthorizedExe(trans('msg.application.only_cancel'));
      } else {
        // receiver can only accept or decline invitaion
        if (in_array($action, ['accepted', 'rejected'])) $application->update(['status' => $action]);
        else $this->unauthorizedExe(trans('msg.application.only_attempt'));
      }
      return ['status' => true, 'message' => trans('msg.application.updated'), 'application' => $application];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceApplication $serviceApplication)
    {
        //
    }
}
