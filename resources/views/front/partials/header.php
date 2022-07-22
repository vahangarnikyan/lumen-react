<?php
  use Illuminate\Support\Facades\Auth;
  use App\Facades\Data;
  use App\Facades\Utils;
  use App\Facades\Options;

  $page_title = '';
  $page_keywords = '';
  $page_description = '';
  $page_canonical_link = '';
  if(isset($page)) {
    if (isset($page->locale_title) && !empty($page->locale_title)) {
      $page_title = $page->locale_title;
    } else if(isset($page->title) && !empty($page->title)) {
      $page_title = $page->title;
    }

    if (isset($page->locale_meta_keywords) && !empty($page->locale_meta_keywords)) {
      $page_keywords = $page->locale_meta_keywords;
    } else if(isset($page->meta_keywords) && !empty($page->meta_keywords)) {
      $page_keywords = $page->meta_keywords;
    }

    if (isset($page->locale_meta_description) && !empty($page->locale_meta_description)) {
      $page_description = $page->locale_meta_description;
    } else if(isset($page->meta_description) && !empty($page->meta_description)) {
      $page_description = $page->meta_description;
    }
  }
  $settings = Options::getSettingsOption();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
    if (isset($settings->analytics_code) && $settings->analytics_code != '') {
      echo $settings->analytics_code;
    }
  ?>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="alternate" href="<?php echo url('/es'); ?>" hreflang="es" />
  <link rel="alternate" href="<?php echo url('/'); ?>" hreflang="en" />
  <title><?php echo Utils::filterTitle($page_title); ?></title>
  <?php if (isset($settings->verify_tag) && $settings->verify_tag != '') : ?>
    <meta name="google-site-verification" content="<?php echo $settings->verify_tag; ?>">
  <?php endif; ?>
  <meta name="description" content="<?php echo $page_description; ?>">
  <meta name="keywords" content="<?php echo $page_keywords; ?>">
  <?php if (isset($canonical_link) && !empty($canonical_link)) : ?>
    <link rel="canonical" href="<?php echo $canonical_link; ?>">
  <?php endif; ?>
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo url('/media/apple-icon-57x57.png'); ?>">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo url('/media/apple-icon-60x60.png'); ?>">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo url('/media/apple-icon-72x72.png'); ?>">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo url('/media/apple-icon-76x76.png'); ?>">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo url('/media/apple-icon-114x114.png'); ?>">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo url('/media/apple-icon-120x120.png'); ?>">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo url('/media/apple-icon-144x144.png'); ?>">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo url('/media/apple-icon-152x152.png'); ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo url('/media/apple-icon-180x180.png'); ?>">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo url('/media/android-icon-192x192.png'); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo url('/media/favicon-32x32.png'); ?>">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo url('/media/favicon-96x96.png'); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo url('/media/favicon-16x16.png'); ?>">
  <link rel="manifest" href="<?php echo url('/media/manifest.json'); ?>">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo url('/media/ms-icon-144x144.png'); ?>">
  <meta name="theme-color" content="#ffffff">
  
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo url('/dist/front.css'); ?>">
</head>
<body class="<?php if(isset($page) && isset($page->page_class)) : echo $page->page_class; endif ?>">
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <script src="<?php echo url('/dist/vendors~admin~front.js') ?>"></script>
  <script src="<?php echo url('/dist/vendors~front.js') ?>"></script>
  
  <div class="site">
    <div class="site-header">
      <app-navigation class="navigation">
        <a class="navigation-brand" href="<?php echo url('/'); ?>">
          <img src="<?php echo url('/media/logo.png'); ?>" alt="Ganagratis">
        </a>
        <button class="navigation-toggler" <?php if (!Auth::check()) echo 'style="display: none;"' ?>>
          <span class="navigation-toggler-item"></span>
          <span class="navigation-toggler-item"></span>
          <span class="navigation-toggler-item"></span>
        </button>
        <div class="navigation-main">
          <ul class="navigation-nav">
            <li class="has-submenu d-block d-lg-none">
              <ul class="navigation-submenu">
                <?php if (!Auth::check()) : ?>
                <li>
                  <a href="<?php echo Utils::localeUrl('login'); ?>">
                    <span class="fa fa-sign-in"></span> <?php echo trans('app.login'); ?>
                  </a>
                </li>
                <li>
                  <a href="<?php echo Utils::localeUrl('register'); ?>">
                    <span class="fa fa-sign-in"></span> <?php echo trans('app.register'); ?>
                  </a>
                </li>
                <?php else: $user = Auth::user(); ?>
                <li>
                  <a href="<?php echo Utils::localeUrl('logout'); ?>">
                    <span class="fa fa-sign-out"></span> <?php echo trans('app.logout'); ?>
                  </a>
                </li>
                <li>
                  <a href="<?php echo Utils::localeUrl('profile'); ?>">
                    <span class="fa fa-user-circle-o"></span> <?php echo $user->firstname . ' ' . $user->lastname; ?>
                  </a>
                </li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>
        </div>
        
        <div class="navigation-side">
          <ul class="navigation-nav">
            <li class="has-submenu">
              <a><span class="fa fa-user-circle-o fa-2x"></span></a>
              <ul class="navigation-submenu">
                <?php if (!Auth::check()) : ?>
                <li>
                  <a href="<?php echo Utils::localeUrl('login'); ?>">
                    <span class="fa fa-sign-in"></span> <?php echo trans('app.login'); ?>
                  </a>
                </li>
                <li>
                  <a href="<?php echo Utils::localeUrl('register'); ?>">
                    <span class="fa fa-sign-in"></span> <?php echo trans('app.register'); ?>
                  </a>
                </li>
                <?php else: $user = Auth::user(); ?>
                <li>
                  <a href="<?php echo Utils::localeUrl('logout'); ?>">
                    <span class="fa fa-sign-out"></span> <?php echo trans('app.logout'); ?>
                  </a>
                </li>
                <li>
                  <a href="<?php echo Utils::localeUrl('profile'); ?>">
                    <span class="fa fa-user-circle-o"></span> <?php echo $user->firstname . ' ' . $user->lastname; ?>
                  </a>
                </li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>
        </div>
        
      </app-navigation>
    </div>
    <div class="site-main">