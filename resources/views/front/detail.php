<?php require_once(dirname(__FILE__) . '/partials/header.php'); ?>

<div class="dashboard">
  <div class="container">
    <h3 class="mt-3"><?php echo $name . ' (' . $symbol . ')'; ?></h3>
    <input type="hidden" id="symbol" value="<?php echo $symbol; ?>" />
    <h4></h4>
    <div class="row my-3">
      <div class="col-sm-6 col-md-3">
        <h5 id="open"></h5>
      </div>
      <div class="col-sm-6 col-md-3">
        <h5 id="high"></h5>
      </div>
      <div class="col-sm-6 col-md-3">
        <h5 id="low"></h5>
      </div>
      <div class="col-sm-6 col-md-3">
        <h5 id="close"></h5>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <a class="period selected">1 Day</a>
        <a class="period">1 Week</a>
        <a class="period">1 Month</a>
        <a class="period">3 Months</a>
        <a class="period">6 Months</a>
        <a class="period">1 Year</a>
      </div>
      <div class="col-sm-12">
        <div id="detailChart"></div>
      </div>
    </div>
  </div>
</div>

</div <?php require_once(dirname(__FILE__) . '/partials/footer.php'); ?>