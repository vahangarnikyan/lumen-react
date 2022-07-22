<?php
  use App\Facades\Data;
  use Illuminate\Support\Facades\Request;
  use App\Facades\Utils;

  $errors = Request::session()->get('errors');
  $values = Request::session()->get('values');
  $alert = Request::session()->get('alert');
?>
<?php require_once(dirname(__FILE__) . '/partials/header.php'); ?>

<div class="site-login">
  <div class="container">
    <?php 
      $banner_settings = (object)[
        'banner_name' => 'top',
        'style' => 'display: inline-block; width: 100%; height: 90px;',
      ];
      require(dirname(__FILE__) . '/widgets/banner.php');
    ?>
    <div class="row">
      <div class="col-sm-12 col-md-8">
        <?php if (!is_null($post1)) echo $post1->content; ?>
      </div>
      <div class="col-sm-12 col-md-4">
        <h2><?php echo trans('app.login'); ?></h2>
        <form method="post" action="login">
          <?php if ($alert && $alert->status == 'danger') : ?>
            <div class="alert alert-danger">
              <?php echo $alert->message; ?>
            </div>
          <?php endif; ?>
          <div class="form-group">
            <label for="username"><?php echo trans('app.email'); ?></label>
            <input type="text" name="username" id="username" placeholder="<?php echo trans('app.email'); ?>"
              value="<?php echo isset($values['username']) ? $values['username'] : ''; ?>" required
              class="form-control<?php if (isset($errors) && $errors->has('username')) { echo " is-invalid"; } ?>"
            >
            <?php if (isset($errors) && $errors->has('username')) : ?>
              <div class="invalid-feedback">
                <?php echo $errors->first('username') ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="password"><?php echo trans('app.password'); ?></label>
            <input type="password" name="password" id="password" placeholder="<?php echo trans('app.password'); ?>"
              value="<?php echo isset($values['password']) ? $values['password'] : ''; ?>" required min="6"
              class="form-control<?php if (isset($errors) && $errors->has('password')) { echo " is-invalid"; } ?>"
            >
            <?php if (isset($errors) && $errors->has('password')) : ?>
              <div class="invalid-feedback">
                <?php echo $errors->first('password') ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary text-uppercase"><?php echo trans('app.login'); ?></button>
          </div>
          <div class="form-group text-center">
            <a href="<?php echo Utils::localeUrl('register'); ?>"><?php echo trans('app.register') ?></a>
            |
            <a href="<?php echo Utils::localeUrl('forgot'); ?>"><?php echo trans('app.forgot_password_quiz') ?></a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="site-content">
  <div class="container">
    
  </div>
</div>

</div <?php require_once(dirname(__FILE__) . '/partials/footer.php'); ?>