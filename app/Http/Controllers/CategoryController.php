<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'orderBy'       => ['regex:(name)'],
        'order'         => ['regex:(asc|desc)'],
        'pageSize'      => 'numeric',
      ]);

      $user           = $request->user();
      $search         = $request->search;
      $orderBy        = $request->orderBy ?? 'id';
      $pageSize       = $request->pageSize;
      $order          = $request->order ?? 'asc';

      $categories     = Category::withCount('services');
      if ($search) $categories->where(function($q) use($search){
        $q->where('name', 'LIKE', '%'.$search.'%');
      });

      $categories = $categories->orderBy($orderBy, $order)->paginate($pageSize);
      // dd($categories);
      foreach ($categories->items() as $cat) {
        $cat->withImageUrl(null, 'avatar');
      }
      return $categories;
      return $categories->map(function($cat){
        return $cat->withImageUrl(null, 'avatar');
      });
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
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
