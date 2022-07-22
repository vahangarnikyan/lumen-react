<?php 
use Illuminate\Support\Facades\Auth;
use App\Facades\Constants;
?>
<?php require_once(dirname(__FILE__) . '/partials/header.php'); ?>
<?php
  $user = Auth::user();
?>
<div class="container">
  <div class="row">
    <div class="col-12 col-xl-8 col-lg-7">
      <div class="card">
        <div class="card-header">
          <?php echo trans('app.user_info'); ?>
        </div>
        <div class="card-body">
          <div>
            <label class="h-label"><?php echo trans('app.name'); ?>: </label>
            <label><?php echo $user->name; ?></label>
          </div>
          <div>
            <label class="h-label"><?php echo trans('app.username'); ?>: </label>
            <label><?php echo $user->username; ?></label>
          </div>
          <div>
            <label class="h-label"><?php echo trans('app.email'); ?>: </label>
            <label><?php echo $user->email; ?></label>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-4 col-lg-5">
      
    </div>
  </div>
</div>
<?php require_once(dirname(__FILE__) . '/partials/footer.php'); ?>