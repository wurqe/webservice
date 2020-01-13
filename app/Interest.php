<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
  protected $fillable = ['name'];
  protected $hidden = ["pivot", "created_at", "updated_at"];

  public static function getInterests($request){
    $search         = $request->search;
    $orderBy        = $request->orderBy;
    $pageSize       = $request->pageSize;

    if ($search) {
      return Interest::where('name', 'LIKE', '%'.$search.'%')->paginate($request->pageSize ? $request->pageSize : null);
    } else {
      return Interest::paginate($request->pageSize ? $request->pageSize : null);
    }
  }

  public function users(){
    return $this->belongsToMany(User::class, 'interest_users')->withTimestamps();
  }
}
