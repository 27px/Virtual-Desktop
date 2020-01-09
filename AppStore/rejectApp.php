<?php
  function ajaxMessage($type,$message,$a)
  {
    echo "{\"type\":\"".$type."\",\"message\":\"".$message."\",\"app\":\"".$a."\"}";
  }
  if(isset($_GET['app']) && !empty($_GET['app']))
  {
    require_once("../config/connect_db.php");
    if($conn->query("UPDATE `Apps` SET `Status`='Rejected' WHERE `ID`='".$_GET['app']."'")=="true")
    {
      ajaxMessage("success","App Rejected.","apprequest_".$_GET['app']);
      $xuname="";
      $xapp="";
      $result=$conn->query("SELECT * FROM `Apps` WHERE `ID`='".$_GET['app']."' LIMIT 1");
      $n=mysqli_num_rows($result);
      $row=mysqli_fetch_row($result);
      $xuname=$row[3];
      $xapp=$row[1];
      if($n>0)
      {
        $conn->query("INSERT INTO `notification`(`User`,`Message`,`type`) VALUES('".$xuname."','Your App ".$xapp." got rejected, Check App Store for more details.','red')");
      }
    }
    else
    {
      ajaxMessage("error","App could not Rejected.","apprequest_".$_GET['app']);
    }
  }
?>
