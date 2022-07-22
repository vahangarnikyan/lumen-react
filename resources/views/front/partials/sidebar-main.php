<?php
  use App\Facades\Data;

  $banner_settings = (object)[
    'banner_name' => 'side',
    'style' => 'display: inline-block; width: 100%; height: 320px;',
  ];
  require(dirname(__FILE__) . '/../widgets/banner.php');