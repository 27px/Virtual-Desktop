<?php
if(isset($_GET['key']) && !empty($_GET['key']))
{
  $k=$_GET['key'];
  require_once("connect_db.php");
  require_once("app.php");
  $result=$conn->query("SELECT * FROM Apps WHERE (Name like '".$k."' OR Name like '%".$k."%' OR Description like '%".$k."%' OR Keyword1 like '%".$k."%' OR Keyword2 like '%".$k."%' OR Keyword3 like '%".$k."%' OR Category like '".$k."') AND (Status='Approved') LIMIT 25");
  $n=mysqli_num_rows($result);
  if($n<=0)
  {
    echo "<div class='resultError'>Sorry we could'nt find anything.</div>";
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
