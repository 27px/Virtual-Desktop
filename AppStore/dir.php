<?php
  require_once("../config/root.php");
  if(!@is_dir($dir))
  {
    $dir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
  }
?>
