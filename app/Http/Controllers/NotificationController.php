<?php

namespace App\Http\Controllers;

// use App\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user       = $request->user();
      $search         = $request->search;
      $pageSize   = $request->pageSize;

      $notifications = $user->notifications();

      if($search) $notifications->orWhere('data->title', 'LIKE', '%'.$search.'%')->orWhere('data->message', 'LIKE', '%'.$search.'%');

      return $notifications->paginate($pageSize);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(DatabaseNotification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(DatabaseNotification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatabaseNotification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatabaseNotification $notification)
    {
        //
    }
}
