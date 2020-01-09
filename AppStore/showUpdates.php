<?php
  require_once("../config/connect_db.php");
  global $conn;
  global $dir;
  require_once("dir.php");
  require_once("checkInstalledApp.php");
  global $inversion;
  if(!is_array($inversion))
  {
    $inversion=array();
    $inversion=checkInstalledApp();
  }
  $x=count($inversion[0]);
  if($x<=0)
  {
    echo "<div class='resultError'>No Updates are available.</div><div class='resultERRImage'></div>";
  }
  else
  {
    $i=0;
    $list="(0";
    while($i<$x)
    {
      $list.=",".$inversion[0][$i];
      $i++;
    }
    $list.=")";
    $result=$conn->query("SELECT * FROM Apps WHERE ID IN ".$list."");
    $n=mysqli_num_rows($result);
    if($n<=0)
    {
      echo "<div class='resultError'>No Updates are available.</div><div class='resultERRImage'></div>";
    }
    else
    {
      $i=0;
      while($row=mysqli_fetch_row($result))
      {
        if($inversion[1][array_search($row[0],$inversion[0])]!=$row[17])
        {
          $i++;
          echo "<div class='result'><span class='icon' style='background-image:url(../AppStore/icon/".$row[9].");'></span>";
          echo "<p class='name'>".$row[1]."</p><p class='author'>".$row[3]."</p><p class='description'>".$row[5]."</p>";
          if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
          {
            echo "<div class='appbutton sysprotected'>Default</div>";
          }
          else
          {
            echo "<div class='appbuttontwo update' onclick=\"installApp('".$row[0]."',this);\">Update</div>";
            echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[0]."',this);\">Uninstall</div>";
          }
          echo "</div>";
        }
      }
      if($i<=0)
      {
        echo "<div class='resultError'>No Updates are available.</div><div class='resultERRImage'></div>";
      }
    }
  }
?>
