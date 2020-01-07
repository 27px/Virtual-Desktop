<?php
  require_once("connect_db.php");
  require_once("app.php");
  if(!isset($_SESSION['Logged']))
  {
    @session_start();
  }
  $result=$conn->query("SELECT * FROM Apps WHERE User='".$_SESSION['Logged']."'");
  $n=mysqli_num_rows($result);
  if($n<=0)
  {
    echo "<div class='resultError'>Sorry You haven't created any apps.</div>";
  }
  else
  {
    while($row=mysqli_fetch_row($result))
    {
      app($row,1);
    }
  }
?>
