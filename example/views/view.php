<div class="col-sm-12">
  <h1>Hello <skVar="name" /></h1>
  <h3><skRaw>$description</skRaw></h3>

  <skPhp>$array = [1,2,3,4,5]</skPhp>

  <ul>
    <skForeach="$array as $value">
      <li>This is a foreach loop <skVar="value"></li>
    </skForeach>
  </ul>

  <ul>
    <skFor="$x=0; $x<count($array); $x++">
      <li>This is a for loop <skVar="array[$x]"></li>
    </skFor>
  </ul>

  <ul>
    <skForeach="[] as $empty">
    <skEmpty />
      <p>This is an empty loop</p>
    </skForeach>

  </ul>

  <skIf="false">
    <skElseIf="true">
    <skElse>
  </skIf>


  <p>
    This is a html injection: 
    <skPhp> $div1 = '<div class="container">If you see the div tags as string it\'s safe</div>'</skPhp>
    <skPhp> $div2 = '<script type="text/javascript">alert("Won\'t be alerted.");</script>'</skPhp>
    <br>
    <skVar="div1">
    <br>
    <skEcho>$div2</skEcho>
  </p>

  <p>
    Sketch <skVersion>
    <span class="pull-right"> <skLap></span>
  </p>
</div>
