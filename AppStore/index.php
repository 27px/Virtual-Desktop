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
        ajax("rejectApp.php?app="+app,function(){
          if(this.readyState==4 && this.status==200)
          {
            var x=JSON.parse(this.responseText);
            setMessage(x.type,x.message);
            _(x.app).parentNode.removeChild(_(x.app));
          }
        });
      }
      function approveApp(app)
      {
        ajax("approveApp.php?app="+app,function(){
          if(this.readyState==4 && this.status==200)
          {
            var x=JSON.parse(this.responseText);
            setMessage(x.type,x.message);
            _(x.app).parentNode.removeChild(_(x.app));
          }
        });
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
  <div class='s'>
    <div class='title'>App Store</div>
    <div class='subcontainer'>
    <div class='buttoncontainer'>
      <input type='submit' value='Search' name='Search' id='Search' onclick="search(_('Key').value);">
    </div>
      <div class='searchcontainer'>
        <img src='search_icon.svg' class='searchIcon'>
        <input type='name' placeholder='Search App' class='Key' id='Key' name='Key' autofocus autocomplete='off' onkeyup="keysearch(event,this.value);">
      </div>
    </div>
  </div>
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
    if(isset($_FILES['source']))
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
  ?>
  </div>
</div>
</body>
</html>
<?php
  $conn->close();
?>
