<html>
<head>
  <script src="script/w3colorCoder.min.js"></script>
</head>
<body>
<div id="coder" style="white-space:no-wrap;">
<?php
  $h=htmlspecialchars(file_get_contents("index.php"));
  $h=str_replace(' ','&nbsp;',$h);//Space
  $h=str_replace('	','&nbsp;&nbsp;',$h);//Tab
  $h=nl2br($h);

  echo $h;
?>
</div>
<script>
w3CodeColor("coder","html");
</script>
</body>
</html>
