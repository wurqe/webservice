<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validatePagination($request, $customs = []){
      $request->validate(array_merge([
        'search'        => 'nullable',
        'orderBy'       => ['nullable|string'],
        'order'         => ['regex:(asc|desc)'],
        'pageSize'      => 'nullable|int',
      ], $customs));
    }

    public function unauthorizedExe($message = 'This action is unauthorized.') {
      abort(403, $message);
    }
}
