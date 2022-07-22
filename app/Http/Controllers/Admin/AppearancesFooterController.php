<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Admin\AdminController;

use App\Option;

class AppearancesFooterController extends AdminController
{
  public function index()
  {
    $footer = Option::where('name', 'footer')->get();

    return $footer;
  }

  public function store(Request $request)
  {
    $data = $request->all();

    $footer = Option::where('name', 'footer')->get();

    if (sizeof($footer) == 0) {
      $footer = Option::create(array(
        'name' => 'footer',
        'value' => $data['value']
      ));
    } else {
      $footer = Option::where('name', 'footer')->update(array('value' => $data['value']));
    }

    return $footer;
  }
}
