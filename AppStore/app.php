<?php
  require_once("checkInstalledApp.php");
  function app($row,$x=0)
  {
    global $inversion;
    if(!is_array($inversion))
    {
      $inversion=array();
      $inversion=checkInstalledApp();
    }
    echo "<div class='result'><span class='icon' style='background-image:url(../AppStore/icon/".$row[9].");'></span>";
    echo "<p class='name'>".$row[1]."</p><p class='author'>";
    if($x!=1)
    echo $row[3];//User Name
    else
    echo $row[4];//App Status
    echo "</p><p class='description'>".$row[5]."</p>";
    if(!in_array($row[0],$inversion[0]))
    {
      echo "<div class='appbutton install' onclick=\"installApp('".$row[0]."',this);\">Install</div>";
    }
    else
    {
      if($inversion[1][array_search($row[0],$inversion[0])]!=$row[17])
      {
        echo "<div class='appbuttontwo update' onclick=\"installApp('".$row[0]."',this);\">Update</div>";
      }
      echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[0]."',this);\">Uninstall</div>";
    }
    echo "</div>";
  }
?>
