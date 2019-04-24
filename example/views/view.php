<div class="col-sm-12">
  <h1>Hello <sketch-var="name"></h1>
  <h3><sketch-echo>$description</sketch-echo></h3>

  <sketch-php>$array = [1,2,3,4]</sketch-php>

  <sketch-foreach="$array as $value">
  	<p>This is a foreach loop <sketch-var="value"></p>
  </sketch-foreach>

  <p>
    Sketch <sketch-version>
    <span class="pull-right"> <sketch-lap></span>
  </p>
</div>
