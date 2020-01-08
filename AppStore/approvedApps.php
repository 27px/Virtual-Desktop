<?php
  require_once('connect_db.php');
  global $dir;
  require_once("dir.php");
  require_once("checkInstalledApp.php");
  global $inversion;
  if(!is_array($inversion))
  {
    $inversion=array();
    $inversion=checkInstalledApp();
  }
  $result=$conn->query("SELECT * FROM Apps WHERE Status='Approved' limit 25");
  $n=mysqli_num_rows($result);
  if($n>0)
  {
    while($row=mysqli_fetch_row($result))
    {
      global $inversion;
      echo "<div class='result'><span class='icon' style='background-image:url(../AppStore/icon/".$row[9].");'></span>";
      echo "<p class='name'>".$row[1]."</p><p class='author'>";
      echo $row[3];//User Name
      echo "</p><p class='description'>".$row[5]."</p>";
      //echo "<div class='appbuttontwo reject' onclick=\"rejectApp('".$row[0]."');\">Reject</div>";
      if(!in_array($row[0],$inversion[0]))
      {
        echo "<div class='appbutton install' onclick=\"installApp('".$row[0]."',this);\">Install</div>";
      }
      else if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
      {
        echo "<div class='appbutton sysprotected'>Default</div>";
      }
      else
      {
        echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[0]."',this);\">Uninstall</div>";
      }
      echo "</div>";
    }
  }
  else
  {
    echo "<div class='resultError'>No Apps Approved.</div>";
  }
?>
