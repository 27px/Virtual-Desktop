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
require_once("../config/root.php");
if(!@is_dir($dir))
{
  $dir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
}
require_once("../config/database.php");
?>
<html>
<head>
<title>App Store</title>
<style>
*
{
  margin:0;
  padding:0;
}
body
{
  background:#6b1b78 url("bg.jpg");
  background-size:cover;
  background-attachment:fixed;
  overflow:hidden;
  display:flex;
  flex-wrap:nowrap;
  flex-direction:column;
}
body::-webkit-scrollbar
{
  display:none;
}
div.s
{
  width:calc(100% - 20px);
  min-width:650px;
  height:65px;
  padding:10px;
  background-color:#6b1b78;
  overflow:hidden;
  box-shadow:0px 0px 10px 3px #000000;
  z-index:1000;
  position:
  top:0;
  left:0;
}
div.result,div.resultError
{
  width:calc(100% - 40px);
  min-width:600px;
  height:auto;
  min-height:100px;
  background-color:rgba(255,255,255,0.3);
  margin-top:5px;
  padding:10px;
  border:1px solid #FFFFFF;
  border-radius:5px;
  position:relative;
  box-shadow: 0px 0px 10px 1px #000000;
  margin-bottom:20px;
  margin-left:20px;
  overflow:hidden;
}
div.resultError
{
  font-weight:900;
  min-height:40px;
  font-size:30px;
  font-family:arial black,arial,sans-serif;
  text-align:center;
  background-color:rgba(255,0,0,0.08);
}
div.result span.icon
{
  width:100px;
  height:100px;
  display:inline-block;
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
div.result p.name
{
  width:48%;
  height:auto;
  display:inline-block;
  position:absolute;
  top:20px;
  left:125px;
}
div.result p.author
{
    width:48%;
    height:auto;
    display:inline-block;
}
div.result p.author,div.result p.description
{
  font-size:20px;
  word-wrap:break-word;
}
div.result p.description
{
  width:calc(100% - 390px);
  position:absolute;
  top:70px;
  left:125px;
}
div.result p.author
{
  text-align:right;
  float:right;
}
div.result p.name
{
  font-weight:900;
  font-size:30px;
}
div.result span.highlight
{
  color:#0000A0;
}
div.title
{
  color:#FFFFFF;
  font-family:sans-serif;
  font-weight:900;
  display:inline-block;
  float:left;
  font-size:20px;
  padding:20px;
  position:relative;
}
div.title::before,div.title::after
{
  color:rgba(255,255,255,0.9);
  font-family:serif;
  font-weight:normal;
  font-size:16px;
  display:block;
  margin-right:10px;
}
div.title::after
{
  content:"Apps";
  position:absolute;
  right:0;
  bottom:0;
}
div.title::before
{
  content:"Build";
  position:absolute;
  left:0;
  top:0;
}
div.searchcontainer
{
  width:400px;
  float:right;
  display:flex;
  position:relative;
  flex-wrap:nowrap;
  padding:5px;
  box-shadow:0px 0px 10px 1px rgba(255,255,255,0.3) inset;
  border-radius:200px;
  box-sizing:border-box;
  border:1px solid #808080;
  margin-right:0;
}
div.searchcontainer:hover
{
  border:1px solid #FFFFFF;
}
img.searchIcon
{
  height:50%;
  width:auto;
  position:absolute;
  top:50%;
  left:10;
  transform:translateY(-50%);
}
div.searchcontainer input[type="name"]
{
  font-size:20px;
  font-family:serif;
  height:50%;
  width:100%;
  color:#FFFFFF;
  background-color:transparent;
  border:none;
  padding-left:30px;
  padding-right:5px;
}
div.searchcontainer input[type="name"]::placeholder
{
  color:#C0C0C0;
}
div.searchcontainer input[type="name"]:focus,button:focus
{
  outline:none;
}
div.subcontainer
{
  height:100%;
  padding-top:10px;
}
input[type="submit"]
{
  font-size:20px;
  font-family:serif;
  height:50%;
  width:100%;
  display:block;
  border:1px solid #000000;
  border-radius:5px;
  margin-top:5px;
	background-color:rgba(255,255,255,0.4);
}
input[type="submit"]:hover
{
  background:linear-gradient(135deg,#6b1b78,#9c27b0,#6b1b78);
  border:1px solid #FFFFFF;
  color:#FFFFFF;
}
input[type="submit"]:focus
{
  outline:none;
}
div.buttoncontainer
{
  height:100%;
  width:150px;
  min-width:150px;
  margin-left:10px;
  display:inline-block;
  float:right;
}
div.bcontainer
{
  box-sizing:border-box;
  width:100%;
  height:calc(100% - 85px);
  overflow:hidden;
  display:flex;
  flex-wrap:nowrap;
  flex-direction:row;
}
div.sidemenu
{
  position:absolute;
  bottom:0;
  left:0;
  width:250px;
  min-width:150px;
  height:calc(100% - 85px);
  display:inline-block;
  background-color:rgba(255,255,255,0.6);
  box-shadow:0px 6px 10px 1px #000000;
  overflow-x:hidden;
  overflow-y:auto;
  z-index:1;
  user-select:none;
}
div.sidemenu::-webkit-scrollbar
{
  width:10px;
  height:10px;
  border:1px solid #6b1b78;
  background-color:rgba(107,27,120,0.5);
}
div.sidemenu::-webkit-scrollbar-thumb
{
  background-color:rgba(0,0,0,0.3);
  border-top:1px solid #000000;
  border-bottom:1px solid #000000;
}
div.sidemenu::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(255,255,255,0.5);
}
textarea::-webkit-scrollbar
{
  width:10px;
  height:10px;
  border:0.5px solid #00FF00;
  background-color:rgba(0,255,0,0.5);
}
textarea::-webkit-scrollbar-thumb
{
  background-color:rgba(0,128,0,0.5);
}
textarea::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(0,64,0,0.8);
}
div.menuitems
{
  padding:10px;
  border-bottom:1px solid #000000;
  background-color:rgba(255,255,255,0.1);
  transition:background-color 0.7s,box-shadow 0.7s;
  font-family:arial,sans-serif;
  color:#6b1b78;
}
div.menuitems:hover
{
  background-color:rgba(107,27,120,0.4);
  box-shadow:0px 0px 10px 1px rgba(107,27,120,0.7) inset;
  color:#000060;
}
div.resultantcontainer
{
  position:absolute;
  right:0;
  bottom:0;
  padding:15px;
  box-sizing:border-box;
  width:calc(100% - 250px);
  height:calc(100% - 85px);
  overflow:auto;
  background-color:rgba(255,255,255,0.4);
}
div.resultantcontainer::-webkit-scrollbar
{
  width:10px;
  height:10px;
  border:1px solid #6b1b78;
  background-color:rgba(107,27,120,0.5);
}
div.resultantcontainer::-webkit-scrollbar-thumb
{
  background-color:rgba(0,0,0,0.5);
}
div.resultantcontainer::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(255,255,255,0.5);
}
p.error,p.success,p.warning
{
  width:100%;
  height:35px;
  font-size:16px;
  padding-top:10px;
  padding-left:20px;
  padding:right:20px;
  text-align:justify;
  font-family:sans-serif;
  font-weight:900;
  position:absolute;
  border-bottom:2.5px solid #000000;
  border-top:2.5px solid #000000;
  animation:fade 2s normal 1.5s 1 forwards;
  overflow:hidden;
  z-index:100;
}
@keyframes fade
{
  to
  {
    transform:translateY(-100%);
  }
}
p.error
{
  background-color:#FF5050;
  color:#FFFFFF;
}
p.success
{
  background-color:#50FF50;
  color:#000000;
}
p.warning
{
  background-color:#FFFF00;
  color:#000000;
}
div.menutitle
{
  background-color:rgba(255,255,255,0.5);
  box-shadow:0px 0px 10px 2px #000000;
  padding:20px;
  text-align:center;
  font-size:20px;
  color:#6b1b78;
  font-family:arial black,arial,sans-serif;
  user-select:none;
}
div.Accountmenu div.profileicon
{
  width:80px;
  height:80px;
  border-radius:50%;
  border:1px solid #000000;
  position:relative;
  top:50%;
  left:50%;
  transform:translate(-50%,0%);
}
form.hiddenForm,form.hiddenForm *
{
  display:none;
}
div.category
{
  min-width:150px;
  display:inline-block;
  background:linear-gradient(135deg,transparent,transparent,transparent);
  border:3px solid #FFFFFF;
  color:#FFFFFF;
  padding:10px;
  margin-right:10px;
  margin-bottom:10px;
  text-align:center;
  user-select:none;
  font-family:arial black,arial,sans-serif;
  transition:1s background,1s border,1s color;
  letter-spacing:2.5px;
}
div.category:hover
{
  color:#000000;
  border:3px solid #000000;
  background:linear-gradient(135deg,#00C000,#68F068,#00C000);
}
div.appbutton
{
  width:100px;
  text-align:center;
  padding:5px;
  position:absolute;
  bottom:10px;
  right:10px;
  user-select:none;
}
div.appbuttontwo
{
  width:100px;
  text-align:center;
  padding:5px;
  position:absolute;
  bottom:10px;
  right:132.5px;
  user-select:none;
}
div.install
{
  background:linear-gradient(90deg,#00C000,#005000);
  border:2px solid #008000;
  transition: width 0.5s,background 0.5s ease-in;
  color:#FFFFFF;
  display:block;
}
div.install:hover
{
  background:linear-gradient(90deg,#00FF00,#00FF00);
  color:#000000;
}
div.update
{
  background:linear-gradient(90deg,#00C0C0,#005050);
  border:2px solid #008080;
  transition: width 0.5s,background 0.5s ease-in;
  color:#FFFFFF;
  display:block;
}
div.update:hover
{
  background:linear-gradient(90deg,#00FFFF,#00FFFF);
  color:#000000;
}
div.uninstall
{
  background:linear-gradient(90deg,#C00000,#500000);
  border:2px solid #800000;
  color:#FFFFFF;
  display:block;
}
div.uninstall:hover
{
  background:linear-gradient(90deg,#FF0000,#FF0000);
  filter:brightness(125%);
}
div.sysprotected
{
  background:linear-gradient(90deg,#C0C0C0,#505050);
  border:2px solid #808080;
  color:#000000;
  display:block;
}
<?php
if(isset($_SESSION['User']))
if($_SESSION['User']=="Manager")
{
?>
div.appbuttonthree
{
  width:100px;
  text-align:center;
  padding:5px;
  position:absolute;
  bottom:50px;
  right:10px;
  user-select:none;
}
div.appbuttonfour
{
  width:100px;
  text-align:center;
  padding:5px;
  position:absolute;
  bottom:50px;
  right:132.5px;
  user-select:none;
}
div.approve
{
  background:linear-gradient(90deg,#C000C0,#500050);
  border:2px solid #800080;
  transition: width 0.5s,background 0.5s ease-in;
  color:#FFFFFF;
  display:block;
}
div.approve:hover
{
  background:linear-gradient(90deg,#FF00FF,#FF00FF);
  color:#000000;
}
div.reject
{
  background:linear-gradient(90deg,#303030,#A0A0A0);
  border:2px solid #606060;
  transition: width 0.5s,background 0.5s ease-in;
  color:#000000;
  display:block;
}
div.reject:hover
{
  background:linear-gradient(90deg,#000000,#000000);
  color:#FFFFFF;
}
div.files
{
  background:linear-gradient(90deg,#C0C000,#505000);
  border:2px solid #808080;
  transition: width 0.5s,background 0.5s ease-in;
  color:#000000;
  display:block;
}
div.files:hover
{
  background:linear-gradient(90deg,#FFFF00,#FFFF00);
  color:#000000;
}
<?php

}
?>
table.full
{
  padding:30px;
  width:100%;
  background-color:rgba(107,27,120,0.3);
  margin-bottom:15px;
  border-radius:15px;
}
table.full tr td:nth-child(1)
{
  font-family:arial black,arial,sans-serif;
}
table.full tr td:nth-child(2)
{
  text-align:center;
  font-family:arial black,arial,sans-serif;
}
table.full tr td:nth-child(3),table.full tr td:nth-child(3) *
{
  font-family:serif;
}
table.full tr td,table.full tr td *
{
  font-size:18px;
  user-select:none;
}
table.full input[type="name"],table.full input[type="file"],table.full textarea,table.full input[type="number"]
{
  width:100%;
  background-color:transparent;
  border:1px solid #000000;
  padding:10px;
}
table.full input[type="name"]:focus,table.full input[type="file"]:focus,table.full textarea:focus,table.full input[type="number"]:focus
{
  outline:none;
}
table.full input[type="name"]::placeholder,table.full textarea::placeholder,table.full input[type="number"]::placeholder
{
  color:#707070;
}
form.build
{
  position:relative;
  width:100%;
}
table.full
{
  position:absolute;
  top:0;
  left:50%;
  transform:translateX(-50%);
}
table.full tr td
{
  padding:10px;
}
table.full textarea
{
  resize:vertical;
  max-height:200px;
  height:90px;
  min-height:45px;
}
button.yellowbutton
{
  background:linear-gradient(90deg,#C0C000,#505000);
  width:150px;
  border:2px solid #606000;
  transition: width 0.5s,background: 0.5s ease-in;
  color:#FFFFFF;
  display:inline-block;
  padding:5px;
  margin-left:15px;
}
button.yellowbutton:hover
{
  background:linear-gradient(90deg,#505000,#C0C000);
  color:#000000;
}
button.greenbutton
{
  background:linear-gradient(90deg,#00C000,#005000);
  width:150px;
  border:2px solid #008000;
  transition: width 0.5s,background: 0.5s ease-in;
  color:#FFFFFF;
  display:inline-block;
  padding:5px;
  margin-left:15px;
}
button.greenbutton:hover
{
  background:linear-gradient(90deg,#005000,#00C000);
  color:#000000;
}
button.redbutton
{
  background:linear-gradient(90deg,#C00000,#500000);
  width:150px;
  border:2px solid #800000;
  color:#FFFFFF;
  transition: width 1s,background: 1s ease-in;
  display:inline-block;
  padding:5px;
  margin-left:15px;
}
button.redbutton:hover
{
  background:linear-gradient(90deg,#500000,#C00000);
  filter:brightness(125%);
}
div.popupbg
{
	width:100vw;
	height:100vh;
	background-color:rgba(0,0,0,0.6);
	position:fixed;
	top:0;
	left:0;
	z-index:30;
	display:none;
}
div.popup,div.popup *
{
  user-select:none;
}
div.popup
{
	width:100vmin;
	height:55vmin;
	background-color:#80FF80;
	position:fixed;
	top:50%;
	left:50%;
	transform:translate(-50%,-50%);
	z-index:35;
	overflow:visible;
	display:none;
}
div.popup *
{
	background-color:#80FF80;
}
div.popup div.topbox
{
	position:relative;
	top:0;
	right:0;
	width:100%;
	height:auto;
	background-color:#00C000;
	box-shadow:0px 0px 30px #000000;
	z-index:40;
}
div.popup legend
{
	font-weight:bold;
}
div.popup div.topbox button.close
{
	float:right;
	height:20px;
	width:20px;
	margin:15px;
	border:none;
	outline:none;
	background-color:transparent;
  background-image:url("../Desktop/icon/icon_close.svg");
}
div.popup div.topbox button.close:hover
{
  filter:brightness(140%);
}
div.popup div.topbox>span.title
{
	height:20px;
	margin:5px;
	padding:10px;
	display:inline-block;
  font-family:arial black,arial,sans-serif;
	font-weight:900;
	background-color:transparent;
}
div.popup div.bottombox
{
	position:relative;
	top:0;
	left:0;
	width:auto;
	height:auto;
	box-shadow:0px 0px 30px #000000;
	padding:10px;
	z-index:40;
}
div.popup div.bottombox center.cbutton button
{
	width:95%;
	margin:5px;
	padding:5px;
	display:inline-block;
}
div.popup div.content
{
	box-sizing:border-box;
	position:relative;
	top:0;
	left:0;
	width:auto;
	height:75%;
	padding:10px;
	overflow:auto;
	z-index:37;
}
div.popup div.content::-webkit-scrollbar
{
  width:10px;
  background-color:rgba(0,0,0,0.2);
}
div.popup div.content::-webkit-scrollbar-thumb
{
  background-color:rgba(0,0,0,0.2);
}
div.popup div.content::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(0,0,0,0.4);
}
div.popup div.content fieldset
{
	padding:15px;
	display:block;
	border:2px solid #008000;
}
div.popup table
{
	width:100%;
}
div.popup table td
{
	padding:5px;
}
div.popup .xtable td:nth-child(1)
{
  width:100px !important;
}
div.popup .xtable td:last-child
{
  word-wrap:break-word;
}
td.must::after
{
  content:"*";
  color:#FF0000;
}
.fr
{
  float:right;
}
.wp::placeholder
{
  color:#D0D0D0 !important;
}
.appNameStatus
{
  display:inline-block;
}
.appNameStatus,.appNameStatus span
{
  font-family:serif;
  font-size:25px !important;
  font-weight:normal;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function setMessage(type,message)
{
  var x=document.createElement("P");
  x.setAttribute("class",type);
  x.innerHTML=message;
  var b=document.body;
  b.insertBefore(x,b.firstChild);
}
function parseLatestApps()
{
  _("latestApps").value="true";
  _("menuForm").submit();
}
function parseCategories()
{
  _("getcategory").value="true";
  _("menuForm").submit();
}
function getAppsByCategory(cat)
{
  _("getcategory").value=cat;
  _("menuForm").submit();
}
function installApp(app)
{
  _("install").value=app;
  _("menuForm").submit();
}
function uninstallApp(app)
{
  _("uninstall").value=app;
  _("menuForm").submit();
}
function showInstalledApps()
{
  _("myApps").value="true";
  _("menuForm").submit();
}
function byte_unit_convert(b)
{
  var Size="";
  var byteA=Array(" B"," KB"," MB"," GB"," TB"," PB"," EB"," ZB"," YB");
  var bi=0;
  if(b<1024)
  Size=b;
  while(b>=1024 && bi<8)
  {
    bi++;
    b/=1024;
    Size=b;
  }
  Size=Math.floor(Size*100)/100;
  if(bi<9)
  {
    Size+=byteA[bi];
  }
  return Size;
}
function registerApp()
{
  _("regApp").value="true";
  _("menuForm").submit();
}
function openUploadPopup()
{
  _("uploadpopup").style.display="block";
  _("uploadpopupbg").style.display="block";
}
function closeUploadPopup()
{
  _("uploadpopup").style.display="none";
  _("uploadpopupbg").style.display="none";
  _("resetAppBuild").click();
}
function validateNewApp()
{
  var newName=_("appname").value;
  if(newName=="")
  {
    setMessage("error","Enter the App Name. . .");
    return;
  }
  var invalidChars=['\\','/',':','*','?','\"','<','>','|'];
  var n=invalidChars.length,i=0;
  while(i<n)
  {
    if(newName.indexOf(invalidChars[i])>-1)
    {
      setMessage("error","Invalid Character Found in the App Name. . .");
      return;
    }
    ++i;
  }
  if((_("description").value=="") || (_("keyword1").value=="") || (_("keyword2").value=="") || (_("keyword3").value==""))
  {
    setMessage("warning","Adding Description & Keyword helps users find your App easily.");
    setTimeout(function(){if(confirm("Are you Sure you want to leave these Fields Empty ?"))_("build").submit();else return;},3000);
  }
  else
  {
    _("build").submit();
  }
}
function avail(event)
{
  if(event.keyCode=="13")//Enter Submit Validation
  {
    event.preventDefault();
    checkAvailability();
  }
}
function checkAvailability()
{
  var z=_("appregname").value;
  if(z=="")
  {
    setMessage("error","Enter the App Name.");
    return;
  }
  var invalidChars=['\\','/',':','*','?','\"','<','>','|'];
  var n=invalidChars.length,i=0;
  while(i<n)
  {
    if(z.indexOf(invalidChars[i])>-1)
    {
      setMessage("error","Invalid Character Found in the App Name. . .");
      return;
    }
    ++i;
  }
  _("xbuild").submit();
}
function ownApps()
{
  _("getOwnApps").value="true";
  _("menuForm").submit();
}
function checkforUpdates()
{
  _("getUpdates").value="true";
  _("menuForm").submit();
}
function requestedApps()
{
  _("requestedApp").value="true";
  _("managerForm").submit();
}
function approvedApps()
{
  _("approvedApps").value="true";
  _("managerForm").submit();
}
function appsInDevelopment()
{
  _("appsInDevelopment").value="true";
  _("managerForm").submit();
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
    function getAppsInDirectory($p)
    {
      $files='';
      if($dh=@opendir($p))
      {
        while(($file=@readdir($dh))!=false)
        {
          if(($file=="." || $file=="..") || $file=="DesktopSettings.fcz" )
          {
            //Do Nothing
            continue;
          }
          else if(!@is_dir($p.$file))
          {
            if(end(explode(".",$file))=="fcz")
            {
              $files[]=$file;
            }
          }
        }
        @closedir($dh);
        if(is_array($files))
        {
          return $files;
        }
        else
        {
          return 0;
        }
      }
    }
    function displayForm($status,$v)
    {
      echo "<script>_(\"resultantcontainer\").innerHTML=\"<form id='xbuild' class='build' method='POST' action='".$_SERVER['PHP_SELF']."'><table class='full'><tr><td>App Name</td><td> : </td><td><input class='wp' type='name' name='appregname' id='appregname' required placeholder='App Name' autocomplete='off' value='".$v."' onkeypress='avail(event);'></td></tr><tr><td colspan='3'><div class='appNameStatus'>".$status."</div><button type='button' class='greenButton fr' onclick='checkAvailability();'>Check</button></td></tr></table></form>\";</script>";
    }
    function checkInstalledApp()
    {
      global $dir;
      global $inversion;
      $r[]=5;//File Manager // Already Installed
      $v[]=1;//File Manager // Version
      $apps=getAppsInDirectory($dir);
      if(!is_array($apps))
      {
        $inversion[]=$r;
        $inversion[]=$v;
        return $inversion;
      }
      $n=count($apps);
      $i=0;
      while($i<$n)
      {
        $file=$apps[$i];
        if(@file_exists($dir.$file))
        {
          $f=@fopen($dir.$file,"r");
          while(!@feof($f))
          {
            $fx=@fgets($f);
            $fxa=explode("\"",$fx);
            if($fxa[1]=="id" && $fxa[3]!="")
            {
              $r[]=$fxa[3];
            }
            else if($fxa[1]=="version" && $fxa[3]!="")
            {
              $v[]=$fxa[3];
            }
          }
          @fclose($f);
        }
        $i++;
      }
      $inversion[]=$r;
      $inversion[]=$v;
      return $inversion;
    }
    function installApp($id)
    {
      global $dir;
      global $conn;
      $result=$conn->query("SELECT * FROM Apps WHERE ID=".$id." LIMIT 1");
      if(mysqli_num_rows($result)>0)
      {
        if($row=mysqli_fetch_row($result))
        {
          if($row[4]!="Approved" && ($row[3]!=$_SESSION['Logged'] && $_SESSION['User']!="Manager"))
          {
            global $msgcount;
            $msgcount++;
            echo "<script>setTimeout(function(){setMessage(\"error\",\"Sorry this App is not Approved yet.\");},".($msgcount*1500).");</script>";
            return;
          }
          $t="Installed";
          if(@file_exists($dir.$row[1].".fcz"))
          {
            $t="Updated";
          }
          $f=@fopen($dir.$row[1].".fcz","w");
          $c="[\"type\"=\"Application\"]".PHP_EOL."[\"id\"=\"".$row[0]."\"]".PHP_EOL."[\"version\"=\"".$row[17]."\"]".PHP_EOL."[\"icon\"=\"".$row[9]."\"]".PHP_EOL."[\"title\"=\"".$row[1]."\"]".PHP_EOL."[\"width\"=\"".$row[11]."\"]".PHP_EOL."[\"height\"=\"".$row[12]."\"]".PHP_EOL."[\"min-width\"=\"".$row[13]."\"]".PHP_EOL."[\"min-height\"=\"".$row[14]."\"]".PHP_EOL."[\"max-width\"=\"".$row[15]."\"]".PHP_EOL."[\"max-height\"=\"".$row[16]."\"]";
          if(fwrite($f,$c))
          {
            global $msgcount;
            $msgcount++;
            echo "<script>setTimeout(function(){setMessage(\"success\",\"".$row[1]." ".$t.".\");},".($msgcount*1500).");</script>";
          }
          @fclose($f);
        }
      }
    }
    function uninstallApp($app)
    {
      global $dir;
      global $msgcount;
      if(@file_exists($dir.$app.".fcz"))
      {
        if(@unlink($dir.$app.".fcz"))
        {
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"success\",\"".$app." Uninstalled.\");},".($msgcount*1500).");</script>";
        }
        else
        {
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"error\",\"".$app." could'nt Uninstalled.\");},".($msgcount*1500).");</script>";
        }
      }
      else
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"error\",\"App not found.\");},".($msgcount*1500).");</script>";
      }
    }
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
                echo "<div class='appbuttontwo update' onclick=\"installApp('".$row[0]."');\">Update</div>";
                echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[1]."');\">Uninstall</div>";
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
                echo "<div class='appbuttontwo update' onclick=\"installApp('".$row[0]."');\">Update</div>";
              }
              echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[1]."');\">Uninstall</div>";
            }
            echo "</div>";
          }
        }
      }
    }
    checkInstalledApp();
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
    function app($row,$x=0)
    {
      global $inversion;
      echo "<div class='result'><span class='icon' style='background-image:url(../AppStore/icon/".$row[9].");'></span>";
      echo "<p class='name'>".$row[1]."</p><p class='author'>";
      if($x!=1)
      echo $row[3];//User Name
      else
      echo $row[4];//App Status
      echo "</p><p class='description'>".$row[5]."</p>";

      if(!in_array($row[0],$inversion[0]))
      {
        echo "<div class='appbutton install' onclick=\"installApp('".$row[0]."');\">Install</div>";
      }
      else if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
      {
        echo "<div class='appbutton sysprotected'>Default</div>";
      }
      else
      {
        if($inversion[1][array_search($row[0],$inversion[0])]!=$row[17])
        {
          echo "<div class='appbuttontwo update' onclick=\"installApp('".$row[0]."');\">Update</div>";
        }
        echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[1]."');\">Uninstall</div>";
      }
      echo "</div>";
    }
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
      if($_POST['latestApps']=="true")
      {
        $result=$conn->query("SELECT * FROM Apps WHERE Status='Approved' ORDER BY ID desc LIMIT 25");
        $n=mysqli_num_rows($result);
        if($n<=0)
        {
          echo "<div class='resultError'>Sorry No Apps In the Store.</div>";
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
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"error\",\"App Folder Creation Error : Contact Admin if this occurs again.\");},".($msgcount*1500).");</script>";
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
                $msgcount++;//Move Error
                echo "<script>setTimeout(function(){setMessage(\"error\",\"File Move Error on ".$name."\");},".($msgcount*1500).");</script>";
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
            $msgcount++;
            echo "<script>setTimeout(function(){setMessage(\"error\",\"File Upload Error on ".$uperror."\");},".($msgcount*1500).");</script>";
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
                    $msgcount++;
                    echo "<script>setTimeout(function(){setMessage(\"error\",\"".$db." Could not set.\");},".($msgcount*1500).");</script>";
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
            $msgcount++;//Move Error
            echo "<script>setTimeout(function(){setMessage(\"error\",\"Icon Move Error.\");},".($msgcount*1500).");</script>";
          }
          else
          {
            if($conn->query("UPDATE `Apps` SET `icon`='".$newIcon."' WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."'"))
            {
              $xc=$row[17];
              $xc++;
              $conn->query("UPDATE `Apps` SET `Version`='".$xc."' WHERE Name='".$aname."' AND User='".$_SESSION['Logged']."'");
              $msgcount++;
              echo "<script>setTimeout(function(){setMessage(\"success\",\"Icon set\");},".($msgcount*1500).");</script>";
            }
            else
            {
              $msgcount++;
              echo "<script>setTimeout(function(){setMessage(\"error\",\"Icon could'nt set\");},".($msgcount*1500).");</script>";
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
              echo "<div class='appbutton install' onclick=\"installApp('".$row[0]."');\">Install</div>";
            }
            else if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
            {
              echo "<div class='appbutton sysprotected'>Default</div>";
            }
            else
            {
              echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[1]."');\">Uninstall</div>";
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
              echo "<div class='appbutton install' onclick=\"installApp('".$row[0]."');\">Install</div>";
            }
            else if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
            {
              echo "<div class='appbutton sysprotected'>Default</div>";
            }
            else
            {
              echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[1]."');\">Uninstall</div>";
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
              echo "<div class='appbutton install' onclick=\"installApp('".$row[0]."');\">Install</div>";
            }
            else if(in_array($row[0],$inversion[0]) && $row[0]=="5")//File Manager System Protected
            {
              echo "<div class='appbutton sysprotected'>Default</div>";
            }
            else
            {
              echo "<div class='appbutton uninstall' onclick=\"uninstallApp('".$row[1]."');\">Uninstall</div>";
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
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"success\",\"App Rejected\");},".($msgcount*1500).");</script>";
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
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"error\",\"App could not Rejected.\");},".($msgcount*1500).");</script>";
        }
      }
    }
    if(isset($_POST['approveApp']))
    {
      if(!empty($_POST['approveApp']))
      if($conn->query("UPDATE `Apps` SET `Status`='Approved' WHERE `ID`='".$_POST['approveApp']."'")=="true")
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"success\",\"App Approved\");},".($msgcount*1500).");</script>";
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
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"error\",\"App could not Accepted.\");},".($msgcount*1500).");</script>";
      }
    }
  ?>
  </div>
</div>
<form class="hiddenForn" id="menuForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" id="latestApps" name="latestApps" value="">
  <input type="hidden" id="getcategory" name="getcategory" value="">
  <input type="hidden" id="install" name="install" value="">
  <input type="hidden" id="uninstall" name="uninstall" value="">
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
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"error\",\"App Name Exists\");},".($msgcount*1500).");</script>";
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
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"success\",\"App name Available\");},".($msgcount*1500).");</script>";
      if($conn->query("INSERT INTO `Apps`(`Name`,`User`) VALUES('".$xsname."','".$_SESSION['Logged']."')")==true)
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"success\",\"App name Reserved\");},".($msgcount*1500).");</script>";
      }
      else
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"error\",\"App name Reservation Error.\");},".($msgcount*1500).");</script>";
      }
    }
    else if($available==2)
    {
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"warning\",\"Your App is Opened for Editing\");},".($msgcount*1500).");</script>";
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
