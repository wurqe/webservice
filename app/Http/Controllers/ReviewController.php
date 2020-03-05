<?php

namespace App\Http\Controllers;

use \Codebyray\ReviewRateable\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'orderBy'       => ['regex:(rating|title|body|author_id)'],
        'order'         => ['regex:(asc|desc)'],
        'pageSize'      => 'numeric',
        'user_id'       => ['int', Rule::requiredIf(!$request->service_id)],
        'service_id'    => ['int']
      ]);

      $user_id        = $request->user_id;
      $service_id     = $request->service_id;
      $service = $user = null;
      if($user_id) $user = \App\User::findOrFail($user_id);
      if($service_id) $service = \App\Service::findOrFail($service_id);
      $search         = $request->search;
      $orderBy        = $request->orderBy ?? 'id';
      $pageSize       = $request->pageSize;
      $order          = $request->order ?? 'asc';

      if ($user_id) {
        $reviews        = $user->reviews();
      } else {
        $reviews        = $service->reviews();
      }

      if ($search) $reviews->where(function($q) use($search){
        $q->where('reviews.rating', 'LIKE', '%'.$search.'%')->orWhere('reviews.title', 'LIKE', '%'.$search.'%')->orWhere('reviews.body', 'LIKE', '%'.$search.'%')
        ->orWhere('reviews.author_id', 'LIKE', '%'.$search.'%');
      });

      return $reviews->orderBy($orderBy, $order)->paginate($pageSize);
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
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
