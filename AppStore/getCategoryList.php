<?php
  require_once('connect_db.php');
  $result=$conn->query("SELECT Category FROM Apps WHERE Status='Approved' GROUP BY Category LIMIT 250");
  $n=mysqli_num_rows($result);
  if($n<=0)
  {
    echo "<div class='resultError'>Sorry No Categories are Available.</div>";
  }
  else
  {
    while($row=mysqli_fetch_row($result))
    {
      if($row[0]!="")
      echo "<div class='category' onclick=\"getAppsByCategory('".$row[0]."');\">".$row[0]."</div>";
    }
  }
?>
