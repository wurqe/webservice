<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user = $request->user();
      // $invitation = $user->invitations()->findOrFail(10);
      // $service = $user->services()->first();
      // return $user->edit($service, ['amount' => 500], 'price', $user);
      // return $service->edits;
      // return $user->moderate($service->edits()->first(), 'accepted');
      // return $user->editing;
      // return $user->edited;
      // return $service->edited;
      // return [$user->hasPendingEditFor($invitation)];
      // return [$invitation->otherBider($user)];
      $service = \App\Service::find(4);
      $work = \App\Work::find(10);
      $amount = $work->amount;
      $work_start = new \DateTime($work->created_at);
      $work_completed = new \DateTime($work->completed_at);
      $diff = $work_start->diff($work_completed);
      $mins = $diff->s;
      $hrs = $diff->f;
      $floated = (float)"$hrs.$mins";
      $earned = $floated * $amount;
      return [$work->calculateAmount()];
      return [$diff, $floated, $amount, $earned];
    }

    public function reset()
    {
      // Artisan::call('app:reset');
    }

    public function welcome()
    {
      return view('welcome');
    }

    public function clearCache()
    {
      $exitCode = Artisan::call('cache:clear');
      return '<h1>Cache facade value cleared</h1>';
    }

    public function optimize()
    {
      $exitCode = Artisan::call('optimize');
      return '<h1>Reoptimized class loader</h1>';
    }

    public function routeCache()
    {
      $exitCode = Artisan::call('route:cache');
      return '<h1>Routes cached</h1>';
    }

    public function routeClear()
    {
      $exitCode = Artisan::call('route:clear');
      return '<h1>Route cache cleared</h1>';
    }

    public function viewClear()
    {
      $exitCode = Artisan::call('view:clear');
      return '<h1>View cache cleared</h1>';
    }

    public function configCache()
    {
      $exitCode = Artisan::call('config:cache');
      return '<h1>Clear Config cleared</h1>';
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
        return ['1'=>1];
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

    public function countUsers()
    {
      return ['count' => \App\User::count()];
    }
}
