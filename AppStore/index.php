<?php
ob_start();
session_start();
if(!(isset($_SESSION['Logged'])))
{
  header("Location:../Login/Login.php");
}
else
{
  $dir=$_SESSION['Logged'];
  $msgcount=-1;
  $success="";
  $error="";
}
if(isset($_POST['logout']))
{
  if($_POST['logout']==1)
  {
    $_SESSION=array();//Clear all SESSION Variables
    session_destroy();
    header("Location:../Login/Login.php");
  }
}
$inversion="";
require_once("dir.php");
require_once("connect_db.php");
require_once("app.php");
?>
<html>
<head>
<title>App Store</title>
<link rel="stylesheet" type="text/css" href="style.min.css">
<script src="script.min.js"></script>
<script>
function _(id)
{
  return document.getElementById(id);
}
<?php
  if(isset($_SESSION['User']))
  if($_SESSION['User']=="Manager")
  {
    ?>
      function rejectApp(app)
      {
        _("rejectApp").value=app;
        _("managerForm").submit();
      }
      function approveApp(app)
      {
        _("approveApp").value=app;
        _("managerForm").submit();
      }
      function seeFiles(app)
      {
        window.open("../FileManager/files.php?url="+app);
      }
    <?php
  }
?>
</script>
</head>
<body oncontextmenu="return false">
  <?php
    require_once("putMessage.php");
    require_once("checkInstalledApp.php");
    checkInstalledApp();
  ?>
  <form acion="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

  <div class='s'>
    <div class='title'>App Store</div>
    <div class='subcontainer'>
    <div class='buttoncontainer'>
      <input type='submit' value='Search' name='Search' id='Search'>
    </div>
      <div class='searchcontainer'>
        <img src='search_icon.svg' class='searchIcon'>
        <input type='name' placeholder='Search App' class='Key' id='Key' name='Key' autofocus autocomplete='off' value='<?php if(isset($_POST['Search'])){echo $_POST['Key'];} ?>'>
      </div>
    </div>
  </div>
