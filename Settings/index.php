<?php
ob_start();
session_start();
if(!(isset($_SESSION['Logged'])))
{
  header("Location:../Login/index.php");
}
else
{
  $dir=$_SESSION['Logged'];
  $msgcount=-1;
  $success="";
  $error="";
}
if(isset($_POST['logout']) && $_POST['logout']==1)
{
    require_once('../Includes/logout.php');
    header('Location:../Home/index.php');
}
$inversion="";
$ser="../User/Desktop/".$dir;
$dir=$_SERVER['DOCUMENT_ROOT'].parse_url($ser,PHP_URL_PATH)."/";
if(!@is_dir($dir))
{
  $dir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
}
$dir=str_replace("\\","/",$dir);
require_once("../config/database.php");
$conn=new mysqli($servername,$username,$password);
if(mysqli_connect_error())
{
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Connection Error : ".mysqli_connect_error()."\");},".($msgcount*1500).");</script>";
  die();
}
if(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE 'cloud'"))))
{
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Database not Found.\");},".($msgcount*1500).");</script>";
  die();
}
if(!($conn->query("USE cloud")==true))
{
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Couldn't change Database.\");},".($msgcount*1500).");</script>";
  die();
}
if(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE 'Apps'"))))
{
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Table not Found.\");},".($msgcount*1500).");</script>";
  die();
}
function duplicate($d)
{
  global $darray;
  global $karray;
  global $root;
  if($dh=opendir($d))
  {
    while(($file=readdir($dh))!=false)
    {
      if($file=="." || $file=="..")
      {
        continue;
      }
      else if(is_dir($d."/".$file))
      {
        duplicate($d."/".$file."/");
      }
      else
      {
        $root[]=$d;
        $darray[]="/".$file;
        $karray[]=sha1_file($d."/".$file);
      }
    }
    closedir($dh);
  }
}
function findDuplicate($dir)
{
  duplicate($dir);
  $i=0;
  $j=0;
  $x=0;
  $duplicate="";
  $dup=array();
  global $darray;
  global $karray;
  global $root;
  if(count($karray)>1)
  {
    while($i<count($karray))
    {
      if(array_key_exists($karray[$i],$dup))
      {
        $dup[$karray[$i]].=$root[$i].$darray[$i]."|";
      }
      else
      {
        $dup[$karray[$i]]=$root[$i].$darray[$i]."|";
      }
      $i++;
    }
    $r=array();
    foreach($dup as $key => $value)
    {
      $ax=explode("|",$value);
      if(count($ax)>2)
      {
        $r[]=$value;
      }
    }
    return $r;
  }
}
function abstorelpath($path)
{
  if($_SESSION['Logged']=="administrator@gmail.com")
  {
    return $path;
  }
  else
  {
    $path=str_replace("\\","/",$path);
    $path=str_replace("\\","/",$path);
    $path=str_replace("//","/",$path);
    $path=str_replace($_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'],"Root",$path);

    return $path;
  }
}
function setBackground($x)
{
  echo "<script>";
  echo "_('body').style.backgroundImage=\"url('".$x."')\";";
  echo "</script>";
}
function getTotal($x)
{
  $totalsize=0;
  if(is_dir($x))
  {
    if($dh=opendir($x))
    {
      while($file=readdir($dh))
      {
        if($file=="." || $file=="..")
        {
          continue;
        }
        if(is_dir($x."/".$file))
        {
          $totalsize+=getTotal($x."/".$file);
        }
        else
        {
          $size=filesize($x."/".$file);
          $totalsize+=$size;
        }
      }
    }
  }
  else if(file_exists($x))
  {
    $totalsize+=filesize($x);
  }
  return $totalsize;
}
function showNotification($x)
{
  echo "<div class='notification'>".$x."</div>";
}
function setArrayOfFiles($x)
{
  global $sarray;
  if($dh=opendir($x))
  {
    while($file=readdir($dh))
    {
      if($file=="." || $file=="..")
      {
        continue;
      }
      if(is_dir($x."/".$file))
      {
        $sarray[$x."/".$file]=getTotal($x."/".$file);
      }
      else
      {
        $sarray[$x."/".$file]=filesize($x."/".$file);
      }
    }
  }
}
function byte_unit_convert($b)
{
  $Size="";
  $byteA=array(" B"," KB"," MB"," GB"," TB"," PB"," EB"," ZB"," YB");
  $bi=0;
  if($b<1024)
  $Size=$b;
  while($b>=1024 && $bi<8)
  {
    $bi++;
    $b/=1024;
    $Size=$b;
  }
  $Size=floor($Size*100)/100;
  if($bi<9)
  {
    $Size.=$byteA[$bi];
  }
  return $Size;
}
function getDesktopSettings()
{
  global $dir;
  $file="DesktopSettings.fcz";
  $verifyType=0;
  $r="";
  if(@file_exists($dir."/".$file))
  {
    $f=@fopen($dir."/".$file,"r");
    while(!@feof($f))
    {
      $fx=@fgets($f);
      $fxa=explode("\"",$fx);
      if($fxa[1]=="type" && $fxa[3]=="DesktopSettings")
      {
        $verifyType=1;
      }
      else if($verifyType!=0)
      {
        if($fxa[1]!="" && $fxa[3]!="")
        {
          $r[$fxa[1]]=$fxa[3];
        }
      }
    }
    @fclose($f);
  }
  return $r;
}
?>
<html>
<head>
  <link href="../Desktop/icon/loading.gif" rel="preload" as="image" />
<style>
*
{
  padding:0;
  margin:0;
  user-select:none;
}
body
{
  background:#00C0FF url("2.jpg");
  background-size:cover;
}
div.clockContainer
{
  width:222px;
  height:260px;
  display:inline-block;
  margin-top:30px;
  margin-left:30px;
  background-image:url('../Desktop/icon/loading.gif');
  background-size:150px;
  background-position:center 35%;
  background-repeat:no-repeat;
}
div.newclockContainer
{
  width:222px;
  height:260px;
  display:inline-block;
  margin-top:30px;
  margin-left:30px;
}
iframe.dispClock,div.newClock,div.disableclock
{
  width:222px;
  height:222px;
  border-bottom:none;
  border-top:1px solid #000000;
  border-left:1px solid #000000;
  border-right:1px solid #000000;
  box-sizing:border-box;
}
div.newClock,div.disableclock
{
  font-family:arial black,arial,sans-serif,serif;
  font-size:120px;
  text-align:center;
  padding-top:10%;
  -webkit-text-stroke:1px #000000;
}
div.newClock
{
  color:#308030;
}
div.newClock:hover
{
  color:#00FF00;
}
div.disableclock
{
  color:#A03030;
}
div.disableclock:hover
{
  color:#FF0000;
}
button.clk
{
  width:222px;
  height:40px;
}
button.selectClock
{
  background:linear-gradient(90deg,#00C000,#005000);
  border:2px solid #008000;
  color:#FFFFFF;
  font-family:serif;
  font-size:25px;
}
button.selectClock:hover
{
  border:2px solid #000000;
  background:linear-gradient(90deg,#00FF00,#00FF00);
  color:#000000;
}
button.disableClock
{
  background:linear-gradient(90deg,#C00000,#500000);
  border:2px solid #800000;
  color:#FFFFFF;
  font-family:serif;
  font-size:25px;
}
button.disableClock:hover
{
  border:2px solid #000000;
  background:linear-gradient(90deg,#FF0000,#FF0000);
}
div.sideMenu
{
  width:310px;
  height:100%;
  background-color:rgba(255,255,255,0.7);
  position:fixed;
  top:0;
  left:0;
  box-shadow:0px 0px 10px 0px #000000,0px 0px 20px 0px #000000;
  outline:1px solid #000000;
  z-index:1;
  overflow-x:hidden;
  overflow-y:auto;
}
div.centerContainer
{
  width:calc(100% - 310px);
  height:100%;
  background-color:rgba(255,255,255,0.4);
  padding-bottom:40px;
  box-sizing:border-box;
  position:fixed;
  top:0;
  right:0;
  overflow-y:auto;
  z-index:0;
}
div.centerContainer::-webkit-scrollbar,div.sideMenu::-webkit-scrollbar
{
  width:8px;
  height:8px;
  border:1px solid #6b1b78;
}
div.centerContainer::-webkit-scrollbar-thumb,div.sideMenu::-webkit-scrollbar-thumb
{
  background-color:rgba(0,200,255,0.8);
  border-top:1px solid #000000;
  border-bottom:1px solid #000000;
}
div.centerContainer::-webkit-scrollbar-thumb:hover,div.sideMenu::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(0,100,255,0.8);
}
div.menuTitle
{
  background-color:rgba(255,255,255,1);
  box-shadow:0px 0px 10px 0px #000000;
  border-bottom:1px solid #000000;
}
div.mtitle
{
  display:inline-block;
  vertical-align:middle;
  font-size:25px;
  font-family:arial black;
}
div.settings
{
  color:#b71540;
}
div.home
{
  color:#60A060;
}
div.widgets
{
  color:#FF6060;
}
div.big
{
  width:50px;
  height:50px;
  background-size:contain;
}
div.icon
{
  display:inline-block;
  vertical-align:middle;
  background-position:center;
  background-repeat:no-repeat;
  margin:10px;
}
div.rotate
{
  animation:rotate 5s linear infinite normal;
}
@keyframes rotate
{
  0%
  {
    transform:rotate(0deg);
  }
  100%
  {
    transform:rotate(360deg);
  }
}
div.swinger
{
  animation:swinger 1s linear infinite alternate;
}
@keyframes swinger
{
  0%
  {
    transform:rotate(0deg);
  }
  100%
  {
    transform:rotate(-90deg);
  }
}
div.small
{
  width:40px;
  height:40px;
  margin-left:15px;
  background-size:130%;
}
div.subTitle
{
  padding:10px;
  font-size:17px;
  display:inline-block;
  vertical-align:middle;
  font-family:arial;
}
div.rb
{
  border:1px solid #000000;
  border-radius:50%;
  box-shadow:0px 0px 5px 0px #000000;
  box-sizing:border-box;
}
div.subContainer
{
  border-bottom:1px solid #000000;
  transition:1.6s background-color;
}
div.subContainer:hover
{
  background-color:#00C0FF;
}
div.subContainer::before
{
  content:"";
  position:absolute;
  width:0px;
  height:60px;
  background-color:rgb(0,100,255);
  transition:width 0.5s;
}
div.subContainer:hover::before
{
  width:10px;
}
form.hiddenForm
{
  display:none;
}
@font-face
{
  font-family:digital;
  src:url("../Plugins/Font/digital-7.ttf");
}
div.digitalClock
{
  font-size:45px;
  color:#FFFFFF;
  padding:30px;
  text-align:center;
  min-width:290px;
  padding-left:10px;
  padding-right:10px;
  font-family:digital;
  letter-spacing:5px;
  background-color:rgba(0,0,0,0.8);
  text-transform:uppercase;
  display:inline-block;
  margin-top:30px;
  margin-left:30px;
}
div.red
{
  text-shadow:1px 1px 2.5px #FF0000,
              1px -1px 2.5px #FF0000,
              -1px 1px 2.5px #FF0000,
              -1px -1px 2.5px #FF0000;
  outline:3px solid #FF0000;
}
div.green
{
  text-shadow:1px 1px 2.5px #00FF00,
              1px -1px 2.5px #00FF00,
              -1px 1px 2.5px #00FF00,
              -1px -1px 2.5px #00FF00;
  outline:3px solid #00FF00;
}
div.blue
{
  text-shadow:1px 1px 2.5px #0000FF,
              1px -1px 2.5px #0000FF,
              -1px 1px 2.5px #0000FF,
              -1px -1px 2.5px #0000FF;
  outline:3px solid #0000FF;
}
div.cyan
{
  text-shadow:1px 1px 2.5px #00FFFF,
              1px -1px 2.5px #00FFFF,
              -1px 1px 2.5px #00FFFF,
              -1px -1px 2.5px #00FFFF;
  outline:3px solid #00FFFF;
}
div.yellow
{
  text-shadow:1px 1px 2.5px #FFFF00,
              1px -1px 2.5px #FFFF00,
              -1px 1px 2.5px #FFFF00,
              -1px -1px 2.5px #FFFF00;
  outline:3px solid #FFFF00;
}
div.magenta
{
  text-shadow:1px 1px 2.5px #FF00FF,
              1px -1px 2.5px #FF00FF,
              -1px 1px 2.5px #FF00FF,
              -1px -1px 2.5px #FF00FF;
  outline:3px solid #FF00FF;
}
div.white
{
  text-shadow:1px 1px 2.5px #FFFFFF,
              1px -1px 2.5px #FFFFFF,
              -1px 1px 2.5px #FFFFFF,
              -1px -1px 2.5px #FFFFFF;
  outline:3px solid #FFFFFF;
}
div.info
{
  color:#FFFFFF;
  padding:35px;
  font-size:23px;
  letter-spacing:2px;
  background-color:rgba(0,0,0,0.4);
  border-bottom:3px solid #000000;
}
div.loading
{
  position:fixed;
  top:0;
  left:0;
  width:100vw;
  height:100vh;
  display:none;
  background:rgba(0,0,0,0.5) url("../Desktop/icon/loading2.gif");
  background-position:60% center;
  background-repeat:no-repeat;
}
div.success
{
  color:#00FF00;
}
div.error
{
  color:#FF0000;
}
div.digitalClockDisabler
{
  width:305px;
  display:inline-block;
  padding-top:29px;
  padding-bottom:29px;
  font-size:30px;
  margin-left:32px;
  margin-top:30px;
  text-align:center;
  font-family:arial black,arial,sans-serif,serif;
  color:#FF0000;
  outline:5px solid #FF0000;
  transition:0.8s color,0.8s outline,0.8s box-shadow;
}
div.digitalClockDisabler:hover
{
  color:#FFFFFF;
  outline:5px solid #FFFFFF;
  -webkit-text-stroke:1px #000000;
  box-shadow:0px 0px 30px 10px #000000,0px 0px 10px 3px #000000 inset;
}
form.customClockForm
{
  padding:40px;
  margin:30px;
  min-height:200px;
  border-radius:20px;
  box-shadow:0px 0px 30px 10px #000000;
  margin-top:80px;
  min-width:550px
}
form.customClockForm button.le
{
  padding:8px 20px;
  border:3px solid #000080;
  background-color:rgba(255,255,255,0.3);
  color:#000080;
  font-family:arial black,arial,sans-serif,serif;
  font-size:18px;
  width:100%;
}
form.customClockForm button.le:hover
{
  cursor:pointer;
  background-color:rgba(0,0,0,0.5);
  color:#FFFFFF;
  border:3px solid #FFFFFF;
  position:relative;
}
table.cctable
{
  width:70%;
  text-align:center;
  min-height:100px;
  min-width:500px;
  position:relative;
  top:50%;
  left:50%;
  transform:translate(-50%,0%);
}
table.cctable .clockContainer
{
  margin:0 !important;
}
table.cctable iframe
{
  border:0 !important;
}
form.customClockForm input[type="color"]
{
  display:none;
}
table.cctable td:first-child
{
  width:200px;
}
table.cctable td:last-child
{
  text-align:right;
}
iframe.analyzeprogress
{
  width:500px;
  height:500px;
  border:none;
}
div.analyzeitem
{
  background-color:#C0C0C0;
  margin:10px 20px;
  position:relative;
  z-index:-1;
}
div.analyzeicon
{
  width:80px;
  height:80px;
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
  z-index:1;
  margin:10px;
}
div.analyzeprogressbar
{
  position:absolute;
  top:0;
  left:0;
  background:#50FF50 url("7.jpg");
  background-size:cover;
  background-attachment:fixed;
  height:100%;
  width:100%;
  box-sizing:border-box;
  z-index:0;
  background-blend-mode:hard-light;
}
div.analyzeitem table.pro
{
  font-weight:900;
}
table.pro
{
  width:100%;
  position:relative;
  text-align:center;
  border-collapse:collapse;
}
table.pro tr.eqrow td
{
  width:calc(100% / 6.5) !important;
  white-space:wrap;
}
div.sideprog
{
  display:inline-block;
  vertical-align:center;
  width:calc(500px - 50px);
  padding-top:200px;
  box-sizing:border-box;
  height:500px;
  position:absolute;
  top:0;
  right:0;
  text-align:center;
  font-size:25px;
  word-wrap:break-word;
  margin-right:50px;
}
div.sideprog span.bold
{
  font-family:arial black,arial,sans-serif,serif;
  font-weight:900;
}
div.notification
{
  width:calc(100% - 100px);
  height:auto;
  margin:50px;
  box-sizing:border-box;
  text-align:center;
  color:#00FF00;
  padding:50px;
  font-size:40px;
  background-color:rgba(0,0,0,1);
  border-top:2.5px solid #00FF00;
  border-bottom:5px solid #00FF00;
  position:relative;
  box-shadow: 0px 0px 10px 4px #000000;
}
div.resultError::after
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  bottom:-45px;
  left:30;
  border-top:20px solid #FF0000;
  border-bottom:20px solid transparent;
  border-left:20px solid transparent;
  border-right:20px solid transparent;
}
div.resultError::before
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  bottom:-50px;
  left:30;
  border-top:20px solid #000000;
  border-bottom:20px solid transparent;
  border-left:20px solid transparent;
  border-right:20px solid transparent;
  filter:blur(5px);
}
div.resultError
{
  width:calc(100% - 100px);
  height:auto;
  margin:50px;
  box-sizing:border-box;
  text-align:center;
  color:#FF0000;
  padding:50px;
  font-size:40px;
  background-color:rgba(0,0,0,1);
  border-top:2.5px solid #FF0000;
  border-bottom:5px solid #FF0000;
  position:relative;
  box-shadow: 0px 0px 10px 4px #000000;
}
div.notification::after
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  bottom:-45px;
  left:30;
  border-top:20px solid #00FF00;
  border-bottom:20px solid transparent;
  border-left:20px solid transparent;
  border-right:20px solid transparent;
}
div.notification::before
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  bottom:-50px;
  left:30;
  border-top:20px solid #000000;
  border-bottom:20px solid transparent;
  border-left:20px solid transparent;
  border-right:20px solid transparent;
  filter:blur(5px);
}
div.divider
{
  background:#00000080 url("1.jpg");
  background-size:cover;
  background-attachment:fixed;
  color:#FFFFFF;
  padding:10px 0px;
  box-sizing:border-box;
  text-align:center;
  font-size:30px;
  font-weight:900;
  margin:30px 0px;
  border-top:1px solid #FFFFFF;
  border-bottom:1px solid #FFFFFF;
  text-shadow:0px 0px 10px #000000;
  font-family:arial black,arial,sans-serif,serif;
}
div.dupcontainer
{
  background-color:#FFFFFF80;
  color:#000000;
  padding:10px 0px;
  box-sizing:border-box;
  text-align:center;
  margin:30px 0px;
  border-top:1px solid #000000;
  border-bottom:1px solid #000000;
  box-sizing:border-box;
}
div.dupitem
{
  margin:30px;
  box-sizing:border-box;
}
div.dupitem .pro .eqrow td
{
  width:calc(100% / 5) !important;
  white-space:wrap;
}
div.cube
{
  width:100px;
  height:100px;
  position:relative;
  top:50%;
  left:50%;
  transform-style:preserve-3d;
  transform-origin:center;
  transform:translate(-50%,-50%) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
  animation:roll 5s linear infinite normal;
}
div.face
{
  width:100px;
  height:100px;
  outline:1px solid #000000;
  position:absolute;
  transform-style:preserve-3d;
}
div.front
{
  background-color:hsla(0deg,100%,50%,0.5);
  top:0;
  left:0;
}
div.back
{
  background-color:hsla(300deg,100%,50%,0.5);
  top:0;
  left:0;
  transform:translateZ(-100px);
}
div.left
{
  background-color:hsla(60deg,100%,50%,0.5);
  top:0;
  left:0;
  transform-origin:left;
  transform:rotateY(90deg);
}
div.right
{
  background-color:hsla(120deg,100%,50%,0.5);
  top:0;
  right:0;
  transform-origin:right;
  transform:rotateY(-90deg);
}
div.top
{
  background-color:hsla(180deg,100%,50%,0.5);
  top:0;
  right:0;
  transform-origin:top;
  transform:rotateX(-90deg);
}
div.bottom
{
  background-color:hsla(240deg,100%,50%,0.5);
  bottom:0;
  left:0;
  transform-origin:bottom;
  transform:rotateX(90deg);
}
@keyframes roll
{
  0%
  {
    transform:translate(-50%,-50%) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
  }
  100%
  {
    transform:translate(-50%,-50%) rotateX(360deg) rotateY(360deg) rotateZ(360deg);
  }
}
div.nothingselected
{
  width:100%;
  text-align:center;
  position:relative;
  top:80%;
  left:50%;
  font-size:20px;
  font-weight:900;
  color:#00000080;
  font-family:arial black,arial,sans-serif,serif;
  transform:translate(-50%,-50%);
  animation:jump 1s linear infinite normal;
  opacity:1;
}
@keyframes jump
{
  0%
  {
    top:80%;
    opacity:1;
  }
  100%
  {
    top:70%;
    opacity:0;
  }
}
div.profileviewer div.profilepicture
{
  width:300px;
  height:300px;
  background-position:center;
  background-size:80%;
  border-radius:50%;
  border:2px solid #000000;
  position:absolute;
  box-shadow:0px 0px 15px 5px #000000;
  top:10px;
  left:50%;
  transform:translate(-50%,0%);
  transition:box-shadow 0.5s;
}
div.profileviewer div.profilepicture:hover
{
  box-shadow:0px 0px 10px 2px #000000;
}
div.profileviewer div.email
{
  display:inline-block;
  position:absolute;
  top:330px;
  left:50%;
  transform:translate(-50%,0);
  padding:10px;
  text-align:center;
  text-transform:lowercase;
  font-weight:900;
  font-size:30px;
  font-family:arial black,arial,sans-serif,serif;
  border:3px solid #000000;
  transition:box-shadow 0.5s,padding 0.5s;
}
div.profileviewer div.email:hover
{
  box-shadow:0px 0px 15px 5px #000000;
  padding:20px;
}
div.profileviewer div.name
{
  width:50%;
  display:inline-block;
  position:absolute;
  top:440px;
  left:50%;
  transform:translate(-50%,0);
  text-align:center;
  font-weight:900;
  font-size:25px;
}
div.profileviewer div.name input::placeholder
{
  color:#FFFFFF80;
}
div.profileviewer div.name input::selection
{
  color:#00FFFF;
  background-color:rgba(0,0,0,0.5);
}
div.profileviewer div.name input:focus
{
  outline:none;
}
div.profileviewer div.password
{
  width:100%;
  min-width:200px;
  position:absolute;
  top:520px;
  left:50%;
  transform:translate(-50%,0);
}
div.profileviewer div.password input
{
  width:calc(30%);
  margin-left:calc(2.5%);
  text-align:center;
  box-sizing:border-box;
  background-color:transparent;
  font-size:22px;
  text-align:center;
  border:none;
  padding:10px;
  border-bottom:3px solid #000000;
}
div.profileviewer div.password input::selection
{
  color:#00FFFF;
  background-color:rgba(0,0,0,0.5);
}
div.profileviewer div.password input::placeholder
{
  color:#00000080;
}
div.profileviewer div.password input:focus
{
  outline:none;
}
div.profileviewer input[type='button']
{
  position:absolute;
  top:595px;
  left:50%;
  transform:translate(-50%,0);
  min-width:200px;
  padding:10px;
  background:linear-gradient(135deg,#00C000,#008000,#00C000);
  font-size:20px;
  font-weight:400;
  border:1px solid #008000;
  transition:background 0.5s;
}
div.profileviewer input[type='button']:hover
{
  background:linear-gradient(135deg,#00FF00,#00FF00,#00FF00);
}
div.profileviewer input[type='button']:focus
{
  outline:none;
}
div.xmessage
{
  color:#FF0000;
  background-color:#000000;
  padding:5px 20px;
  position:absolute;
  top:485px;
  left:50%;
  transform:translate(-50%,0);
  display:none;
  border-left:2px solid #FF0000;
  border-right:2px solid #FF0000;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function applyChangesToProfile()
{
  if(_("editcurrentpassword").value=="")
  {
    _("xmessage").style.display="inline-block";
    _("xmessage").innerHTML="Enter Current Password";
    return;
  }
  else if(_("editpassword").value=="")
  {
    _("xmessage").style.display="inline-block";
    _("xmessage").innerHTML="Enter New Password";
    return;
  }
  else if(_("editconfirmpassword").value=="")
  {
    _("xmessage").style.display="inline-block";
    _("xmessage").innerHTML="Confirm Password";
    return;
  }
  else if(_("editpassword").value!=_("editconfirmpassword").value)
  {
    _("xmessage").style.display="inline-block";
    _("xmessage").innerHTML="Enter Same Password";
    return;
  }
  else if(_("editpassword").value==_("editcurrentpassword").value)
  {
    _("xmessage").style.display="inline-block";
    _("xmessage").innerHTML="Don't use Same previous Password";
    return;
  }
  _("editprofile").submit();
}
function submitForm(x)
{
  _(x).value="true";
  _("sub").submit();
}
function selectDigitalClock(x)
{
  _("loading").style.display="block";
  _("dclockinfo").innerHTML="Loading . . .";
  _("dclockinfo").classList.remove("success");
  _("dclockinfo").classList.remove("error");
  _("dClock").value=x;
  _("sub").submit();
}
function selectAnalougeClock(x)
{
  _("loading").style.display="block";
  _("aClock").value=x;
  _("sub").submit();
}
function setPreview()
{
  var c="../Desktop/Widgets/Clock/Clock.php?";
  x=_("c1").value;
  x=x.replace("#","");
  c+="dark=%23"+x;
  x=_("c2").value;
  x=x.replace("#","");
  c+="&light=%23"+x;
  x=_("c3").value;
  x=x.replace("#","");
  c+="&needle=%23"+x;
  _("clockPreview").src=c;
}
function selectCustomAnalougeClock()
{
  selectAnalougeClock(_("c2").value+_("c1").value+_("c3").value);
}
</script>
</head>
<body id="body" oncontextmenu="return false;">
<div class="xc">
<div class="sideMenu">
  <div class="menuTitle settings">
    <div class="big icon rotate" style="background-image:url('../Desktop/icon/icon_settings.svg');"></div>
    <div class="mtitle">Settings</div>
  </div>
  <div class="subContainer" onclick="submitForm('notification');">
    <div class="rb small icon swinger" style="background-image:url('../Desktop/icon/icon_notification.svg');background-size:contain;"></div>
    <div class="subTitle">Notifications</div>
  </div>
  <div class="subContainer" onclick="submitForm('analyse');">
    <div class="rb small icon rotate" style="background-image:url('../Desktop/icon/icon_widgets.svg');background-size:90%;
    background-color:#E0E0FF;"></div>
    <div class="subTitle">Analyse Storage</div>
  </div>
  <div class="subContainer" onclick="submitForm('profile');">
    <div class="rb small icon" style="background-image:url('../Desktop/icon/nopic.jpg');background-size:100%;"></div>
    <div class="subTitle">Profile</div>
  </div>
  <div class="subContainer" onclick="window.location='../Home/index.php?logout=true';">
    <div class="rb small icon" style="background-image:url('../Desktop/icon/icon_logout.svg');background-size:90%;
    background-color:#FFE0E0;"></div>
    <div class="subTitle">Log Out</div>
  </div>
  <div class="menuTitle home"  onclick="window.location='../Home/index.php';">
    <div class="big icon" style="background-image:url('../Desktop/icon/home.gif');background-size:520%;"></div>
    <div class="mtitle">Home</div>
  </div>
  <div class="subContainer" onclick="window.location='../Desktop/index.php';">
    <div class="rb small icon" style="background-image:url('../Desktop/icon/icon_desktop.svg');;background-size:70%;"></div>
    <div class="subTitle">Desktop</div>
  </div>
  <div class="subContainer" onclick="window.location='../AppStore/index.php';">
    <div class="rb small icon" style="background-image:url('../Desktop/icon/icon_App_Store.svg');background-size:110%;"></div>
    <div class="subTitle">App Store</div>
  </div>
  <div class="menuTitle widgets">
    <div class="big icon" style="background-image:url('../Desktop/icon/widget.gif');"></div>
    <div class="mtitle">Widgets</div>
  </div>
  <div class="subContainer" onclick="submitForm('aClock');">
    <div class="rb small icon" style="background-image:url('../Desktop/icon/clock.jpg');"></div>
    <div class="subTitle">Analogue Clock</div>
  </div>
  <div class="subContainer" onclick="submitForm('dClock');">
    <div class="rb small icon" style="background-image:url('../Desktop/icon/digital.jpg');background-size:120%;"></div>
    <div class="subTitle">Digital Clock</div>
  </div>
</div>
<div class="centerContainer">
<?php
if(isset($_POST['profile']) && !empty($_POST['profile']))
{
  setBackground("1.jpg");
  global $conn;
  $result=$conn->query("SELECT * FROM login WHERE EMail='".$_SESSION['Logged']."' LIMIT 1");
  $n=mysqli_num_rows($result);
  if($n>0)
  {
    $row=mysqli_fetch_row($result);
    $zemail=$row[1];
    $profilepicture="../User/Profile/".$_SESSION['Logged'].".".$row[4];
    $zfirstname=$row[6];
    $zlastname=$row[7];
    echo "<div class='profileviewer'>";
    echo "<div class='profilepicture' style=\"background-image:url('".$profilepicture."');\"></div>";
    echo "<div class='email'>".$zemail."</div>";
    echo "<div class='name'>".$zfirstname."&nbsp".$zlastname."</div></div>";
  }
}
else if(isset($_POST['notification']) && !empty($_POST['notification']))
{
  setBackground("6.jpg");
  global $conn;
  $result=$conn->query("SELECT * FROM notification WHERE User='".$_SESSION['Logged']."'");
  $n=mysqli_num_rows($result);
  if($n<=0)
  {
    echo "<div class='resultError'>No Notifications</div>";
  }
  else
  {
    while($row=mysqli_fetch_row($result))
    {
      showNotification($row[2]);
    }
  }
}
else if(isset($_POST['analyse']) && !empty($_POST['analyse']))
{
  setBackground("5.jpg");
  $totalsize=getTotal($dir);
  setArrayOfFiles($dir);
  arsort($sarray);
  $i=0;
  $tp=(round(($totalsize/(150*1024*1024))*10000)/100);
  echo "<iframe class='analyzeprogress' src='../FileManager/CircularProgress.php?progress=".$tp."' oncontextmenu='return false;'></iframe>";
  echo "<div class='sideprog'><span class='bold'>".$tp."%</span> Used<br><span class='bold'>".byte_unit_convert((150*1024*1024)-$totalsize)."</span> Free Space,<br><span class='bold'>".byte_unit_convert($totalsize)."</span> used out of <span class='bold'>".byte_unit_convert(150*1024*1024)."</span></div>";
  echo "<div class='divider'>Storage Analysis</div>";
  foreach($sarray as $k=>$v)
  {
    $i++;
    $type="";
    $icon="../Desktop/icon/";
    if(basename($k)=="DesktopSettings.fcz")
    {
      continue;
    }
    else if(is_dir($k))
    {
      $type="Folder";
      $icon.="icon_folder.png";
    }
    else
    {
      $ext=end(explode(".",basename($k)));
      if($ext=="fcz")
      {
        $type="App";
        $icon.="icon_app_store.svg";
      }
      else
      {
        $type="File";
        $icon="../FileIcon/icongenerator.php?ext=".$ext."&case=upper&predefined=alpha";
      }
    }
    $percent=(round(($v/$totalsize)*10000)/100);
    echo "<div class='analyzeitem'>";
    echo "<div class='analyzeprogressbar' style='width:".$percent."%;'></div>";
    echo "<table class='pro' border=1>";
    echo "<tr>";
    echo "<td rowspan='2' style='width:100px;'><div class='analyzeicon' style=\"background-image:url('".$icon."');\"></div></td>";
    echo "<td colspan='6'>".abstorelpath($k)."</td>";
    echo "</tr><tr class='eqrow'>";
    echo "<td>".basename($k)."</td>";
    echo "<td>".$type."</td>";
    echo "<td>".date("h:i:s d/m/Y",filemtime($k))."</td>";
    echo "<td>".$v." B</div>";
    echo "<td>".byte_unit_convert($v)."</td>";
    echo "<td>".$percent." %</td>";
    echo "</tr>";
    echo "</table></div>";
  }
  echo "<div class='divider'>Duplicate Files</div>";
  $duplicateFiles=findDuplicate($dir);
  $nm=0;
  foreach($duplicateFiles as $value)
  {
    $nm++;
    echo "<div class='dupcontainer'>";
    $tmp=explode("|",$value);
    foreach($tmp as $v)
    {
      if($v!="")
      {
        $type="";
        $icon="../Desktop/icon/";
        if(basename($v)=="DesktopSettings.fcz")
        {
          continue;
        }
        else if(is_dir($v))
        {
          $type="Folder";
          $icon.="icon_folder.png";
        }
        else
        {
          $ext=end(explode(".",basename($v)));
          if($ext=="fcz")
          {
            $type="App";
            $icon.="icon_app_store.svg";
          }
          else
          {
            $type="File";
            $icon="../FileIcon/icongenerator.php?ext=".$ext."&case=upper&predefined=alpha";
          }
        }
        $tsz=getTotal($v);
        echo "<div class='dupitem'>";
        echo "<table class='pro' border=1>";
        echo "<tr>";
        echo "<td rowspan='2' style='width:100px;'><div class='analyzeicon' style=\"background-image:url('".$icon."');\"></div></td>";
        echo "<td colspan='6'>".abstorelpath($v)."</td>";
        echo "</tr><tr class='eqrow'>";
        echo "<td>".basename($v)."</td>";
        echo "<td>".$type."</td>";
        echo "<td>".date("h:i:s d/m/Y",filemtime($v))."</td>";
        echo "<td>".$tsz." B</div>";
        echo "<td>".byte_unit_convert($tsz)."</td>";
        echo "</tr>";
        echo "</table></div>";
      }
    }
    echo "</div>";
  }
  if($nm<=0)
  {
    echo "<div class='resulterror'>No Duplicate Files</div>";
  }
}
else if(isset($_POST['dClock']) && !empty($_POST['dClock']))
{
  setBackground("4.jpg");
  if($_POST['dClock']!="true" && $_POST['dClock']!="disable")
  {
    $rest=getDesktopSettings();
    $xi=0;
    $c="[\"type\"=\"DesktopSettings\"]";
    foreach($rest as $key => $value)
    {
      if($key=="widget-dClock")
      {
        $value=$_POST['dClock'];
        $xi++;
      }
      $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
    }
    if($xi<=0)
    {
      $c.=PHP_EOL."[\"widget-dClock\"=\"".$_POST['dClock']."\"]";
    }
    $f=@fopen($dir."/DesktopSettings.fcz","w");
    if(@fwrite($f,$c))
    {
      echo "<div class=\"info success\" id=\"dclockinfo\">Clock Set.</div>";
    }
    else
    {
      echo "<div class=\"info error\" id=\"dclockinfo\">Clock couldn't set.</div>";
    }
    @fclose($f);
  }
  else if($_POST['dClock']=="disable")
  {
    $rest=getDesktopSettings();
    $xi=0;
    $c="[\"type\"=\"DesktopSettings\"]";
    foreach($rest as $key => $value)
    {
      if($key=="widget-dClock")
      {
        $value='disabled';
        $xi++;
      }
      $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
    }
    if($xi<=0)
    {
      $c.=PHP_EOL."[\"widget-dClock\"=\"".$_POST['dClock']."\"]";
    }
    $f=@fopen($dir."/DesktopSettings.fcz","w");
    if(@fwrite($f,$c))
    {
      echo "<div class=\"info success\" id=\"dclockinfo\">Clock Disabled.</div>";
    }
    else
    {
      echo "<div class=\"info error\" id=\"dclockinfo\">Clock couldn't Disabled.</div>";
    }
    @fclose($f);
  }
  else
  echo "<div class=\"info\" id=\"dclockinfo\">Click to Select.</div>";
  ?>
  <div class="digitalClock red" id="digitalClock1" onclick="selectDigitalClock('red');">00:00:00 00</div>
  <div class="digitalClock green" id="digitalClock2" onclick="selectDigitalClock('green');">00:00:00 00</div>
  <div class="digitalClock blue" id="digitalClock3" onclick="selectDigitalClock('blue');">00:00:00 00</div>
  <div class="digitalClock cyan" id="digitalClock4" onclick="selectDigitalClock('cyan');">00:00:00 00</div>
  <div class="digitalClock magenta" id="digitalClock5" onclick="selectDigitalClock('magenta');">00:00:00 00</div>
  <div class="digitalClock yellow" id="digitalClock6" onclick="selectDigitalClock('yellow');">00:00:00 00</div>
  <div class="digitalClock white" id="digitalClock7" onclick="selectDigitalClock('white');">00:00:00 00</div>
  <div class="digitalClockDisabler" onclick="selectDigitalClock('disable');">Disable Clock</div>
  <script>
  var d = new Date(<?php echo time() * 1000 ?>);
  function digitalClock()
  {
    d.setTime(d.getTime() + 1000);
    var hrs = d.getHours();
    var mins = d.getMinutes();
    var secs = d.getSeconds();
    mins = (mins < 10 ? "0" : "") + mins;
    secs = (secs < 10 ? "0" : "") + secs;
    var apm = (hrs < 12) ? "AM" : "PM";
    hrs = (hrs > 12) ? hrs - 12 : hrs;
    hrs = (hrs == 0) ? 12 : hrs;
    var ctime = hrs + ":" + mins + ":" + secs + " " + apm;
    for(let i=1;i<8;i++)
    _("digitalClock"+i).innerHTML=ctime;
  }
  window.onload = function() {
    digitalClock();
    setInterval('digitalClock()', 1000);
  }
  </script>
  <?php
}
else if(isset($_POST['aClock']) && !empty($_POST['aClock']))
{
  setBackground("3.jpg");
  if($_POST['aClock']=="true")
  {
    $x="#FFFFFF";
    $r="#FFFFFF";
    $a[]="00";
    $a[]="80";
    $a[]="FF";
    $d="C0";
    function showClock($r,$x)
    {
      echo "\n<div class='clockContainer'>\n\t";
      echo "<iframe class='dispClock' src='../Desktop/Widgets/Clock/Clock.php?light=%23".$r."&dark=%23".$x."'></iframe>";
      echo "\n\t<button class='clk selectClock' type='submit' onclick=\"selectAnalougeClock('#".$r."#".$x."');\">Install</button>\n</div>\n";
    }
    for($i=0;$i<3;$i++)
    {
      for($j=0;$j<3;$j++)
      {
        for($k=0;$k<3;$k++)
        {
          if($i==1||$j==1||$k==1)
          {
            $x=$a[$i].$a[$j].$a[$k];
            $r=str_replace($a[1],$d,$x);
            showClock($r,$x);
            showClock($x,$r);
          }
        }
      }
    }
    $r="404040";
    $x="000000";
    showClock($r,$x);
    showClock($x,$r);
    showClock("40FF40","FF4040");
    echo "\n<div class='newclockContainer' onclick=\"selectAnalougeClock('custom');\">\n\t";
    echo "\n<div class='newclock'>+</div>";
    echo "\n\t<button class='clk selectClock' type='button' onclick=\"selectAnalougeClock('custom');\">Custom</button>\n</div>\n";

    echo "\n<div class='newclockContainer' onclick=\"selectAnalougeClock('disable');\">\n\t";
    echo "\n<div class='disableclock'>&times</div>";
    echo "\n\t<button class='clk disableClock' type='button' onclick=\"selectAnalougeClock('disable');\">Disable Clock</button>\n</div>\n";
  }
  else if($_POST['aClock']=="disable")
  {
      $rest=getDesktopSettings();
      $c="[\"type\"=\"DesktopSettings\"]";
      foreach($rest as $key => $value)
      {
        if($key=="widget-aClock")
        {
          $value="disabled";
        }
        $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
      }
      $f=@fopen($dir."/DesktopSettings.fcz","w");
      if(@fwrite($f,$c))
      {
        echo "<div class=\"info success\" id=\"dclockinfo\">Clock Disabled.</div>";
      }
      else
      {
        echo "<div class=\"info error\" id=\"dclockinfo\">Clock couldn't disabled.</div>";
      }
      @fclose($f);
  }
  else if($_POST['aClock']=="custom")
  {
    ?>
      <form class="customClockForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table class="cctable">
          <tr>
            <td><button type="button" class="le" onclick="_('c1').click();">Inner Color</button></td>
            <td rowspan="3">
              <?php
                $r="FFFF00";
                $x="000001";
                echo "\n<div class='clockContainer'>\n\t";
                echo "<iframe class='dispClock' id=\"clockPreview\" src='../Desktop/Widgets/Clock/Clock.php?light=%23".$x."&dark=%23".$r."&needle=%23000001'></iframe>";
                echo "\n\t<button class='clk selectClock' type='button' onclick=\"selectCustomAnalougeClock();\">Install</button>\n</div>\n";
              ?>
            </td>
          </tr>
          <tr>
            <td><button type="button" class="le" onclick="_('c2').click();">Outer Color</button></td>
          </tr>
          <tr>
            <td><button type="button" class="le" onclick="_('c3').click();">Needle Color</button></td>
          </tr>
        </table>
        <input type="color" name="c1" id="c1" value="#FFFF00" oninput="setPreview()"/>
        <input type="color" name="c2" id="c2" value="#000001" oninput="setPreview()"/>
        <input type="color" name="c3" id="c3" value="#000001" oninput="setPreview()"/>
      </form>
    <?php
  }
  else
  {
    $rest=getDesktopSettings();
    $xi=0;
    $c="[\"type\"=\"DesktopSettings\"]";
    foreach($rest as $key => $value)
    {
      if($key=="widget-aClock")
      {
        $value=$_POST['aClock'];
        $xi++;
      }
      $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
    }
    if($xi<=0)
    {
      $c.=PHP_EOL."[\"widget-aClock\"=\"".$_POST['aClock']."\"]";
    }
    $f=@fopen($dir."/DesktopSettings.fcz","w");
    if(@fwrite($f,$c))
    {
      echo "<div class=\"info success\" id=\"dclockinfo\">Clock Set.</div>";
    }
    else
    {
      echo "<div class=\"info error\" id=\"dclockinfo\">Clock couldn't set.</div>";
    }
    @fclose($f);
  }
}
else
{
  ?>
    <div class="cube" id="cube">
      <div class="face back"></div>
      <div class="face top"></div>
      <div class="face bottom"></div>
      <div class="face left"></div>
      <div class="face right"></div>
      <div class="face front"></div>
    </div>
    <div class='nothingselected'>Select an Option from Menu</div>
  <?php
}
?>
</div>
</div>
<form class="hiddenForm" id="sub" name="sub" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" id="notification" name="notification" value="" />
  <input type="hidden" id="profile" name="profile" value="" />
  <input type="hidden" id="analyse" name="analyse" value="" />
  <input type="hidden" id="aClock" name="aClock" value="" />
  <input type="hidden" id="dClock" name="dClock" value="" />
</form>
<div class="loading" id="loading">Loading</div>
</body>
</html>
