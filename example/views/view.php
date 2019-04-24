<div class="col-sm-12">
  <h1>Hello <?= $name; ?></h1>
  <h3><?= $description; ?></h3>
  <sketch-foreach="[1,2,3,4] as $value">
  	<p>This is a foreach loop <?= $value; ?></p>
  </sketch-foreach>
  <p>
    Sketch <sketch-version>
    <span class="pull-right"> <sketch-lap></span>
  </p>
</div>
