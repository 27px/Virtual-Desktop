<?php
  require_once("database.php");
  $conn=new mysqli($servername,$username,$password);
  if(mysqli_connect_error())
  {
    die("<div class='resultError'>Connection Error : ".mysqli_connect_error()."</div>");
  }
  if(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE '".$database."'"))))
  {
    die("<div class='resultError'>Database not Found</div>");
  }
  if(!($conn->query("USE ".$database)==true))
  {
    die("<div class='resultError'>Couldn't change Database</div>");
  }
?>
