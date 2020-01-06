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
require_once("../config/database.php");
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
    function putMessage($type,$message)
    {
      global $msgcount;
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"".$type."\",\"".$message."\");},".($msgcount*1500).");</script>";
    }
    require_once("getAppsInDirectory.php");
    function displayForm($status,$v)
    {
      echo "<script>_(\"resultantcontainer\").innerHTML=\"<form id='xbuild' class='build' method='POST' action='".$_SERVER['PHP_SELF']."'><table class='full'><tr><td>App Name</td><td> : </td><td><input class='wp' type='name' name='appregname' id='appregname' required placeholder='App Name' autocomplete='off' value='".$v."' onkeypress='avail(event);'></td></tr><tr><td colspan='3'><div class='appNameStatus'>".$status."</div><button type='button' class='greenButton fr' onclick='checkAvailability();'>Check</button></td></tr></table></form>\";</script>";
    }
    require_once("checkInstalledApp.php");
    function showUpdates()
    {
      global $conn;
      global $inversion;
      $x=count($inversion[0]);
      if($x<=0)
      {
        echo "<div class='resultError'>No Updates are available.</div>";
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
          echo "<div class='resultError'>No Updates are available.</div>";
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
            echo "<div class='resultError'>No Updates are available.</div>";
          }
        }
      }
    }
    function showMyApps()
    {
      global $conn;
      global $inversion;
      $x=count($inversion[0]);
      if($x<=0)
      {
        echo "<div class='resultError'>No Apps Installed.</div>";
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
          echo "<div class='resultError'>No Apps Installed.</div>";
        }
        else
        {
          while($row=mysqli_fetch_row($result))
          {
            echo "<div class='result'><span class='icon' style='background-image:url(../AppStore/icon/".$row[9].");'></span>";
            echo "<p class='name'>".$row[1]."</p><p class='author'>".$row[3]."</p><p class='description'>".$row[5]."</p>";
            if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
            {
              echo "<div class='appbutton sysprotected'>Default</div>";
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
        }
      }
    }
    checkInstalledApp();
    function appsByME()
    {
      global $conn;
      $result=$conn->query("SELECT * FROM Apps WHERE User='".$_SESSION['Logged']."'");
      $n=mysqli_num_rows($result);
      if($n<=0)
      {
        echo "<div class='resultError'>Sorry You haven't created any apps.</div>";
      }
      else
      {
        while($row=mysqli_fetch_row($result))
        {
          app($row,1);
        }
      }
    }
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
    else if(isset($_POST['latestApps']) && !empty($_POST['latestApps']))
    {
      if($_POST['latestApp']=="true")
      {
        require_once("latestApps.php");
      }
    }
    else if(isset($_POST['getcategory']) && !empty($_POST['getcategory']))
    {
      if($_POST['getcategory']=="true")
      {
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
      }
      else
      {
        $result=$conn->query("SELECT * FROM Apps WHERE Category LIKE '".$_POST['getcategory']."' AND Status='Approved' LIMIT 25");
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
    }
    else if(isset($_POST['install']) && !empty($_POST['install']))
    {
      installApp($_POST['install']);
    }
    else if(isset($_POST['uninstall']) && !empty($_POST['uninstall']))
    {
      uninstallApp($_POST['uninstall']);
    }
    else if(isset($_POST['myApps']) && !empty($_POST['myApps']))
    {
      showMyApps();
    }
    else if(isset($_POST['getOwnApps']) && $_POST['getOwnApps']=="true")
    {
      appsByME();
    }
    else if(isset($_POST['getUpdates']) && $_POST['getUpdates']=="true")
    {
      showUpdates();
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
    else if(isset($_POST['regApp']))
    {
      if($_POST['regApp']=="true")
      {
        displayForm("Enter App Name to check availability.","");
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
<form class="hiddenForn" id="menuForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" id="latestApps" name="latestApps" value="">
  <input type="hidden" id="getcategory" name="getcategory" value="">
  <input type="hidden" id="myApps" name="myApps" value="">
  <input type="hidden" id="regApp" name="regApp" value="">
  <input type="hidden" id="getOwnApps" name="getOwnApps" value="">
  <input type="hidden" id="getUpdates" name="getUpdates" value="">
</form>



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



<?php
if(isset($_POST['appregname']) && !empty($_POST['appregname']))
{
  $xsname=htmlspecialchars($_POST['appregname']);
  global $conn;
  $available=1;
  $result=$conn->query("SELECT * FROM Apps WHERE Name='".$xsname."' LIMIT 1");
  $n=mysqli_num_rows($result);
  if($n>0)
  {
    $available=0;
    $result=$conn->query("SELECT * FROM Apps WHERE Name='".$xsname."' AND User='".$_SESSION['Logged']."' LIMIT 1");
    $n=mysqli_num_rows($result);
    if($n<=0)
    {
      putMessage("error","App Name Exists.");
      displayForm("<span style='color:#D00000;'>".$xsname." already Exists</span>",$xsname);
      $available=0;
    }
    else
    {
      $available=2;
    }
  }
  if($available==1 || $available==2)
  {
    if($available==1)
    {
      putMessage("success","App Name Available.");
      if($conn->query("INSERT INTO `Apps`(`Name`,`User`) VALUES('".$xsname."','".$_SESSION['Logged']."')")==true)
      {
        putMessage("success","App Name Reserved.");
      }
      else
      {
        putMessage("error","App Name Reservation Error.");
      }
    }
    else if($available==2)
    {
      putMessage("warning","Your App is Opened for Editing.");
    }
    $description="";
    $keyword1="";
    $keyword2="";
    $keyword3="";
    $category="";
    $width="";
    $height="";
    $minwidth="";
    $minheight="";
    $maxwidth="";
    $maxheight="";
    if($available==2)
    {
      $row=mysqli_fetch_row($result);
      $description=$row[5];
      $keyword1=$row[6];
      $keyword2=$row[7];
      $keyword3=$row[8];
      $category=$row[10];
      $width=$row[11];
      $height=$row[12];
      $minwidth=$row[13];
      $minheight=$row[14];
      $maxwidth=$row[15];
      $maxheight=$row[16];
    }
  ?>
    <div class="popupbg" id="uploadpopupbg"></div>
    <div class="popup" id="uploadpopup">
      <div class="topbox">
        <span class="title">Build App</span>
        <button type="button" class="close closebutton" onclick="closeUploadPopup();"></button>
      </div>
    <div class="content" id="xspopcontent">
      <form id="build" class="build" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table class="full">
          <tr>
            <td>App Name</td>
            <td> : </td>
            <td><input type="name" name="appname" id="appname" required placeholder="App Name" value="<?php echo $xsname; ?>" readonly></td>
          </tr>
          <tr>
            <td>Source Files</td>
            <td> : </td>
            <td><input type="file" name="source[]" id="source" accept="text/*,image/*,inode/*" required multiple></td>
          </tr>
          <tr>
            <td>Description</td>
            <td> : </td>
            <td><textarea name="description" id="description" placeholder="Description"><?php echo $description; ?></textarea></td>
          </tr>
          <tr>
            <td>Keyword 1</td>
            <td> : </td>
            <td><input type="name" name="keyword1" id="keyword1" placeholder="Keyword 1" value="<?php echo $keyword1; ?>"></td>
          </tr>
          <tr>
            <td>Keyword 2</td>
            <td> : </td>
            <td><input type="name" name="keyword2" id="keyword2" placeholder="Keyword 2" value="<?php echo $keyword2; ?>"></td>
          </tr>
          <tr>
            <td>Keyword 3</td>
            <td> : </td>
            <td><input type="name" name="keyword3" id="keyword3" placeholder="Keyword 3" value="<?php echo $keyword3; ?>"></td>
          </tr>
          <tr>
            <td>Icon</td>
            <td> : </td>
            <td><input type="file" name="icon" id="icon" accept="image/*"></td>
          </tr>
          <tr>
            <td>Category</td>
            <td> : </td>
            <td><input type="name" name="category" id="category" placeholder="Category" value="<?php echo $category; ?>"></td>
          </tr>
          <tr>
            <td>Width</td>
            <td> : </td>
            <td><input type="number" name="width" id="width" placeholder="Width" value="<?php echo $width; ?>"></td>
          </tr>
          <tr>
            <td>Height</td>
            <td> : </td>
            <td><input type="number" name="height" id="height" placeholder="Height" value="<?php echo $height; ?>"></td>
          </tr>
          <tr>
            <td>Minimum Width</td>
            <td> : </td>
            <td><input type="number" name="minwidth" id="minwidth" placeholder="Minimum Width" value="<?php echo $minwidth; ?>"></td>
          </tr>
          <tr>
            <td>Minimum Height</td>
            <td> : </td>
            <td><input type="number" name="minheight" id="minheight" placeholder="Minimum Height" value="<?php echo $minheight; ?>"></td>
          </tr>
          <tr>
            <td>Maximum Width</td>
            <td> : </td>
            <td><input type="number" name="maxwidth" id="maxwidth" placeholder="Maximum Width" value="<?php echo $maxwidth; ?>"></td>
          </tr>
          <tr>
            <td>Maximum Height</td>
            <td> : </td>
            <td><input type="number" name="maxheight" id="maxheight" placeholder="Maximum Height" value="<?php echo $maxheight; ?>"></td>
          </tr>
        </table>
      </form>
    </div>
    <div class="bottombox" id="popupbottombox">
      <center>
        <button type="button" class="redButton" onclick="window.location='index.php';">Cancel</button>
        <button type="reset" class="yellowButton" id="resetAppBuild" form="build">Reset</button>
        <button type="button" class="greenButton" form="build" onclick="validateNewApp();"><?php if($available==2)echo "Save";else echo "Create";?></button>
      </center>
    </div>
<script>
  openUploadPopup();
</script>
<?php
  }
}
?>
<!--POPUP-->
</body>
</html>
<?php
  $conn->close();
?>