</form>
<div class="bcontainer">
  <div class="sidemenu">
    <div class="menutitle">Account</div>
    <div class="menuitems" onclick="window.location='../Home/index.php';">Home</div>
    <div class="menuitems" onclick="window.location='../Desktop/index.php';">Desktop</div>
    <div class="menuitems" onclick="window.location='../Settings/index.php';">Settings</div>
    <div class="menuitems" onclick="window.location='../Home/index.php?logout=true';">Logout</div>
    <div class="menutitle">Menu</div>
    <div class="menuitems" onclick="parseLatestApps();">Latest</div>
    <div class="menuitems" onclick="parseCategories();">Category</div>
    <div class="menuitems" onclick="showInstalledApps();">Installed Apps</div>
    <div class="menuitems" onclick="checkforUpdates();">Updates</div>
    <div class="menuitems" onclick="registerApp();">Build Apps</div>
    <div class="menuitems" onclick="ownApps();">My Apps</div>
    <?php
      if(isset($_SESSION['User']))
      if($_SESSION['User']=="Manager")
      {
        ?>
        <div class="menuitems" onclick="requestedApps();">App Requests</div>
        <div class="menuitems" onclick="approvedApps();">Approved Apps</div>
        <div class="menuitems" onclick="appsInDevelopment();">Apps in Development</div>
        <?php
      }
    ?>
  </div>
  <div class='resultantcontainer' id="resultantcontainer">
  <?php
    if(isset($_POST['Search']) && (isset($_POST['Key']) && !empty($_POST['Key'])))
    {
      $k=$_POST['Key'];
      if($k=="")
      {
        header("Location:".$_SERVER['PHP_SELF']);
      }
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
    else if(isset($_FILES['source']))
    {
      if(isset($_POST['appname']))
      {
        $appname=htmlspecialchars($_POST['appname']);
        $xsr=getcwd()."/Apps/";
        $d=$xsr;
        if(@is_dir($d.$appname))
        {
          //Folder Exists
        }
        else if(!@mkdir($d.$appname,0777))
        {
          putMessage("error","App Folder Creation Error : Contact Admin if this occurs again.");
        }
        if(!(count($_FILES["source"]["name"])<=1 && $_FILES["source"]["name"][0]==""))
        if(@is_dir($d.$appname))
        if(!empty($_FILES["source"]["name"]))
        {
          $num=count($_FILES["source"]["name"]);
          $i=0;
          $xm=0;
          $uperror=" :: ";
          $reapp=0;
          while($i<$num)
          {
            $name=$_FILES["source"]["name"][$i];
            $name=basename($name);
            $tmp=$_FILES["source"]["tmp_name"][$i];
            $size=$_FILES["source"]["size"][$i];
            $error=$_FILES["source"]["error"][$i];
            if($error!=0)
            {
              $uperror.=$name." : ";//Error
            }
            else
            {
              if(!move_uploaded_file($tmp,$d.$appname."/".$name)==true)
              {
                putMessage("error","File Move Error on ".$name);
              }
              else
              {
                $xm++;
                $reapp++;
              }
            }
            $i++;
          }
          if($xm>0)
          {
            $result=$conn->query("SELECT Version FROM Apps WHERE Name='".$appname."' AND User='".$_SESSION['Logged']."' LIMIT 1");
            $n=mysqli_num_rows($result);
            if($n>0)
            {
              if($row=mysqli_fetch_row($result))
              {
                $xc=$row[0];
                $xc++;
                $conn->query("UPDATE `Apps` SET `Version`='".$xc."' WHERE Name='".$appname."' AND User='".$_SESSION['Logged']."'");
              }
            }
          }
          if($reapp>0)
          {
            $result=$conn->query("SELECT Version FROM Apps WHERE Name='".$appname."' AND User='".$_SESSION['Logged']."' LIMIT 1");
            $n=mysqli_num_rows($result);
            if($n>0)
            {
              if($row=mysqli_fetch_row($result))
              {
                $conn->query("UPDATE `Apps` SET `Status`='Requested' WHERE Name='".$appname."' AND User='".$_SESSION['Logged']."'");
              }
            }
          }
          if($uperror!=" :: ")
          {
            putMessage("error","File Upload Error on ".$uperror);
          }
        }
      }
    }
    if(isset($_POST['appname']))
    {
      if(!empty($_POST['appname']))
      {
        $aname=$_POST['appname'];
        $up=0;
        $result=$conn->query("SELECT * FROM Apps WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."' LIMIT 1");
        $n=mysqli_num_rows($result);
        if($n>0)
        {
          if($row=mysqli_fetch_row($result))
          {
            $xv["Description"]="description";//5
            $xv["Keyword1"]="keyword1";//6
            $xv["Keyword2"]="keyword2";//7
            $xv["Keyword3"]="keyword3";//8
            $xv["Category"]="category";//10
            $xv["Width"]="width";//11
            $xv["Height"]="height";//12
            $xv["Min-Width"]="minwidth";//13
            $xv["Min-Height"]="minheight";//14
            $xv["Max-Width"]="maxwidth";//15
            $xv["Max-Height"]="maxheight";//16
            $xl=count($xv);
            $i=0;
            $xr[]=5;
            $xr[]=6;
            $xr[]=7;
            $xr[]=8;
            $xr[]=10;
            $xr[]=11;
            $xr[]=12;
            $xr[]=13;
            $xr[]=14;
            $xr[]=15;
            $xr[]=16;
            foreach($xv as $db=>$post)
            {
              if(isset($_POST[$post]) && !empty($_POST[$post]))
              {
                $d=htmlspecialchars($_POST[$post]);
                if($row[$xr[$i]]!=$d)
                {
                  if($conn->query("UPDATE `Apps` SET `".$db."`='".$d."' WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."'")==true)
                  {
                    if($i>4)
                    {
                      $up++;
                    }
                  }
                  else
                  {
                    putMessage("error",$db." Could not set.");
                  }
                }
              }
              $i++;
            }
            if($up>0)
            {
              $xc=$row[17];
              $xc++;
              $conn->query("UPDATE `Apps` SET `Version`='".$xc."' WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."'");
            }
          }
        }
      }
    }
    if(isset($_FILES["icon"]))
    if(!(count($_FILES["icon"]["name"])<=1 && $_FILES["icon"]["name"]==""))
    if(!empty($_FILES["icon"]["name"]))
    {
      $name=$_FILES["icon"]["name"];
      $name=basename($name);
      $tmp=$_FILES["icon"]["tmp_name"];
      $error=$_FILES["icon"]["error"];
      if($error==0)
      {
        $result=$conn->query("SELECT * FROM Apps WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."' LIMIT 1");
        $n=mysqli_num_rows($result);
        if($n>0)
        {
          $row=mysqli_fetch_row($result);
          $newIcon="icon_".$appname."_".$row[0].".".end(explode(".",$name));
          $xsr=getcwd()."/icon/";
          $d=$xsr;
          if(!move_uploaded_file($tmp,$d.$newIcon)==true)
          {
            putMessage("error","Icon Move Error.");
          }
          else
          {
            if($conn->query("UPDATE `Apps` SET `icon`='".$newIcon."' WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."'"))
            {
              $xc=$row[17];
              $xc++;
              $conn->query("UPDATE `Apps` SET `Version`='".$xc."' WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."'");
              putMessage("success","Icon set.");
            }
            else
            {
              putMessage("error","Icon could'nt set.");
            }
          }
        }
      }
    }
    if(isset($_POST['requestedApp']))
    {
      if($_POST['requestedApp']=="true")
      {
        $result=$conn->query("SELECT * FROM Apps WHERE Status='Requested'");
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
          echo "<div class='resultError'>No pending Requests.</div>";
        }
      }
    }
    if(isset($_POST['approvedApps']))
    {
      if($_POST['approvedApps']=="true")
      {
        $result=$conn->query("SELECT * FROM Apps WHERE Status='Approved'");
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
      }
    }
    if(isset($_POST['appsInDevelopment']))
    {
      if($_POST['appsInDevelopment']=="true")
      {
        $result=$conn->query("SELECT * FROM Apps WHERE Status='In Development'");
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
          echo "<div class='resultError'>No Apps in Development.</div>";
        }
      }
    }
    if(isset($_POST['rejectApp']))
    {
      if(!empty($_POST['rejectApp']))
      {
        if($conn->query("UPDATE `Apps` SET `Status`='Rejected' WHERE `ID`='".$_POST['rejectApp']."'")=="true")
        {
          putMessage("success","App Rejected.");
          $xuname="";
          $xapp="";
          $result=$conn->query("SELECT * FROM `Apps` WHERE `ID`='".$_POST['rejectApp']."' LIMIT 1");
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
          putMessage("error","App could not Rejected.");
        }
      }
    }
    if(isset($_POST['approveApp']))
    {
      if(!empty($_POST['approveApp']))
      if($conn->query("UPDATE `Apps` SET `Status`='Approved' WHERE `ID`='".$_POST['approveApp']."'")=="true")
      {
        putMessage("success","App Approved.");
        $xuname="";
        $xapp="";
        $result=$conn->query("SELECT * FROM `Apps` WHERE `ID`='".$_POST['approveApp']."' LIMIT 1");
        $n=mysqli_num_rows($result);
        $row=mysqli_fetch_row($result);
        $xuname=$row[3];
        $xapp=$row[1];
        if($n>0)
        {
          $conn->query("INSERT INTO `notification`(`User`,`Message`,`type`) VALUES('".$xuname."','Your App ".$xapp." was Approved, Check App Store for more details.','green')");
        }
      }
      else
      {
        putMessage("error","App could not Accepted.");
      }
    }
  ?>
  </div>
</div>
<!--Manager Form-->
<?php
  if(isset($_SESSION['User']))
  if($_SESSION['User']=="Manager")
  {
    ?>
    <!--Manager Form-->
    <form class="hiddenForn" id="managerForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" id="requestedApp" name="requestedApp" value="">
      <input type="hidden" id="approvedApps" name="approvedApps" value="">
      <input type="hidden" id="appsInDevelopment" name="appsInDevelopment" value="">
      <input type="hidden" id="rejectApp" name="rejectApp" value="">
      <input type="hidden" id="approveApp" name="approveApp" value="">
    </form>
    <!--Manager Form-->
    <?php
  }
?>
<!--Manager Form-->


<!--POPUP-->
</body>
</html>
<?php
  $conn->close();
?>
