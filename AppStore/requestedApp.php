<?php
  require_once('../config/connect_db.php');
  global $dir;
  require_once("dir.php");
  require_once("checkInstalledApp.php");
  global $inversion;
  if(!is_array($inversion))
  {
    $inversion=array();
    $inversion=checkInstalledApp();
  }
  $result=$conn->query("SELECT * FROM Apps WHERE Status='Requested'");
  $n=mysqli_num_rows($result);
  if($n>0)
  {
    while($row=mysqli_fetch_row($result))
    {
      global $inversion;
      echo "<div class='result' id='apprequest_".$row[0]."'><span class='icon' style='background-image:url(../AppStore/icon/".$row[9].");'></span>";
      echo "<p class='name'>".$row[1]."</p><p class='author'>";
      echo $row[3];//User Name
      echo "</p><p class='description'>".$row[5]."</p>";
      echo "<div class='appbuttontwo approve' onclick=\"approveApp('".$row[0]."');\">Approve</div>";
      echo "<div class='appbuttonthree reject' onclick=\"rejectApp('".$row[0]."');\">Reject</div>";
      echo "<div class='appbuttonfour files' onclick=\"seeFiles('".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/".$root."AppStore/Apps/".$row[1]."');\">Files</div>";
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
    echo "<div class='resultError'>No pending Requests.</div><div class='resultERRImage'></div>";
  }
?>
