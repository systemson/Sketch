<div class="col-sm-12">
  <h1>Hello <sk-var="name"></h1>
  <h3><sk-echo>$description</sk-echo></h3>

  <sk-php>$array = [1,2,3,4]</sk-php>

  <sk-foreach="$array as $value">
  	<p>This is a foreach loop <sk-var="value"></p>
  </sk-foreach>

  <p>
    Sketch <sk-version>
    <span class="pull-right"> <sk-lap></span>
  </p>
</div>
