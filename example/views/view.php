<div class="col-sm-12">
  <h1>Hello <?= $name; ?></h1>
  <h3><?= $description; ?></h3>
  <p>
    Sketch <?= $version; ?>
    <span class="pull-right"> <?= number_format(microtime(true) - AMBER_START, 6); ?></span>
  </p>
</div>
