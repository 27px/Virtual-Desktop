<?php
  if(isset($_GET['getcategory']) && !empty($_GET['getcategory']))
  {
    require_once('connect_db.php');
    require_once('app.php');
    $result=$conn->query("SELECT * FROM Apps WHERE Category LIKE '".$_GET['getcategory']."' AND Status='Approved' LIMIT 25");
    $n=mysqli_num_rows($result);
    if($n<=0)
    {
      echo "<div class='resultError'>Sorry No Categories are Available.</div>";
    }
    else
    {
      while($row=mysqli_fetch_row($result))
      {
        app($row);
      }
    }
  }
?>
