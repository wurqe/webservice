<?php

namespace App\Http\Controllers;

use App\Service;
use App\ServiceApplication;
use App\Invitation;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  public function __construct(){
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'type'          => ['regex:(seek|provide)'],
        'search'        => '',
        'orderBy'       => ['regex:(title)'],
        'order'         => ['regex:(asc|desc)'],
        'pageSize'      => 'numeric',
      ]);

      $user           = $request->user();
      $user_id        = $user ? $user->id : 0;
      $search         = $request->search;
      $orderBy        = $request->orderBy;
      $pageSize       = $request->pageSize;
      $order          = $request->order ?? 'asc';
      $type           = $request->type ?? 'seek';

      $services = Service::where('user_id', '!=', $user_id)->with(['skills', 'category:id,name']);

      if ($search) $services->where('title', 'LIKE', '%'.$search.'%');
      else $services->where('type', $type);

       $services = $services->orderBy($orderBy, $order)->paginate($pageSize);
       return $services->map(function($s){return $s->withImageUrl(null, 'attachments', true);});
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
        'category_id'     => 'required|int',
        'title'           => 'required|min:3|max:15',
        'type'            => ['required', 'regex:(seek|provide)'],
        'description'     => 'required',
        'payment_type'    => ['required', 'regex:(fixed|hourly|flexible)'],
        'negotiable'      => 'bool',
        'amount'          => 'numeric',
        'terms'           => '',
        'timeframe'       => '',
        'skills'          => 'array',
        'attachments'     => 'array',
        'attachments.*'   => 'imageable',
        'availability'    => ''
      ]);

      $user = $request->user();
      $timeframe = $request->timeframe;
      $availability = $request->availability;

      $service = $user->services()->create([
        'category_id'     => $request->category_id,
        'title'           => $request->title,
        'type'            => $request->type,
        'description'     => $request->description,
        'payment_type'    => $request->payment_type,
        'negotiable'      => $request->negotiable,
        'amount'          => $request->amount,
        'terms'           => $request->terms,
        'tags'            => $request->skills,
      ]);

      // save meta
      if ($timeframe || $availability) {
        if ($request->type === 'seek') $service->addMeta([], ['name' => 'timeframe', 'value' => $request->timeframe]);
        else $service->addMeta([], ['name' => 'availability', 'value' => $request->availability]);
      }

      try {
        if($service) $service->saveImage($request->attachments, 'attachments');
      } catch (\Exception $e) {}
      return ['status' => true, 'service' => $service, $user];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function hire(Request $request, Invitation $invitation)
    {
      $this->authorize('hire', $invitation);
      $user = $request->user();
      // check accepted invitation
      if (!$invitation->isAccepted()) return ['status' => false, 'message' => trans('msg.service.not_hired')];
      $invitation->initaiteContract();
      return ['status' => true, 'message' => trans('msg.service.hired'), 'invitaion' => $invitation];
    }
}
