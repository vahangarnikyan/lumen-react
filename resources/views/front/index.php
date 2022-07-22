<?php
  use App\Facades\Data;
?>
<?php require_once(dirname(__FILE__) . '/partials/header.php'); ?>

<div class="dashboard">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        
      </div>
    </div>
  </div>
  <div class="table-wrapper table-responsive">
    
  </div>
  <div class="container">
    <div class="row mt-5 px-3">
      <?php if (!is_null($page)) echo $page->content; ?>
    </div>
  </div>
</div>

</div <?php require_once(dirname(__FILE__) . '/partials/footer.php'); ?>