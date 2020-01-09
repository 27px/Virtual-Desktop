<?php
  require_once("app.php");
  require_once("../config/connect_db.php");
  $result=$conn->query("SELECT * FROM Apps WHERE Status='Approved' ORDER BY ID desc LIMIT 25");
  $n=mysqli_num_rows($result);
  if($n<=0)
  {
    echo "<div class='resultError'>Sorry No Apps In the Store.</div><div class='resultERRImage'></div>";
  }
  else
  {
    while($row=mysqli_fetch_row($result))
    {
      app($row);
    }
  }
?>
