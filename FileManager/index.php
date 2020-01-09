<?php
ob_start();
session_start();
if(!(isset($_SESSION['Logged'])))
{
  die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">You are not logged in . . .</div><div style=\"color:#FF5050;text-align:center;font-size:30px;padding-bottom:100px;\"><a style=\"color:#FF5050;\" href=\"../Login/index.php\">Click here to login.</a></div>");
}
else
{
  $msgcount=-1;
  $success="";
  $error="";
}
require_once("../Includes/functions.php");
$dir=$_SESSION['Logged'];
require_once("../config/root.php");
function getDirData($d)
{
  $files='';
  if($dh=@opendir($d))
  {
    while(($file=@readdir($dh))!=false)
    {
      if($file=="."||$file=="..")
      {
        continue;
      }
      else if(@is_dir($d."/".$file))
      {
        $files[]=$d."/".$file;
      }
    }
  }
  if($dh=@opendir($d))
  {
    while(($file=@readdir($dh))!=false)
    {
      if($file=="."||$file=="..")
      {
        continue;
      }
      else if(@is_dir($d."/".$file))
      {
        continue;
      }
      else
      {
        $e=end(explode(".",$file));
        if($e=="fcz")
        {
          continue;
        }
        $files[]=$d."/".$file;
      }
    }
  }
  return $files;
}
//Admin-Location
$userArray[]="Contacts";
$userArray[]="Desktop";
$userArray[]="Documents";
$userArray[]="My Documents";
$userArray[]="Downloads";
$userArray[]="Links";
$userArray[]="Music";
$userArray[]="My Music";
$userArray[]="Pictures";
$userArray[]="My Pictures";
$userArray[]="Videos";
$userArray[]="My Videos";
$userArray[]="Searches";
//Admin-Location
function getUserData($path)
{
  global $userArray;
  if($dh=@opendir($path))
  {
    while(($file=@readdir($dh))!=false)
    {
      if($file=="."||$file=="..")
      {
        continue;
      }
      else if(@is_dir($path."/".$file))
      {
        foreach($userArray as $v)
        {
          if(fnmatch($v,$file))
          {
            ?>
              <div class="directory">
                <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
                  <div class="xtitle" ondblclick="window.location='<?php echo "index.php?url=".$path."/".$file."/" ?>';">
                    <div class="st">&gt;</div>
                    <span class="dtitle"><?php echo $file; ?></span>
                  </div>
                  <div class="dcontents">
                    <?php
                      getContentsFromDirectory($path."/".$file."/");
                    ?>
                    <hr class="bb"/>
                  </div>
                </div>
              </div>
            <?php
          }
        }
      }
    }
    @closedir($dh);
  }
}
function getUsers()
{
  $path="C:\\\\Users/";
  if($dh=@opendir($path))
  {
    while(($file=@readdir($dh))!=false)
    {
      if($file=="."||$file=="..")
      {
        continue;
      }
      else if(@is_dir($path.$file))
      {
        ?>
          <div class="directory">
            <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
              <div class="xtitle" ondblclick="window.location='<?php echo "index.php?url=".$path.$file ?>';">
                <div class="st">&gt;</div>
                <span class="dtitle"><?php echo $file; ?></span>
              </div>
              <div class="dcontents">
                <?php
                  getUserData($path.$file);
                ?>
                <hr class="bb"/>
              </div>
            </div>
          </div>
        <?php
      }
    }
    @closedir($dh);
  }
}
function getDrives()
{
  foreach(range('A','Z') as $d)
  {
    $dir=$d.":\\\\";
    if(@is_dir($dir))
    {
      ?>
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle" ondblclick="window.location='<?php echo "index.php?url=".$d.":\\\\" ?>';">
              <div class="st">&gt;</div>
              <span class="dtitle"><?php echo $dir; ?></span>
            </div>
            <div class="dcontents">
              <?php
              if($dh=@opendir($dir))
              {
                while(($file=@readdir($dh))!=false)
                {
                  if($file=="."||$file=="..")
                  {
                    continue;
                  }
                  else if(@is_dir($dir."/".$file))
                  {
                    ?>
                    <div class="directory">
                      <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
                        <div class="xtitle" ondblclick="window.location='<?php echo "index.php?url=".$d.":\\\\" ?>';">
                          <div class="st">&gt;</div>
                          <span class="dtitle"><?php echo $file; ?></span>
                        </div>
                        <div class="dcontents">
                          <hr class="bb"/>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                }
              }
              ?>
              <hr class="bb"/>
            </div>
          </div>
        </div>
      <?php
    }
  }
}
function getContentsFromDirectory($d)
{
  $f=getDirData($d);
  if($f=='' || empty($f) || !is_array($f))
  {
    return;
  }
  $n=count($f);
  for($i=0;$i<$n;$i++)
  {
    $cx=$f[$i];
    $cx=str_replace("\\","/",$cx);
    $file=end(explode("/",$cx));
    if(@is_dir($d.$file))
    {
      ?>
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle" ondblclick="window.location='<?php echo "index.php?url=".$d.$file."/" ?>';">
              <div class="st">&gt;</div>
              <span class="dtitle"><?php echo $file; ?></span>
            </div>
            <div class="dcontents">
              <?php
                getContentsFromDirectory($d.$file."/");
              ?>
              <hr class="bb"/>
            </div>
          </div>
        </div>
      <?php
    }
    else
    {
      //File
      $ext=end(explode(".",$file));
      ?>
        <div class="directory" onclick="">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle">
              <div class="str"><?php echo strtoupper(end(explode(".",$file))); ?></div>
              <span class="xdtitle"><?php echo $file; ?></span>
            </div>
          </div>
        </div>
      <?php
    }
  }
}
function authorised($path,$log)
{
  if($log=="administrator@gmail.com")
  return true;
  $ph=str_replace("\\","/",$path);
  $p=explode("/",$ph);
  $i=0;
  $l=count($p);
  while($i<$l)
  {
    if($p[$i]==$log)
    {
      return true;
    }
    $i++;
  }
  return false;
}
if(isset($_POST['logout']) && $_POST['logout']==1)
{
  require_once('../Includes/logout.php');
  header('Location:../Home/index.php');
}
if(isset($_GET['url']) && !empty($_GET['url']))
{
  if(@is_dir($_GET['url']))
  {
    $dir=$_GET['url'];
  }
  else if(file_exists($_GET['url']))
  {
    $dir=dirname($_GET['url']);
  }
  else
  {
    die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Invalid Directory.</div>");
  }
}
else if(isset($_GET['selection']) && !empty($_GET['selection']))
{
  echo "Selection Mode";
  $selection="true";
  $dir=$_GET['selection'];
}
if(!@is_dir($dir))
{
  if(@file_exists($dir))
  {
    $dir=dirname($dir);
  }
  else
  {
    $dir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
  }
}
$xdir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
$ser=str_replace($_SERVER['DOCUMENT_ROOT'],(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'],$dir);
$u=urlbaraddress();
$currentDirectory="Computer";
if(!(isset($_GET['type']) && $_GET['type']=="myPC"))
{
  if(!($_SESSION['Logged']=="administrator@gmail.com"))
  {
    if($u=="/")
    {
      $currentDirectory="Root Folder";
    }
    else
    {
      $currentDirectory=explode("/",$u);
      $xz=count($currentDirectory)-2;
      $currentDirectory=$currentDirectory[$xz];
    }
  }
}
if(!@is_dir($dir) && !($_SESSION['Logged']=="administrator@gmail.com"))
{
  //Desktop Does not Exist , Create one.
  die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">No Folder Found</div>");
}
if(!authorised($dir,$_SESSION['Logged']))
{
  die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">You are not Authorised to access this Folder.</div>");
}
//Image Extensions
$ImgExt[]="jpg";
$ImgExt[]="jpeg";
$ImgExt[]="svg";
$ImgExt[]="png";
$ImgExt[]="gif";
$ImgExt[]="bmp";
$ImgExt[]="svgz";
$ImgExt[]="tiff";
$ImgExt[]="exif";
$ImgExt[]="ico";
//Image Extensions


//Accent[Green]
$Accent="#00FF00";
$AccentDark="#00C000";
$AccentLight="#80FF80";
$AccentBorder="#008000";
$NumText="#00A000";
//Accent



//Theme[Dark]
$ThemeBG="#000000";
$EditorBG="#202025";
$ThemeText="#E0E0E0";
$MenuText="#FFFFFF";
$MenuListBG="#505050";
$MenuListText="#E0E0E0";
//Additional Change
$Border=$Accent;
//Theme



function urlbaraddress()
{
  global $dir;
  if(($_SESSION['Logged']=="administrator@gmail.com"))
  {
    if(isset($_GET['type']) && !empty($_GET['type']))
    {
      return "";
    }
    else
    {
      $tmp=str_replace(":\\\\",":\\",$dir);
      $tmp=str_replace(":\\",":/",$tmp);
      $tmp=str_replace(":/",":\\\\\\\\",$tmp);
      if(($tmp[strlen($tmp)-1]!="/") && ($tmp[strlen($tmp)-1]!="\\"))
      {
        return $tmp."/";
      }
      return $tmp;
    }
  }
  else
  {
    return $dir;
  }
}
function dfvalue()
{
  if($_SESSION['Logged']=="administrator@gmail.com")
  {
    return urlbaraddress();
  }
  else
  {
    global $dir;
    $n=explode($_SESSION['Logged'],$dir);
    $r="";
    for($i=1;$i<count($n);$i++)
    {
      $r.=$n[$i];
    }
    return $r;
  }
}
?>
<html>
<head>
<title>File Manager</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<style>
*
{
  padding:0;
  margin:0;
}
body.desktop
{
  background:#006000 url("1.jpg");
  background-size:cover;
  overflow:hidden;
}
body.desktop,body.desktop *
{
  user-select:none !important;
}
div.rightMenu
{
  background-color:#E0E0E0;
  box-shadow:5px 5px 15px 0px rgba(0,0,0,0.6);
  display:none;
  border:1px solid #000000;
  min-width:150px;
  position:fixed;
  top:-100%;
  left:-100%;
  user-select:none;
  z-index:1000;
}
div.rightOptions
{
  padding-right:5px;
  min-height:30px;
  border-bottom:1px solid #FFFFFF;
  border-top:1px solid rgba(0,0,0,0.2);
  box-sizing:border-box;
}
div.rightMenu:hover,div.rightOptions:hover
{
  cursor:pointer;
}
div.rightOptions:hover
{
  background-color:#C0E0F0;
}
div.rightOptions span.shortcut
{
  padding:5px;
  float:right;
}
div.rightOptions span.icon
{
  float:left;
  padding:5px;
  border-right:1px solid rgba(0,0,0,0.1);
}
div.rightOptions span.title
{
  padding:5px;
  float:left;
  min-width:100px;
  font-size:15px;
  border-left:1px solid rgba(255,255,255,1);
}
div.rightOptions span.icon div.icon
{
  border:none;
  outline:none;
  height:20px;
  width:20px;
}
div.rightOptions span.icon div.icon_refresh
{
  background-image:url("../Desktop/icon/icon_refresh.svg");
  background-size:cover;
}
div.rightOptions span.icon div.icon_upload
{
  background-image:url("../Desktop/icon/icon_upload.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_download
{
  background-image:url("../Desktop/icon/icon_upload.svg");
  background-size:contain;
  background-repeat:no-repeat;
  transform:rotate(180deg);
}
div.rightOptions span.icon div.icon_folder
{
  background-image:url("../Desktop/icon/icon_folder.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_file
{
  background-image:url("../Desktop/icon/icon_file.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_paste
{
  background-image:url("../Desktop/icon/icon_paste.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_clipboard
{
  background-image:url("../Desktop/icon/icon_clipboard.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_profile
{
  background-image:url("../Desktop/icon/icon_profile.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_size
{
  background-image:url("../Desktop/icon/icon_size.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_widgets
{
  background-image:url("../Desktop/icon/icon_widgets.svg");
  background-size:contain;
  background-repeat:no-repeat;
  background-position:center;
}
div.rightOptions span.icon div.icon_settings
{
  background-image:url("../Desktop/icon/icon_settings.svg");
  background-size:contain;
  background-repeat:no-repeat;
  background-position:center;
}
div.rightOptions span.icon div.icon_cut
{
  background-image:url("../Desktop/icon/icon_cut.svg");
  background-size:contain;
  filter:contrast(500%);
  background-position:center;
  background-repeat:no-repeat;
}
div.icon_home
{
  background-image:url("../Desktop/icon/icon_home.svg");
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_open
{
  background-image:url("../Desktop/icon/icon_open.svg");
  background-size:80%;
  background-position:center;
  background-repeat:no-repeat;
  filter:contrast(500%);
}
div.rightOptions span.icon div.icon_delete
{
  background-image:url("../Desktop/icon/icon_delete.svg");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_rename
{
  background-image:url("../Desktop/icon/icon_rename.png");
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_properties
{
  background-image:url("../Desktop/icon/icon_properties.png");
  background-size:contain;
  background-repeat:no-repeat;
}
div.rightOptions span.icon div.icon_openwith
{
  background-image:url("../Desktop/icon/icon_openwith.svg");
  background-size:contain;
  background-repeat:no-repeat;
  background-position:center;
}
div.rightOptions span.icon div.icon_copy
{
  background-image:url("../Desktop/icon/icon_copy.svg");
  background-size:contain;
  background-repeat:no-repeat;
  background-position:center;
}
div.deskcontainer
{
  width:100%;
  height:100%;
  overflow:hidden;
  display:flex;
  flex-direction:column;
  flex-wrap:nowrap;
}
div.topbar
{
  width:100%;
  height:45px;
  min-height:45px !important;
  background-color:rgba(255,255,255,0.4);
  color:#FFFFFF;
  box-shadow:0px 0px 15px 1px #000000;
  transition:background-position 2s;
  background-position:bottom;
  overflow-x:auto;
  overflow-y:hidden;
}
div.topbar:hover
{
  background:url(1.jpg),rgba(255,255,255,0.1);
  background-blend-mode:lighten;
  background-position:center;
}
div.topbar div.title
{
  height:100%;
  font-size:30px;
  padding:5px;
  text-shadow:0px 0px 7px #000000;
  display:none;
  float:left;
  user-select:none;
}
div.topbar div.icon
{
  width:30px;
  height:30px;
  margin:7px;
  display:inline-block;
  float:left;
}
div.topbar div.icon_desktop
{
  background-image:url("icon/icon_desktop.svg");
  background-size:cover;
}
div.topbar div.icon_cloud
{
  background-image:url("icon/icon_cloud.svg");
  background-size:cover;
}
div.topbar div.icon_logout
{
  background-image:url("icon/icon_logout.svg");
  background-size:cover;
  background-position:center;
}
div.topbar div.icon_settings
{
  background-image:url("icon/icon_settings.svg");
  background-size:cover;
}
div.topbar div.h
{
  width:auto;
  height:100%;
  display:inline-block;
  padding-right:0px;
  transition:background-color 1s;
}
div.hl
{
  float:left;
  border-right:1px solid #000000;
}
div.hr
{
  float:right;
  border-left:1px solid #000000;
}
div.topbar div.h:hover
{
  background-color:rgba(0,0,0,0.3);
  cursor:pointer;
}
div.desk
{
  width:calc(100% - 240px);
  height:100%;
  display:flex;
  flex-wrap:wrap;
  flex-direction:row;
  align-content:flex-start;
  overflow-x:hidden;
  overflow-y:auto;
  padding-bottom:30px;
  box-sizing:border-box;
}
div.desk::-webkit-scrollbar
{
  height:5px;
  width:5px;
  background-color:rgba(255,255,255,0.4);
  border:1px solid #000000;
  border-radius:50px;
}
div.desk::-webkit-scrollbar-thumb
{
  background-color:rgba(255,255,255,1);
  border-radius:50px;
  border:1px solid #000000;
}
div.desk::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(0,255,0,1);
}
div.desk div.item
{
  width:80px;
  height:95px;
}
div.desk div.item
{
  margin-top:20px;
  margin-left:20px;
  padding:10px;
  user-select:none;
  overflow:hidden;
  display:inline-block;
}
div.desk div.item:hover
{
  outline:1px solid #00A0FF;
  background-color:rgba(0,128,255,0.2);
}
div.desk div.selected
{
  outline:1px solid #00C0A0 !important;
  overflow:visible;
  box-shadow:0px 0px 0px 2px #000000,0px 0px 10px 3px #000000;
}
div.desk div.item:focus
{
  outline:inherit;
}
div.desk div.item:focus div.title,div.selected div.title
{
  line-height:1.5 !important;
}
div.desk div.item div.icon
{
  width:100%;
  height:80%;
}
div.desk div.item div.title
{
  width:100%;
  text-align:center;
  color:#FFFFFF;
  font-family:arial;
  letter-spacing:2px;
  text-shadow:1px 1px 1px #000000,-1px 1px 1px #000000,1px -1px 1px #000000,-1px -1px 1px #000000;
  font-size:13px;
  line-height:150px;
  padding-top:5px;
  word-break:break-all;
}
div.desk div.item div.title::first-line
{
  line-height:1;
}
div.taskbar
{
  width:100%;
  height:40px;
  min-height:40px !important;
  background-color:rgba(255,255,255,0.2);
  color:#FFFFFF;
  box-shadow:0px 0px 15px 1px #000000;
  transition:background-position 2s ease-in-out;
  background-position:bottom;
  z-index:1;
}
div.taskbar:hover
{
  background:url(1.jpg),rgba(255,255,255,0.1);
  background-blend-mode:lighten;
  background-position:center;
  background-size:cover;
}
div.taskbar div
{
  display:inline-block;
}
div.taskbar div.menuContainer
{
  height:40px;
  width:40px;
  box-sizing:border-box;
  border-radius:0% 50% 50% 0%;
  margin-right:6px;
  margin-right:25px;
}
div.taskbar div.menuIcon
{
  height:100%;
  width:100%;
  background-image:url(../Desktop/icon/icon_4menu.svg);
  background-size:90%;
  background-position:center;
  background-color:rgba(255,255,255,0.5);
  border-radius:50%;
  box-sizing:border-box;
  border:1px solid #000000;
  margin-left:6px;
  transition:background-size 1s,background-color 1s;
}
div.taskbar div.menuContainer:hover div.menuIcon
{
  background-size:190%;
  background-color:rgba(0,255,255,1);
}
div.app,div.app *
{
  user-select:none;
  color:#000000;
}
div.app
{
  width:auto;
  height:100%;
  position:relative;
  border:1px solid #000000;
  border-radius:5px;
  box-sizing:border-box;
  margin-right:5px;
  overflow:hidden;
  background-color:rgba(0,0,0,0.4);
  z-index:0;
}
div.app div.icon
{
  margin:3.5px;
  height:30px;
  width:30px;
  float:left;
  box-sizing:border-box;
  display:inline-block;
  background-image:url(../Desktop/icon/icon_setting.svg);
  background-size:cover;
}
div.app div.System
{
  background-image:url(../Desktop/icon/icon_setting.svg);
  background-size:cover;
}
div.app div.Application
{
  background-image:url(../Desktop/icon/icon_app.png);
  background-size:90%;
  background-position:center;
}
div.app div.title
{
  max-width:150px;
  overflow:hidden;
  font-size:18px;
  font-weight:900;
  font-family:arial black;
  width:auto;
  height:auto;
  padding:5px;
  float:left;
  padding-left:10px;
  display:none;
}
div.app:hover
{
  background-color:rgba(0,0,0,0.8);
}
div.app:hover div.title
{
  color:#FFFFFF;
  display:inline-block;
}
iframe.App
{
  width:100%;
  height:calc(100% - 37.5px);
  border:none;
  outline:none;
  background-color:rgba(255,255,255,0.3);
}
.fullscreen
{
  height:calc(100% - 30px) !important;
}
div.Widget,div.Widget *
{
  user-select:none;
}
div.Widget
{
  position:absolute;
  top:10px;
  left:10px;
  width:810px;
  height:445px;
  resize:both;
  min-width:150px;
  min-height:50px;
  overflow:hidden;
  z-index:1;
}
div.frametop
{
  display:none;
}
div.frametop button.winbutton
{
  display:inline-block;
  float:right;
  width:30px;
  height:20px;
  background-color:transparent;
}
div.frametopin
{
  display:inline-block;
  position:absolute;
  top:0;
  left:0;
  background-size:100%;
  padding-right:10px;
  padding-bottom:5px;
}
div.frametopin div.icon
{
  display:inline-block;
  width:20px;
  height:20px;
  margin-top:5px;
  margin-left:5px;
  float:left;
}
div.frametopin div.title
{
  display:inline-block;
  float:left;
  padding-left:10px;
  padding-top:5.5px;
  text-shadow:1px 1px 10px #FFFFFF,-1px 1px 10px #FFFFFF,1px -1px 10px #FFFFFF,-1px -1px 10px #FFFFFF;
}
div.Widget div.back
{
  background-color:transparent;
  margin-left:5px;
  margin-right:5px;
  border-radius:5px;
}
div.Widget div.backfullscreen
{
  margin-left:0px;
  margin-right:0px;
  border-radius:0px;
}
div.WidgetSelected
{
  border-radius:10px;
  box-shadow:0px 0px 10px 1px #000000;
  background-color:rgba(255,255,255,0.4);
  overflow:hidden;
}
div.WidgetSelected div.backblur
{
  background-color:rgba(255,255,255,0.4);
  padding-bottom:5px;
  overflow:hidden;
}
div.WidgetSelected div.frametop
{
  display:block;
  background:url("../Desktop/icon/windows/bg.png");
  background-size:cover;
  background-position:center;
  width:100%;
  height:30px;
  opacity:0.99;
}
div.WidgetSelected div.frametop:hover
{
  cursor:move;
}
div.WidgetSelected div.back
{
  background-color:#D0D0D0;
  border:0.5px solid #606060;
  overflow:hidden;
}
div.WidgetSelected button
{
  border-left:1px solid #606060;
  border-right:1px solid #606060;
  border-bottom:1px solid #606060;
  height:100%;
}
div.WidgetSelected button.minimize
{
  background-image:url("../Desktop/icon/windows/minimize.png");
  background-position:top;
  background-size:100% 200%;
  background-repeat:no-repeat;
  border-bottom-left-radius:5px;
}
div.WidgetSelected button.maximize
{
  background-image:url("../Desktop/icon/windows/boxmin.png");
  background-position:top;
  background-size:100% 200%;
  background-repeat:no-repeat;
}
div.WidgetSelected button.close
{
  background-image:url("../Desktop/icon/windows/close.png");
  background-position:top;
  background-size:100% 200%;
  width:40px;
  background-repeat:no-repeat;
  border-bottom-right-radius:5px;
  overflow:hidden;
}
div.WidgetSelected button.close:hover,div.WidgetSelected button.minimize:hover,div.WidgetSelected button.maximize:hover
{
  background-position:bottom;
  filter:brightness(110%);
}
div.WidgetSelected div.bset
{
  float:right;
  width:100px;
  height:20px;
  display:inline-block;
  margin-right:10px;
  border:0.5px solid #000000;
  overflow:hidden;
  transform:translateY(-1px);
  border-bottom-left-radius:5px;
  border-bottom-right-radius:5px;
  box-shadow:0 0 10px 1px #707070;
  background:linear-gradient(0deg,rgba(255,255,255,0.5),rgba(0,0,0,0.5),rgba(255,255,255,0.5));
}
div.virtualContainer
{
  width:100%;
  height:calc(100% - 85px);
  position:absolute;
  top:45px;
  display:flex;
  background-color:rgba(0,0,0,0.3);
  overflow:hidden;
}
div.virtualContainer .Widget
{
  display:inline-block;
}
div.selection
{
  position:absolute;
  top:0;
  left:0;
  width:100px;
  height:100px;
  background-color:rgba(0,255,128,0.5);
  outline:2px solid #00FF80;
  display:none;
}
div.virtualContainer
{
  //display:flex;
  display:none;
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
.optionDisabled *
{
  filter:grayscale(100);
  opacity:0.5;
}
.rightOptions .arrow
{
  background-color:transparent;
  border-left:5px solid #000000;
  border-top:5px solid transparent;
  border-bottom:5px solid transparent;
  width:0;
  height:0;
  padding:0 !important;
  margin-top:10px;
  display:inline-block;
}
input.renameTitle
{
  width:calc(75% - 8px);
  text-align:center;
  background-color:rgba(255,255,255,0.9);
  border-top:1px solid #00FF00;
  border-bottom:1px solid #00FF00;
  border-left:none;
  border-right:none;
  padding:4px;
}
input.renameTitle:focus
{
  outline:none;
}
input.renameCancel
{
  width:15%;
  padding-top:5px;
  padding-bottom:5px;
  border:none;
  display:inline-block;
  color:#FFFFFF;
  background-color:#800000;
}
input.renameCancel:hover
{
  background-color:#FF0000;
}
input.renameSubmit
{
  width:15%;
  padding-top:5px;
  padding-bottom:5px;
  border:none;
  display:inline-block;
  color:#000000;
  font-size:11px;
  background-color:#008000;
  box-sizing:border-box;
}
input.renameSubmit:hover
{
  background-color:#00FF00;
}
input.renameCancel:focus,input.renameSubmit:focus
{
  outline:none;
}
div.RealWidget div.backblur
{
  background-color:transparent;
}
div.RealWidget div.backblur div.back
{
  background-color:transparent;
}
div.RealWidget div.backblur div.back iframe
{
  background-color:rgba(255,255,255,0.4);
}
div.RealWidget
{
  width:250px;
  height:280px;
  position:absolute;
  left:100%;
  top:0;
  transform:translate(-105%,60px);
  resize:none;
  z-index:0;
}
div.RealWidgetSelected div.backblur div.back
{
  background-color:#C0C0C0;
}
div.Cut
{
  opacity:0.5;
  filter:brightness(90%);
  outline:1px dotted #FFFFFF;
}
div.expandedMenu
{
  width:auto;
  height:auto;
  min-width:170px;
  border:1px solid #000000;
  background-color:#E0E0E0;
  box-shadow:5px 5px 15px 0px rgba(0,0,0,0.6);
  position:absolute;
  top:0;
  left:0;
  margin-left:1px;
  display:none;
}
div.expandedMenu:hover
{
  display:inline-block !important;
}
div.expandedMenu span.icon
{
  width:20px;
  height:20px;
  display:inline-block;
}
div.expandedMenu span.title
{
  display:inline-block;
}
form.hiddenForm,form.hiddenForm *
{
  display:none;
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
  background-color:#80FFFF;
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
  background-color:#80FFFF;
}
div.popup div.topbox
{
  position:relative;
  top:0;
  right:0;
  width:100%;
  height:auto;
  background-color:#00C0C0;
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
  border:2px solid #008080;
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
button.greenbutton
{
  background:linear-gradient(90deg,#00C000,#005000);
  width:100%;
  border:2px solid #008000;
  transition: width 0.5s,background: 0.5s ease-in;
  color:#FFFFFF;
  display:block;
}
button.greenbutton:hover
{
  background:linear-gradient(90deg,#00FF00,#00FF00);
  color:#000000;
}
button.redbutton
{
  background:linear-gradient(90deg,#C00000,#500000);
  width:100%;
  border:2px solid #800000;
  color:#FFFFFF;
  transition: width 1s,background: 1s ease-in;
  display:block;
}
button.redbutton:hover
{
  background:linear-gradient(90deg,#FF0000,#FF0000);
  filter:brightness(125%);
}
div.icon_fromcloud
{
  background-image:url("../Desktop/icon/icon_cloud.svg");
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
div.icon_globe
{
  background-image:url("../Desktop/icon/icon_globe.svg");
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
div.iconUploadPreview
{
  width:90px;
  height:90px;
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
.radioOption
{
  display:inline-block;
}
.radioOption span
{
  display:block;
  padding:10px;
}
center.cbutton
{
  width:200px;
  display:inline-block;
  float:right;
}
center.wallcent
{
  width:100%;
}
center.wallcent button
{
  width:30%;
  margin:5px;
  padding:5px;
  display:inline-block;
}
a.downloadHidden
{
  display:none;
}
label.rad
{
  padding-left:10px;
}
.icon_App_Store
{
  background-image:url("../Desktop/icon/icon_App_Store.svg");
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
}
iframe.download
{
  display:none;
}
div.appifrload
{
  width:100%;
  height:100%;
  position:absolute;
  background:rgba(200,200,200,0.7) url("../Desktop/icon/loading.gif");
  background-size:100px;
  background-repeat:no-repeat;
  background-position:center;
}
div.appmenubg
{
  width:100vw;
  height:calc(100vh - 85px);
  position:fixed;
  top:45px;
  left:0;
  background-color:rgba(0,0,0,0.5);
  display:none;
}
div.appmenu
{
  width:260px;
  height:350px;
  background-color:#2d6830;
  position:fixed;
  bottom:40px;
  left:0;
  z-index:0;
  box-shadow:5px -5px 10px 1px #000000;
  display:none;
  overflow:hidden;
  animation:showmenu 0.3s linear 1 forwards;
}
@keyframes showmenu
{
  0%
  {
    height:0px;
  }
  100%
  {
    height:350px;
  }
}
div.appmenu .appshort
{
  margin-top:5px;
  margin-left:5px;
  margin-right:5px;
  width:calc(100% -10px);
  height:20px;
  overflow:hidden;
  background-color:rgba(0,0,0,0.6);
  padding:5px 0px;
}
div.appmenu .appshort div.icon
{
  width:20px;
  height:20px;
  display:inline-block;
  vertical-align:bottom;
  background-size:contain;
  background-repeat:no-repeat;
  margin-left:5px;
  margin-right:10px;
}
div.appmenu .appshort div.title
{
  width:calc(100% - 35px);
  height:20px;
  display:inline-block;
  padding-top:1.5px;
  box-sizing:border-box;
  font-size:16px;
  font-weight:600;
  color:#FFFFFF;
  overflow:hidden;
}
div.appmenu>input.menusearch
{
  padding:10px;
  width:calc(100% - 10px);
  margin:0px 5px;
  margin-top:5px;
  height:30px;
  border:none;
  outline:none;
  background-color:rgba(255,255,255,0.5);
  vertical-align:middle;
  font-family:serif;
  font-size:20px;
}
div.appmenu>input.menusearch::placeholder
{
  color:#505050;
}
input.urladdress::selection
{
  background-color:#cddc39;
}
div.appmenu>input.menusearch::selection
{
  background-color:#4caf50;
}
input.renameTitle::selection
{
  background-color:#009688;
  color:#FFFFFF;
}
div.appmenucontent
{
  width:100%;
  height:calc(100% - 42.5px);
  overflow-x:hidden;
  overflow-y:auto;
}
div.appmenucontent::-webkit-scrollbar
{
  width:10px;
  height:10px;
  background-color:rgba(0,128,255,0.5);
}
div.appmenucontent::-webkit-scrollbar-thumb
{
  background-color:rgba(0,128,255,1);
}
div.appmenucontent::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(0,255,128,1);
}
div.menusearcherror
{
  text-align:center;
  color:#FFFFFF;
  padding-top:20px;
}
input.urladdress
{
  background-color:rgba(0,255,128,0.1);
  border:none;
  outline:none;
  font-size:25px;
  height:45px;
  color:#FFFFFF;
  font-family:serif;
  padding:0px 10px;
  letter-spacing:1px;
  width:calc(100% - 45px);
}
input.urladdress
{
  outline:none;
}
input.urladdress::placeholder
{
  color:#A0A0A0;
}
div.dc
{
  display:flex;
  flex-direction:row;
  height:100%;
}
div.opendir
{
  width:240px;
  min-width:200px;
  height:100%;
}
div.directorytree
{
  width:100%;
  height:100%;
  border-right:1px solid <?php echo $AccentBorder; ?>;
  background-color:rgba(128,128,128,0.2);
  user-select:none;
  overflow-x:auto;
  overflow-y:auto;
  position:relative;
}
div.directorytree div.dtree
{
  color:<?php echo $AccentDark; ?>;
  padding:15px;
  border-bottom:1px solid <?php echo $Accent; ?>;
}
div.directorytree::-webkit-scrollbar
{
  width:8px;
  height:8px;
  background-color:rgba(128,128,128,0.8);
}
div.directorytree::-webkit-scrollbar:hover
{
  background-color:rgba(255,255,255,0.5);
}
div.directorytree::-webkit-scrollbar-thumb
{
  background-color:<?php echo $AccentBorder; ?>;
  border-radius:20px;
  border:0.5px solid <?php echo $Accent; ?>;
}
div.directorytree::-webkit-scrollbar-thumb:hover
{
  background-color:<?php echo $Accent; ?>;
  border:0.5px solid <?php echo $AccentBorder; ?>;
}
div.directorytree::-webkit-scrollbar-corner
{
  display:none;
}
div.directorytree div.directory
{
  color:<?php echo $ThemeText; ?>;
  width:100vmax !important;
}
div.xtitle
{
  padding:0px 10px;
  height:32px;
  width:100vmax;
  overflow:hidden;
}
div.xtitle:focus,div.xtcontainer:focus
{
  outline:none;
}
div.xtcontainer:focus::before
{
  content:"";
  width:100vmax;
  height:32px;
  background-color:#37803a;
  position:absolute;
  left:0;
  z-index:-100;
}
div.directorytree div.st
{
  font-size:18px;
  font-family:consolas;
  margin-right:8px;
  display:inline-block;
  transition:transform 0.5s;
  padding:5px 0px;
  color:<?php echo $Accent; ?>;
}
div.str
{
  font-size:18px;
  font-family:consolas;
  margin-right:8px;
  display:inline-block;
  transition:transform 0.5s;
  padding:5px 0px;
  color:<?php echo $Accent; ?>;
  min-width:50px;
}
div.directorytree div.maxfolder>div.xtcontainer>div.xtitle>div.st
{
  transform:rotate(90deg);
}
div.directorytree div.directory span.dtitle
{
  font-size:16px;
  font-weight:400;
  letter-spacing:1px;
  padding:5px 0px;
}
div.directorytree div.directory span.xdtitle
{
  font-size:14px;
  letter-spacing:1px;
  padding:0px;
}
div.dcontents
{
  width:100vmax;
  height:auto;
  display:none;
}
hr.bb
{
  margin-left:13.5px;
  border-color:<?php echo $AccentDark; ?>;
}
div.maxfolder div.dcontents,div.maxfolder div.xtitle
{
  border-left:1px solid <?php echo $AccentDark; ?>;
}
div.dcontents>div.directory>div.xtcontainer
{
  padding-left:13.5px;
  width:100vmax;
}
div.directorytree div.maxfolder>div.xtcontainer>div.dcontents
{
  display:block;
}
div.nb
{
  border:none !important;
}
div.drivecontainer div.drive
{
  padding-top:10px;
  padding-right:10px;
  padding-bottom:10px;
  width:480px;
  height:100px;
  border:1px solid #000000;
  display:inline-block;
  float:left;
  margin-top:40px;
  margin-left:40px;
  text-align:left;
  background-color:rgba(255,255,255,0.6);
  position:relative;
  box-shadow:0px 0px 10px 1px #000000;
  border-radius:5px;
}
div.drivecontainer div.drive:hover
{
  background-color:#a9daab;
  border:1px solid #00FF00;
  box-shadow:0px 0px 10px 5px #000000;
}
div.drivecontainer div.drive,div.drivecontainer div.drive *
{
  user-select:none;
}
div.drivecontainer div.drive div.icon
{
  width:100px;
  height:100px;
  background-image:url("drive.png");
  background-size:contain;
  background-repeat:none;
  background-position:center;
  display:inline-block;
}
div.drivecontainer div.title
{
  text-align:left;
  display:inline-block;
  padding:5px;
  margin-left:5px;
  position:absolute;
  bottom:10px;
  left:100px;
}
div.drivecontainer div.drive div.letter
{
  display:inline-block;
  position:absolute;
  padding:5px;
  margin-left:5px;
  font-size:20px;
  font-family:arial black,arial,sans-serif;
  font-weight:900;
  letter-spacing:4px;
}
div.drivecontainer div.drive div.percentage
{
  display:inline-block;
  position:absolute;
  top:10px;
  right:22px;
  padding:5px;
  margin-left:5px;
  font-size:20px;
  font-family:consolas,serif;
  text-shadow:1px 1px 1px #000000,-1px 1px 1px #000000,1px -1px 1px #000000,-1px -1px 1px #000000;
  font-weight:600;
  letter-spacing:2px;
}
div.drivecontainer div.drive div.percentage span.nostyle
{
  font-weight:normal !important;
}
div.drivecontainer div.drive div.red
{
  color:#FF0000 !important;
}
div.drivecontainer div.drive div.green
{
  color:#00FF00 !important;
}
div.drivecontainer div.drive div.progressbar
{
  display:inline-block;
  position:absolute;
  bottom:35px;
  width:350px;
  height:20px;
  outline:1px solid #000000;
  background-color:#D0D0D0;
}
div.drivecontainer div.drive div.progressbar div.progress
{
  width:0px;
  background-color:#00FF00;
  height:20px;
}
div.drivecontainer div.drive div.progressbar div.green
{
  background:linear-gradient(0deg,#438600,#8fd747,#438600);
}
div.drivecontainer div.drive div.progressbar div.red
{
  background:linear-gradient(0deg,#A00000,#FF0000,#A00000);
}
div.drivecontainer span.bold
{
  font-weight:900;
  font-family:arial,sans-serif;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function togglefolder(event,x)
{
  event.stopPropagation();
  x.parentNode.classList.toggle("maxfolder");
}
function selectItemsByDrag(mx,my,mw,mh)
{
  var e=document.getElementsByClassName("item");
  var n=e.length;
  for(let i=0;i<n;i++)
  {
    var x=e[i].getBoundingClientRect();
    var left=parseInt(x.left);
    var top=parseInt(x.top);
    var width=parseInt(x.width);
    var height=parseInt(x.height);
    var bottom=top+height;
    var right=left+width;
    if((mx<=right && right<=mw) && (my<=bottom && bottom<=mh))
    {
      e[i].classList.add("Selected");
    }
    if((mx<=left && left<=mw) && (my<=bottom && bottom<=mh))
    {
      e[i].classList.add("Selected");
    }
    if((mx<=left && left<=mw) && (my<=top && top<=mh))
    {
      e[i].classList.add("Selected");
    }
    if((mx<=right && right<=mw) && (my<=top && top<=mh))
    {
      e[i].classList.add("Selected");
    }
  }
}
function selectItemsEdge(mx,my)
{
  var e=document.getElementsByClassName("item");
  var n=e.length;
  for(let i=0;i<n;i++)
  {
    var x=e[i].getBoundingClientRect();
    var left=parseInt(x.left);
    var top=parseInt(x.top);
    var width=parseInt(x.width);
    var height=parseInt(x.height);
    var bottom=top+height;
    var right=left+width;
    if((mx>=left && mx<=right) && (my>=top && my<=bottom))
    {
      e[i].classList.add("Selected");
    }
  }
}
function on(event,id,type,xel)
{
  off();
  if(id=="appRightMenu")
  {
    event.stopPropagation();
    xel.classList.add("Selected");
    var all=document.getElementsByClassName("Selected");
    var n=all.length;
    for(let i=0;i<n;i++)
    {
      if((all[i].getAttribute("attr_type")=="App" || all[i].getAttribute("attr_type")=="System") || (type=="App" || type=="System"))
      {
        _("bappContextOpenWith").classList.add("optionDisabled");
        _("bappContextCut").classList.add("optionDisabled");
        _("bappContextCopy").classList.add("optionDisabled");
        _("bappContextRename").classList.add("optionDisabled");
        _("bappContextDownload").classList.add("optionDisabled");
        _("bappContextDelete").classList.add("optionDisabled");
        _("bappContextProperties").classList.add("optionDisabled");
      }
      else if(all[i].getAttribute("attr_type")=="Folder" || (type=="App" || type=="Folder"))
      {
        _("bappContextOpenWith").classList.add("optionDisabled");
        _("bappContextDownload").classList.add("optionDisabled");
      }
    }
    if(n>1)
    {
      _("bappContextOpen").classList.add("optionDisabled");
      _("bappContextOpenWith").classList.add("optionDisabled");
      _("bappContextRename").classList.add("optionDisabled");
      _("bappContextProperties").classList.add("optionDisabled");
    }
  }
  if(id=="rightMenu")
  {
    if(!isValidPaste())
    {
      document.getElementById("bodyContextPaste").classList.add("optionDisabled");
    }
  }
  var x=event.clientX,y=event.clientY;
  var menu=document.getElementById(id);
  menu.style.display="inline-block";
  var xd=menu.clientWidth,yd=menu.clientHeight;
  var body=document.getElementById("body");
  var bxd=body.clientWidth,byd=body.clientHeight;
  x=(x>=(bxd-xd))?(x-xd):x;
  y=(y>=(byd-yd))?(y-yd):y;
  menu.style.top=y+"px";
  menu.style.left=x+"px";
}
function off()
{
  var a=document.getElementsByClassName("rightoptions");
  var b=a.length;
  for(let i=0;i<b;i++)
  {
    a[i].classList.remove("optionDisabled");
  }
  document.getElementById("rightMenu").style.display="none";
  document.getElementById("appRightMenu").style.display="none";
  menuHideApps();
}
function searchmenuon(key)
{
  if(key=="")
  {
    menuAllFilesAndFolders();
    return;
  }
  var x="";
  var a=document.getElementsByClassName("item");
  var n=a.length;
  var key=new RegExp(key,"i");
  var xn=0;
  for(let i=0;i<n;i++)
  {
    var b=a[i].childNodes;
    if(key.test(b[1].innerHTML))
    {
      x+="<div class='appshort' onclick='"+a[i].getAttribute('ondblclick')+"'>";
      x+="<div class='icon' style='background-image:"+b[0].style.backgroundImage+";'></div>";
      x+="<div class='title'>"+b[1].innerHTML+"</div>";
      x+="</div>";
      xn++;
    }
  }
  if(xn<=0)
  {
    x="<div class='menusearcherror'>No Results Found !</div>";
  }
  _("appmenucontent").innerHTML=x;
}
function menuAllFilesAndFolders()
{
  var x="";
  var a=document.getElementsByClassName("item");
  var n=a.length;
  for(let i=0;i<n;i++)
  {
    var b=a[i].childNodes;
    x+="<div class='appshort' onclick='"+a[i].getAttribute('ondblclick')+"'>";
    x+="<div class='icon' style='background-image:"+b[0].style.backgroundImage+";'></div>";
    x+="<div class='title'>"+b[1].innerHTML+"</div>";
    x+="</div>";
  }
  _("appmenucontent").innerHTML=x;
}
function menuShowApps()
{
  var x=_("appmenu").style;
  var y=_("appmenubg").style;
  if(x.display=="block")
  {
    x.display="none";
    y.display="none";
  }
  else
  {
    x.display="block";
    y.display="block";
    _("menusearch").focus();
  }
}
function menuHideApps()
{
  var x=_("appmenu").style;
  var y=_("appmenubg").style;
  x.display="none";
  y.display="none";
  _("menusearch").value="";
}
function widgetSelect(e)
{
  e.classList.toggle("WidgetSelected");
  e.classList.toggle("RealWidgetSelected");
}
function drawRect(e,x,s)
{
  if(e.button===2)
  {
    //if mouse right click drag return to contextmenu
    rectSelect=false;
    return;
  }
  rectSelect=true;
  initX=parseInt(e.clientX);
  initY=parseInt(e.clientY);
  s.style.top=initY;
  s.style.left=initX;
  s.style.width=0;
  s.style.height=0;
  s.style.display="block";
}
function growRect(e,x,s)
{
  if(rectSelect==true)
  {
    rectX=e.clientX;
    rectY=parseInt(e.clientY);
    if(parseInt(e.clientX)>=initX && parseInt(e.clientY)>=initY)
    {
      //console.log("se");
      //se
      s.style.left=initX;
      s.style.top=initY;
      s.style.width=parseInt(rectX-initX);
      s.style.height=parseInt(rectY-initY);
      selectItemsByDrag(parseInt(e.clientX)-parseInt(s.style.width),parseInt(e.clientY)-parseInt(s.style.height),parseInt(e.clientX),parseInt(e.clientY));
    }
    else if(parseInt(e.clientX)<=initX && parseInt(e.clientY)>=initY)
    {
      //console.log("sw");
      //sw
      s.style.left=rectX;
      s.style.top=initY;
      s.style.width=parseInt(initX-rectX);
      s.style.height=parseInt(rectY-initY);
      selectItemsByDrag(parseInt(e.clientX),parseInt(e.clientY)-parseInt(s.style.height),parseInt(e.clientX)+parseInt(s.style.width),parseInt(e.clientY));
    }
    else if(parseInt(e.clientX)<=initX && parseInt(e.clientY)<=initY)
    {
      //console.log("nw");
      //nw
      s.style.left=rectX;
      s.style.top=rectY;
      s.style.bottom=0;
      s.style.right=0;
      s.style.width=parseInt(initX-rectX);
      s.style.height=parseInt(initY-rectY);
      selectItemsByDrag(parseInt(e.clientX),parseInt(e.clientY),parseInt(s.style.width)+parseInt(e.clientX),parseInt(s.style.height)+parseInt(e.clientY));
    }
    else if(parseInt(e.clientX)>=initX && parseInt(e.clientY)<=initY)
    {
      //console.log("ne");
      //ne
      s.style.left=initX;
      s.style.top=rectY;
      s.style.width=parseInt(rectX-initX);
      s.style.height=parseInt(initY-rectY);
      selectItemsByDrag(parseInt(e.clientX)-parseInt(s.style.width),parseInt(e.clientY),parseInt(e.clientX),parseInt(e.clientY)+parseInt(s.style.height));
    }
    selectItemsEdge(parseInt(e.clientX),parseInt(e.clientY));
  }
}
function endRect(s)
{
  s.style.display="none";
  rectSelect=false;
  rectX=0;
  rectY=0;
  initX=0;
  initY=0;
}
  function openAPP(title,icon,source,w,h,nw=0,nh=0,xw=0,xh=0)
  {
    off();
    deselectItem();
    if(window!=window.top)
    {
      window.parent.openAPP(title,icon,source,w,h,nw=0,nh=0,xw=0,xh=0);
    }
    else
    {
      var virtual=document.getElementById("virtualContainer");
      virtual.style.display="flex";
      var id=reserveAppStack();
      var src=source;

      //Start of Window Frame
      var Widget=document.createElement("DIV");
      Widget.setAttribute("class","Widget WidgetSelected OpenedApp");
      Widget.setAttribute("id","Cont_frame_"+id);
      Widget.setAttribute("idcode",id);
      Widget.setAttribute("x","0");
      Widget.setAttribute("y","0");
      Widget.setAttribute("m","0");
      Widget.setAttribute("w",w);
      Widget.setAttribute("h",h);
      Widget.setAttribute("onclick","event.stopPropagation();");
      Widget.setAttribute("style","z-index:"+id+";");
      Widget.style.width=w;
      Widget.style.height=h;
      if(nw!=0)
      {
        Widget.style.minWidth=nw+"px";
      }
      if(nh!=0)
      {
        Widget.style.minHeight=nh+"px";
      }
      if(xw!=0)
      {
        Widget.style.maxWidth=xw+"px";
      }
      if(xh!=0)
      {
        Widget.style.maxHeight=xh+"px";
      }
      virtual.appendChild(Widget);

      var frametopin=document.createElement("DIV");
      frametopin.setAttribute("class","Frametopin");
      Widget.appendChild(frametopin);

      var ic=document.createElement("DIV");
      ic.setAttribute("class","icon");
      ic.style.background="url('"+icon+"')";
      ic.style.backgroundSize="contain";
      ic.style.backgroundRepeat="no-repeat";
      frametopin.appendChild(ic);

      var ti=document.createElement("DIV");
      ti.setAttribute("id","title_"+id);
      ti.setAttribute("class","title");
      ti.innerHTML=title;
      frametopin.appendChild(ti);

      var frametop=document.createElement("DIV");
      frametop.setAttribute("class","Frametop");
      frametop.setAttribute("id","frame_"+id);
      frametop.setAttribute("onmousedown","ld(this,'Cont_frame_"+id+"',id);");
      frametop.setAttribute("x","0");
      frametop.setAttribute("y","0");
      Widget.appendChild(frametop);

      var bset=document.createElement("DIV");
      bset.setAttribute("class","bset");
      frametop.appendChild(bset);

      var b1=document.createElement("BUTTON");
      b1.setAttribute("type","button");
      b1.setAttribute("class","winbutton close");
      b1.setAttribute("onmousedown","event.stopPropagation()");
      b1.setAttribute("onclick","closeApp('"+id+"');");
      bset.appendChild(b1);

      var b2=document.createElement("BUTTON");
      b2.setAttribute("type","button");
      b2.setAttribute("class","winbutton maximize");
      b2.setAttribute("id","winmax");
      b2.setAttribute("onmousedown","event.stopPropagation()");
      b2.setAttribute("onclick","boxMaximizeApp('"+id+"');");
      bset.appendChild(b2);

      var b3=document.createElement("BUTTON");
      b3.setAttribute("type","button");
      b3.setAttribute("class","winbutton minimize");
      b3.setAttribute("onmousedown","event.stopPropagation()");
      b3.setAttribute("onclick","minimizeApp('"+id+"');");
      bset.appendChild(b3);

      var backblur=document.createElement("DIV");
      backblur.setAttribute("class","backblur");
      backblur.setAttribute("id","f_"+id);
      Widget.appendChild(backblur);
      var back=document.createElement("DIV");
      backblur.setAttribute("class","back");
      backblur.appendChild(back);

      if(title!="Image Viewer" && title!="File Viewer")
      {
        var loader_gif=document.createElement("DIV");
        loader_gif.setAttribute("class","appifrload");
        loader_gif.setAttribute("id","app_ifr_loader_preview_"+id);
        back.appendChild(loader_gif);
      }

      var iframe=document.createElement("iframe");
      iframe.setAttribute("class","App WidgetApplication");
      iframe.setAttribute("id","ifr_"+id);
      iframe.setAttribute("src",src);
      back.appendChild(iframe);
      if(title!="Image Viewer" && title!="File Viewer")
      {
        iframe.onload=function(){
          _("app_ifr_loader_preview_"+id).style.display="none";
        };
      }
      //End of Window Frame


      //App Taskbar
      var task=document.createElement("DIV");
      task.setAttribute("class","app");
      task.setAttribute("id","app_task_"+id);
      task.setAttribute("onclick","toggleApp("+id+");");
      document.getElementById("taskbar").appendChild(task);

      var taskIcon=document.createElement("DIV");
      taskIcon.setAttribute("class","icon Application");
      taskIcon.style.background=" url('"+icon+"')";
      taskIcon.style.backgroundSize="90%";
      taskIcon.style.backgroundPosition="center";
      taskIcon.style.backgroundRepeat="no-repeat";
      task.appendChild(taskIcon);

      var taskTitle=document.createElement("DIV");
      taskTitle.setAttribute("class","title");
      taskTitle.innerHTML=title;
      task.appendChild(taskTitle);

      //window.location=source;
    }
  }
  function manageFile(source)
  {
    openAPP("File Viewer","../Desktop/icon/icon_fileviewer.svg",source,625,500,300,200,0,0);
  }
  function imageViewer(source)
  {
    var x="../Decrypt/image.php?getFile="+source;
    openAPP("Image Viewer","../Desktop/icon/icon_image.svg",x,600,500,300,200,0,0);
  }
  function appContext(event)
  {
    event.stopPropagation();
    //new
  }

  function ld(de,cid,id)
  {
    stackUp(cid);
    var app=document.getElementById(cid);
    if(app.getAttribute("m")==1)
    {
      boxMaximizeApp(app.getAttribute("idcode"));
      //return false;
    }
    var dragItem = de;
    var container = document.getElementById("virtualContainer");

    var active = false;
    var currentX;
    var currentY;
    var initialX;
    var initialY;
    var xOffset=parseInt(de.getAttribute("x"));
    var yOffset=parseInt(de.getAttribute("y"));

    container.addEventListener("mousedown", dragStart, false);
    container.addEventListener("mouseup", dragEnd, false);
    container.addEventListener("mousemove", drag, false);

    function dragStart(e)
    {
      if(e.type === "mousedown")
      {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;
      }
      if(e.target === dragItem)
      {
        active = true;
      }
    }
    function dragEnd(e)
    {
      initialX = currentX;
      initialY = currentY;
      active = false;
      de.setAttribute("x",initialX);
      de.setAttribute("y",initialY);
    }
    function drag(e)
    {
      if(active)
      {
        e.preventDefault();
        if(e.type === "mousemove")
        {
          currentX = e.clientX - initialX;
          currentY = e.clientY - initialY;
        }
        xOffset = currentX;
        yOffset = currentY;
        setTranslate(currentX, currentY, document.getElementById(cid));
      }
    }
    function setTranslate(xPos, yPos, el)
    {
      el.style.transform = "translate(" + xPos + "px, " + yPos + "px)";
    }
  }
  function hideVirtualContainer(e)
  {
    e.style.display="none";
  }
  function reserveAppStack()
  {
    var a=parseInt(document.getElementById("appStackCount").value);
    document.getElementById("appStackCount").value=++a;
    return a;
  }
  function closeApp(id)
  {
    var re="Cont_frame_"+id;
    document.getElementById("virtualContainer").removeChild(document.getElementById(re));
    re="app_task_"+id;
    document.getElementById("taskbar").removeChild(document.getElementById(re));
    var x=document.getElementsByClassName("WidgetApplication");
    var z=document.getElementsByClassName("appminimized");
    if((x.length-z.length)<=0)
    {
      hideVirtualContainer(document.getElementById("virtualContainer"));
    }
  }
  function boxMaximizeApp(id)
  {
    var re="Cont_frame_"+id;
    var se="frame_"+id;
    var app=document.getElementById(re);
    var apptop=document.getElementById(se);
    var f=document.getElementById('f_'+id);
    var fr=document.getElementById('ifr_'+id);
    if(parseInt(app.getAttribute("m"))==0)
    {
      app.style.width="100%";
      app.style.height="100%";
      app.style.borderRadius="0px";
      app.style.top="0px";
      app.style.left="0px";
      app.style.transform="translate(0,0)";
      apptop.setAttribute("x",0);
      apptop.setAttribute("y",0);
      f.classList.add("backfullscreen");
      fr.classList.add("fullscreen");
      app.setAttribute("m",1);
    }
    else
    {
      var w=app.getAttribute("w");
      var h=app.getAttribute("h");
      app.style.width=w;
      app.style.height=h;
      app.style.borderRadius="5px";
      app.style.top="10px";
      app.style.left="10px";
      apptop.setAttribute("x",0);
      apptop.setAttribute("y",0);
      f.classList.remove("backfullscreen");
      fr.classList.remove("fullscreen");
      app.setAttribute("m",0);
    }
  }
  function minimizeApp(id)
  {
    var app=document.getElementById("Cont_frame_"+id);
    app.style.display="none";
    app.classList.add("appminimized");
    var x=document.getElementsByClassName("WidgetApplication");
    var z=document.getElementsByClassName("appminimized");
    if((x.length-z.length)<=0)
    {
      hideVirtualContainer(document.getElementById("virtualContainer"));
    }
  }
  function maximizeApp(id)
  {
    var virtual=document.getElementById("virtualContainer")
    virtual.style.display="flex";
    var app=document.getElementById("Cont_frame_"+id);
    app.style.display="inline-block";
    app.classList.remove("appminimized");
  }
  function toggleApp(id)
  {
    stackUp("Cont_frame_"+id);
    var app=document.getElementById("Cont_frame_"+id);
    if(app.classList.contains("appminimized"))
    {
      maximizeApp(id);
    }
    else
    {
      minimizeApp(id);
    }
  }
  function contextFunctionNewFolder()
  {
    document.getElementById('contextNewFolder').value='1';
    document.getElementById('contextForm').submit();
  }
  function contextFunctionNewFile(ext)
  {
    document.getElementById('contextNewFile').value=ext;
    document.getElementById('contextForm').submit();
  }
  function resetCutCopy()
  {
    document.getElementById('contextCut').value='0';
    document.getElementById('contextCopy').value='0';
    var cd=document.getElementsByClassName('item');
    var l=cd.length,i=0;
    while(i<l)
    {
      cd[i].classList.remove("Cut");
      ++i;
    }
  }
  function contextFunctionPaste(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    if(!isValidPaste())
    {
      return;
    }
    var text=getClip();
    if(text=="" || text==null)
    {
      return;
    }
    var content=text+"<?php echo urlbaraddress(); ?>";
    document.getElementById("appContextPaste").value=content;
    var n=content.split("|");
    if(n.length<3)
    {
      //Invalid Length Error
      return;
    }
    if(n[0]=="Cut")
    {
      if(n[1]=="<?php echo urlbaraddress(); ?>")
      {
        //Same Source Cut Paste  //No Need to Cut Paste
        resetCutCopy();
      }
      else
      {
        //Another Source Cut Paste
        realPaste();
      }
    }
    else if(n[0]=="Copy")
    {
      if(n[1]=="<?php echo urlbaraddress(); ?>")
      {
        //Same Source Copy Paste
        realPaste();
      }
      else
      {
        //Another Source Copy Paste
        realPaste();
      }
    }
  }
  function realPaste()
  {
    document.getElementById('contextForm').submit();
  }
  function contextFunctionQuickNote()
  {
    openAPP("Quick Note","../Desktop/icon/icon_clipboard.svg","data:text/html;charset=utf-8,<html><head><style>*{padding:0;margin:0;}body{background-color:rgba(255,60,60);padding:5px;font-size:30px;}</style></head><body contenteditable='true' autocorrect='off' spellcheck='false'></body></html>",500,250,300,100,0,0);
  }
  function renameOk()
  {
    var x=document.getElementsByClassName("Selected");
    var n=x.length,i=0;
    if(n>1)
    {
      alert("Multiple Rename not possible . . . ");
      return;
    }
    var xid=x[0].getAttribute("xid");
    var t=x[0].getAttribute("attr_type");
    var itemTitle=document.getElementById("title_"+xid);
    document.getElementById("appContextRename").value+=itemTitle.innerHTML;
    var content=itemTitle.innerHTML;
    var dynamic="";
    dynamic+="<input id=\"renameSubmitButton\" class=\"renameSubmit\" type=\"button\" value=\"&#10004;\" onclick=\"submitRename(document.getElementById('"+content+"_rename').value,'"+content+"');\" ";
    dynamic+=" defaultRe=\""+content+"\" ";
    dynamic+=" />";
    dynamic+="<input type='text' autocomplete='off' id='";
    dynamic+=content;
    dynamic+="_rename' class='renameTitle' value='";
    dynamic+=content;
    dynamic+="' onkeydown='event.stopPropagation();' onkeyup='event.stopPropagation();' onkeypress=\"rename(event,this.value,'";
    dynamic+=content;
    dynamic+="');\">";
    dynamic+="<input class=\"renameCancel\" type=\"button\" value=\"&times;\"  onclick=\"cancelRename();\" />";

    itemTitle.innerHTML=dynamic;

    //itemTitle.innerHTML="<input type='text' id='"+content+"_rename' class='renameTitle' value='"+content+"' onkeypress='rename(event,this.value,/'"+content+"/');'>";
    document.getElementById(content+"_rename").selectionStart=0;
    document.getElementById(content+"_rename").selectionEnd=document.getElementById(content+"_rename").value.length;
    document.getElementById(content+"_rename").focus();
  }
  function appFunctionRename(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    renameOk();
  }
  function appFunctionDownload(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    var all=document.getElementsByClassName("Selected");
    var n=all.length;
    for(let i=0;i<n;i++)
    {
      url=all[i].getAttribute("attr_down");
      filename=all[i].getAttribute("attr_filename");
      _("appContextDownloadFile").value+=filename+";";
    }
    _("contextForm").submit();
  }
  function srename(newName,itemTitle)
  {
    document.getElementById("appContextRename").value=itemTitle;
    if(newName=="")
    {
      alert("Invalid Name . . .");
      return;
    }
    if(document.getElementById("appContextRename").value!=newName)
    {
      var invalidChars=['\\','/',':','*','?','\"','<','>','|'];
      var n=invalidChars.length,i=0;
      while(i<n)
      {
        if(newName.indexOf(invalidChars[i])>-1)
        {
          alert("Invalid Character Found . . .");
          document.getElementById(itemTitle+"_rename").focus();
          return;
        }
        ++i;
      }
      var ext=newName.split(".");
      if((ext[(ext.length)-1]).toLowerCase()=="fcz")
      {
        console.error("Forbidden File Extension : fcz");
        alert("Forbidden File Extension : fcz");
        return;
      }
      document.getElementById("appContextRename").value+="/"+newName;
      document.getElementById('contextForm').submit();
    }
    else
    {
      cancelRename();
    }
  }
  function rename(event,newName,itemTitle)
  {
    if(event.keyCode==13)
    {
      srename(newName,itemTitle);
    }
    else
    {
      return;
    }
  }
  function realDelete()
  {
    var x=document.getElementsByClassName("Selected");
    var n=x.length,i=0;
    for(i=0;i<n;i++)
    {
      var xid=x[i].getAttribute("xid");
      document.getElementById("appContextDelete").value+=document.getElementById("title_"+xid).innerHTML+":";
    }
    document.getElementById('contextForm').submit();
  }
  function appFunctionDelete(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    realDelete();
  }
  function setKey(event)
  {
    if(event.keyCode==17)//Ctrl
    {
      document.getElementById("Ctrl").value=1;
    }
    if(event.keyCode==16)//Shift
    {
      document.getElementById("Shift").value=1;
    }
    if(event.keyCode==65)//a
    {
      if(document.getElementById("Ctrl").value==1)
      {
        var e=document.getElementsByClassName("item");
        var l=e.length,i=0;
        for(i=0;i<l;i++)
        {
          e[i].classList.add("Selected");
        }
      }
    }
    if(event.keyCode==46)//Delete
    {
      realDelete();
    }
    if(event.keyCode==67)//c
    {
      if(document.getElementById("Ctrl").value==1)
      {
        realCutCopy("Copy");
      }
    }
    if(event.keyCode==88)//x
    {
      if(document.getElementById("Ctrl").value==1)
      {
        realCutCopy("Cut");
      }
    }
    if(event.keyCode==86)//v
    {
      if(document.getElementById("Ctrl").value==1)
      {
        if(isValidPaste())
        {
          contextFunctionPaste(document.getElementById("bodyContextPaste"));
        }
      }
    }
    if(event.keyCode==73)//i
    {
      if(document.getElementById("Ctrl").value==1)
      {
        invertSelection();
      }
    }
  }
  function unsetKey(event)
  {
    if(event.keyCode==17)
    {
      document.getElementById("Ctrl").value=0;
    }
    if(event.keyCode==16)
    {
      document.getElementById("Shift").value=0;
    }
  }
  function selectItem(x,event)
  {
    event.stopPropagation();
    if(document.getElementById("Ctrl").value==1)
    {
      x.classList.toggle("Selected");
    }
    else if(document.getElementById("Ctrl").value!=1)
    {
      deselectItem();
      x.classList.add("Selected");
    }
    x.blur();
  }
  function shiftedDeselect()
  {
    if(_("Ctrl").value==1)
    {
      return;
    }
    else
    {
      deselectItem();
    }
  }
  function deselectItem()
  {
    var x=document.getElementsByClassName("item"),i=0;
    while(i<x.length)
    {
      x[i].classList.remove("Selected");
      ++i;
    }
  }
  function openFolder(path)
  {
    //title,icon,source,w,h
    openAPP("File Manager","../AppStore/icon/icon_File_Manager_10013.png","index.php?url="+path,625,500,500,450);
  }
  function appFunctionOpen(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    var e=document.getElementsByClassName("Selected");
    var l=e.length;
    if(e.length==1)
    {
      a=e[0].getAttribute("attr_eval");
      eval(a);
    }
  }
  function appFunctionOpenWith(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }

    //

  }
  function appFunctionCut(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    resetCutCopy();
    realCutCopy("Cut");
  }
  function realCutCopy(atype)
  {
    var xpath=atype+"|<?php echo urlbaraddress(); ?>|";
    var x=document.getElementsByClassName("Selected");
    var l=x.length,i=0;
    while(i<l)
    {
      var a=x[i].childNodes[1].innerHTML;
      if(x[i].getAttribute("attr_type")=="File" || x[i].getAttribute("attr_type")=="Folder")
      {
        if(atype=="Cut")
        {
          x[i].classList.add("Cut");
        }
        xpath+=a+"|";
      }
      ++i;
    }
    x=document.getElementsByClassName("item");
    l=x.length;
    i=0;
    while(i<l)
    {
      x[i].classList.remove("Selected");
      i++;
    }
    if(atype=="Cut")
    {
      document.getElementById('contextCut').value='1';
    }
    else
    {
      document.getElementById('contextCopy').value='1';
    }
    sessionStorage.setItem("Clipboard",xpath);
  }
  function appFunctionCopy(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    resetCutCopy();
    realCutCopy("Copy");
  }
  function getSelectedItems()
  {
    var x=document.getElementsByClassName('Selected');
    return x;
  }
  function appFunctionProperties(obj)
  {
    if(obj.classList.contains("optionDisabled"))
    {
      return;
    }
    var x=getSelectedItems();
    if(x.length==1)
    {
      openAPP("Properties","../Desktop/icon/icon_properties.png","../Properties/index.php?url="+x[0].getAttribute("attr_prop"),400,400,350,200,450,500);
    }
  }
  function submitRename(newName,itemTitle)
  {
    srename(newName,itemTitle);
  }
  function cancelRename()
  {
    var x=document.getElementById("renameSubmitButton").getAttribute("defaultRe");
    var ax=document.getElementsByClassName("renamableTitle");
    var l=ax.length;
    var i=0;
    while(i<l)
    {
      if(ax[i].getAttribute("def")==x)
      {
        ax[i].innerHTML=x;
        return;
      }
      ++i;
    }
  }
  function setMessage(type,message)
  {
    var x=document.createElement("P");
    x.setAttribute("class",type);
    x.innerHTML=message;
    var b=document.getElementById("body");
    b.insertBefore(x,b.firstChild);
  }
  function invertSelection()
  {
    var x=document.getElementsByClassName("item");
    var l=x.length,i=0;
    while(i<l)
    {
      x[i].classList.toggle("Selected");
      ++i;
    }
  }
  function getClip()
  {
    return sessionStorage.getItem("Clipboard");
  }
  function isValidPaste()
  {
    var x=getClip();
    if(x=="" || x==null)
    {
      return false;
    }
    var n=x.split("|");
    if(n.length<3)
    {
      //Length Error
      return false;
    }
    else if(!(n[0]=="Copy" || n[0]=="Cut"))
    {
      //Not Copy or Cut
      return false;
    }
    else if(!(( n[1].includes('/') || n[1].includes('\\')) && n[1].includes(':')))
    {
      return false;
    }
    return true;
  }
  function logout()
  {
    document.getElementById('logout').value='1';
    document.getElementById('contextForm').submit();
  }
  function max(a,b)
  {
    return (a>b)?a:b;
  }
  function stackUp(id)
  {
    var x=document.getElementsByClassName("OpenedApp");
    var ax=document.getElementById(id).style;
    var l=x.length,i=0;
    var maxZ=0;
    while(i<l)
    {
      maxZ=parseInt(max(parseInt(maxZ),parseInt(x[i].style.zIndex)));
      if(x[i].getAttribute("id")!=id && x[i].style.zIndex!=1)
      {
        x[i].style.zIndex-=1;
      }
      ++i;
    }
    if(parseInt(ax.zIndex)!=maxZ)
    ax.zIndex=parseInt(maxZ);
  }
  function expandMenu(code,x)
  {
    m=_("expandedMenu");
    m.style.display="block";
    m.style.top=x.offsetTop;
    m.style.left=x.clientWidth;
    if(code=="New File")
    {
      m.innerHTML="<div class='rightOptions' onclick=\"contextFunctionNewFile('txt');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=txt&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>Text File</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('html');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=html&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>HTML</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('php');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=php&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>PHP</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('css');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=css&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>CSS</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('js');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=js&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>JS</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('xml');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=xml&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>XML</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('svg');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=svg&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>SVG</span></div>";
    }
    else if(code=="Upload")
    {
      m.innerHTML="<div class='rightOptions' onclick='contextFunctionUpload();'><span class='icon'><div class='icon icon_file'></div></span><span class='title'>File</span></div><div class='rightOptions' onclick='contextFunctionFolderUpload();'><span class='icon'><div class='icon icon_folder'></div></span><span class='title'>Folder</span></div>";
    }
  }
  function hideExpandedMenu()
  {
    m=_("expandedMenu");
    m.style.display="none";
  }
  function contextFunctionUpload()
  {
    _("upFileUpload").click();
  }
  function contextFunctionFolderUpload()
  {
    _("upAllFileFolder").click();
  }
  function realUpload()
  {
    _("uploaderform").submit();
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
  function openUploadPopup(content,key)
  {
    _("uploadpopup").style.display="block";
    _("uploadpopupbg").style.display="block";
    _("xspopcontent").innerHTML=content;
    _("uploadKey").value=key;
  }
  function closeUploadPopup()
  {
    _("uploadpopup").style.display="none";
    _("uploadpopupbg").style.display="none";
    _("upFileUpload").value="";
    _("upAllFileFolder").value="";
    _("uploadKey").value="";
  }
  function validateUpFile(x)
  {
    <?php
      if($_SESSION['Logged']!="administrator@gmail.com")
      {
        ?>
        var limit=<?php echo ((150*1024*1024)-getTotal($dir)); ?>;
        <?php
      }
    ?>
    var upxsize=0;
    _("popupbottombox").innerHTML="<div class='optionsUpload'><div class='radioOption'><span><input type='radio' name='upradio' form='uploaderform' value='skip' id='radioSkip' checked='checked'><label class='title rad' for='radioSkip'>Skip file if filename Exists</label></span><span><input type='radio' name='upradio' form='uploaderform' value='replace' id='radioReplace'><label class='title rad' for='radioReplace'>Replace if filename Exists</label></span></div><center class='cbutton'><button class='redbutton' onclick='closeUploadPopup();'>Cancel Upload</button><button class='greenbutton' onclick='realUpload();'>Upload</button></center></div>";
    var list=x.files;
    var n=list.length;
    var xs="";
    var pop;
    if(n>0)
    {
      for(var i=0;i<n;i++)
      {
        var file=list[i].name;
        var size=list[i].size;
        var abSize=byte_unit_convert(size);
        var type=list[i].type;
        var ext="";
        var ar=file.split(".");
        ext=ar[(ar.length)-1];
        var preview="style='background-image:url(../FileIcon/icongenerator.php?ext="+ext+"&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'";
        xs+="<br><fieldset><legend>&nbsp;File "+(i+1)+"&nbsp;</legend>";
        xs+="<table class='xtable' border=0>";
        xs+="<tr><td rowspan=5><div class='iconUploadPreview' "+preview+"></div></td></tr>";
        xs+="<tr><td>File Name </td><td> : </td><td>"+file+"</td></tr>";
        xs+="<tr><td>Size </td><td> : </td><td>"+abSize+"</td></tr>";
        xs+="<tr><td>Size (bytes) </td><td> : </td><td>"+size+" B</td></tr>";
        xs+="<tr><td>Type </td><td> : </td><td>"+type+"</td></tr>";
        xs+="</table>";
        xs+="</fieldset><br>";
        upxsize+=size;
      }
        <?php
          if($_SESSION['Logged']!="administrator@gmail.com")
          {
            ?>

            if(upxsize>limit)
            {
              alert("Not Enough Space ! You need "+byte_unit_convert(upxsize-limit)+" more Space");
              return;
            }

            <?php
          }
        ?>
      xs="<div style='padding:10px;font-weight:bold;'>Total "+(i)+" Files Selected</div>."+xs;
      openUploadPopup(xs,"upFileUpload");
    }
  }
  function validateUpFolder(x)
  {
      <?php
        if($_SESSION['Logged']!="administrator@gmail.com")
        {
          ?>
            var limit=<?php echo ((150*1024*1024)-getTotal($dir)); ?>;
          <?php
        }
      ?>
    var upxsize=0;
    _("popupbottombox").innerHTML="<center class='wallcent'><button class='redbutton' onclick='closeUploadPopup();'>Cancel Upload</button><button class='greenbutton' onclick='realUpload();'>Upload</button></center></div>";
    var list=x.files;
    var n=list.length;
    var xs="";
    var pop;
    if(n>0)
    {
      for(var i=0;i<n;i++)
      {
        var file=list[i].name;
        var size=list[i].size;
        var abSize=byte_unit_convert(size);
        var type=list[i].type;
        var ext="";
        var ar=file.split(".");
        ext=ar[(ar.length)-1];
        var preview="style='background-image:url(../FileIcon/icongenerator.php?ext="+ext+"&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'";
        xs+="<br><fieldset><legend>&nbsp;File "+(i+1)+"&nbsp;</legend>";
        xs+="<table class='xtable' border=0>";
        xs+="<tr><td rowspan=5><div class='iconUploadPreview' "+preview+"></div></td></tr>";
        xs+="<tr><td>File Name </td><td> : </td><td>"+file+"</td></tr>";
        xs+="<tr><td>Size </td><td> : </td><td>"+abSize+"</td></tr>";
        xs+="<tr><td>Size (bytes) </td><td> : </td><td>"+size+" B</td></tr>";
        xs+="<tr><td>Type </td><td> : </td><td>"+type+"</td></tr>";
        xs+="</table>";
        xs+="</fieldset><br>";
        upxsize+=size;
      }
        <?php
          if($_SESSION['Logged']!="administrator@gmail.com")
          {
            ?>
            if(upxsize>limit)
            {
              alert("Not Enough Space ! You need "+byte_unit_convert(upxsize-limit)+" more Space");
              return;
            }
            <?php
          }
        ?>
      xs="<div style='padding:10px;font-weight:bold;'>Files in Selected Folder.</div>."+xs;
      openUploadPopup(xs,"upAllFileFolder");
    }
  }
  function pseudomodifytourl(x)
  {
    x=x.replace(":\\\\\\\\",":\\");
    x=x.replace(":\\\\\\",":\\");
    x=x.replace(":\\\\",":\\");
    x=x.replace(":\\",":/");
    x=x.replace(":/",":\\");
    return x;
  }
  function pseudomodifytodisplay(x)
  {
    x=x.replace(":\\\\",":\\");
    x=x.replace(":\\",":/");
    x=x.replace(":/",":\\");
    return x;
  }
  function removeBackSlash(event,x)
  {
    event.stopPropagation();
    if(event.keyCode==220)
    {
      event.preventDefault();
      var p=x.selectionStart+1;
      x.value=x.value.substring(0,x.selectionStart)+"/"+x.value.substring(x.selectionEnd,x.value.length);
      x.selectionStart=p;
      x.selectionEnd=p;
    }
  }
  function relocate(event,x,e)
  {
    if(event.keyCode==13)
    {
      <?php
      if($_SESSION['Logged']=="administrator@gmail.com")
      {
        ?>
          if(x=="")
          {
            window.location="<?php echo $_SERVER['PHP_SELF']."?type=myPC"; ?>";
          }
          else
          {
            window.location="<?php echo $_SERVER['PHP_SELF']."?url="; ?>"+pseudomodifytourl(x);
          }
        <?php
      }
      else
      {
        ?>
          x=x.replace("\\","/");
          if(x[0]!="/")
          {
            x="/"+x;
          }
          if(x[(x.length)-1]!="/")
          {
            x=x+"/";
          }
          window.location="<?php echo $_SERVER['PHP_SELF']."?url=".$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged']; ?>"+x;
        <?php
      }
      ?>
    }
  }
</script>
</head>
<body oncontextmenu="on(event,'rightMenu','Body',this);return false;" onclick="off('rightMenu');" onmousedown="shiftedDeselect();" id="body" class="desktop" onkeydown="setKey(event);" onkeyup="unsetKey(event);">
  <script>
    var rectSelect=false;
    var rectX=0;
    var rectY=0;
    var initX=0;
    var initY=0;
  </script>
  <input type="hidden" id="appStackCount" value="0">




  <div class="virtualContainer" id="virtualContainer" onclick="hideVirtualContainer(this);">  </div>



  <div class="deskcontainer">
    <div class="topbar" id="topbar" oncontextmenu="event.stopPropagation();event.preventDefault();return false;">
      <div class="h hl" onclick="window.location='../Desktop/index.php'">
        <div class="icon icon_home"></div>
      </div>
      <input type="text" autocomplete="off" spellcheck="false" class="urladdress" id="urladdress" name="urladdress" placeholder="<?php echo $_SESSION['Logged']; ?>" value="<?php echo dfvalue(); ?>" onblur="this.value='<?php echo dfvalue(); ?>';" onkeydown="removeBackSlash(event,this);relocate(event,this.value,this);" onkeydown="event.stopPropagation();" onkeyup="event.stopPropagation();"/>


    </div>
  <div class="dc">
    <div class="opendir" oncontextmenu="event.stopPropagation();event.preventDefault();off();return false;">
      <div class="directorytree" id="directorytree">
        <div class="dtree">Directory</div>
        <?php
          if($_SESSION['Logged']=="administrator@gmail.com")
          {
        ?>
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle nb" ondblclick="window.location='<?php echo $_SERVER['PHP_SELF']."?type=myPC"; ?>';">
              <div class="st">&gt;</div>
              <span class="dtitle">My Computer</span>
            </div>
            <div class="dcontents nb">
              <?php
                getDrives();
              ?>
            </div>
            <hr class="bb" />
          </div>
        </div>
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle nb" ondblclick="window.location='<?php echo "index.php?url=C:\\\\Users/"; ?>';">
              <div class="st">&gt;</div>
              <span class="dtitle">Users</span>
            </div>
            <div class="dcontents nb">
              <?php
                getUsers();
              ?>
            </div>
            <hr class="bb" />
          </div>
        </div>





        <?php
          }
          else
          {
        ?>
        <!--Start Directory-->
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle nb" ondblclick="window.location='<?php echo $_SERVER['PHP_SELF']."?url=".$xdir."/" ?>';">
              <div class="st">&gt;</div>
              <span class="dtitle">Root Folder</span>
            </div>
            <div class="dcontents nb">
              <?php
                //Current Directory
                //getContentsFromDirectory($dir);
                //Home Directory
                getContentsFromDirectory($_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged']."/");
              ?>
            </div>
            <hr class="bb" />
          </div>
        </div>
        <!--End Directory-->
        <?php
          }
        ?>
      </div>
    </div>
    <div class="desk" onmousedown="drawRect(event,document.getElementById('topbar'),document.getElementById('selection'));" onmousemove="growRect(event,document.getElementById('topbar'),document.getElementById('selection'));" onmouseup="endRect(document.getElementById('selection'));" id="Dragcontainer" ondrag="return false;" onscroll="endRect(document.getElementById('selection'));">

<?php
if(isset($_POST['appContextDownloadFile']) && !empty($_POST['appContextDownloadFile']))
{
  $d=$_POST['appContextDownloadFile'];
  $a=explode(";",$d);
  $n=count($a)-1;
  for($i=0;$i<$n;$i++)
  {
    echo "<iframe class=\"download\" src='../Download/index.php?download=".$dir.$a[$i]."' ></iframe>";
  }
}
if(isset($_POST['uploadKey']))
{
  if(!empty($_POST['uploadKey']))
  {
    $k=$_POST['uploadKey'];
    if($k=="upFileUpload" && isset($_FILES['upFileUpload']))
    {
      $n=count($_FILES["upFileUpload"]["name"]);
      $i=0;
      $uperror=" :: ";
      while($i<$n)
      {
        $name=$_FILES["upFileUpload"]["name"][$i];
        $name=basename($name);
        $tmp=$_FILES["upFileUpload"]["tmp_name"][$i];
        $size=$_FILES["upFileUpload"]["size"][$i];
        $error=$_FILES["upFileUpload"]["error"][$i];
        if($error!=0)
        {
          //Error
          $uperror.=$name." : ";
        }
        else
        {
          //Success
          if(isset($_POST['upradio']))
          {
            if($_POST['upradio']=="skip" && @file_exists($dir.$name))
            {
              $msgcount++;
              echo "<script>setTimeout(function(){setMessage(\"warning\",\"File Exists : ".$name."\");},".($msgcount*1500).");</script>";
            }
            else
            {
              $plaintext=@file_get_contents($tmp);
              $encrypted=$plaintext;//Admin : no encryption
              if($_SESSION['Logged']!="administrator@gmail.com")
              {
                $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
                $method='aes-256-cbc';
                $password=@substr(hash('sha256', $password, true), 0, 32);
                $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
                $encrypted=@base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
              }
              if(@file_put_contents($dir.$name,$encrypted)==true)
              {
                //Success
                $msgcount++;
                echo "<script>setTimeout(function(){setMessage(\"success\",\"File Uploaded : ".$name."\");},".($msgcount*1500).");</script>";
              }
              else
              {
                //move Error
                $msgcount++;
                echo "<script>setTimeout(function(){setMessage(\"error\",\"File Upload Error on ".$name."\");},".($msgcount*1500).");</script>";
              }
            }
          }
        }
        $i++;
      }
      if($uperror!=" :: ")
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"error\",\"File Upload Error on ".$uperror."\");},".($msgcount*1500).");</script>";
      }
    }
    else if($_POST['uploadKey']=="upAllFileFolder" && isset($_FILES['upAllFileFolder']))
    {
      //Directory Upload
      $n=count($_FILES["upAllFileFolder"]["name"]);
      if($n>0)
      {
        $ndir=$dir."New Folder";
        $i=1;
        while(@file_exists($ndir))
        {
          $ndir=$dir."New Folder (".$i.")";
          $i++;
        }
        $i--;
        if(@mkdir($ndir,0777))
        {
          //$newEn=$ndir;
          $n=count($_FILES["upAllFileFolder"]["name"]);
          $i=0;
          $uperror=" :: ";
          while($i<$n)
          {
            $name=$_FILES["upAllFileFolder"]["name"][$i];
            $name=basename($name);
            $tmp=$_FILES["upAllFileFolder"]["tmp_name"][$i];
            $size=$_FILES["upAllFileFolder"]["size"][$i];
            $error=$_FILES["upAllFileFolder"]["error"][$i];
            if($error!=0)
            {
              //Error
              $uperror.=$name." : ";
            }
            else
            {
              //Success
              $plaintext=@file_get_contents($tmp);
              $encrypted=$plaintext;
              if($_SESSION['Logged']!="administrator@gmail.com")
              {
                $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
                $method='aes-256-cbc';
                $password=@substr(hash('sha256', $password, true), 0, 32);
                $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
                $encrypted=@base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
              }
              if(@file_put_contents($ndir."/".$name,$encrypted)==true)
              {
                //Success
                $msgcount++;
                echo "<script>setTimeout(function(){setMessage(\"success\",\"File Uploaded : ".$name."\");},".($msgcount*1500).");</script>";
              }
              else
              {
                //move Error
                $msgcount++;
                echo "<script>setTimeout(function(){setMessage(\"error\",\"File Upload Error on ".$name."\");},".($msgcount*1500).");</script>";
              }
            }
            $i++;
          }
          if($uperror!=" :: ")
          {
            $msgcount++;
            echo "<script>setTimeout(function(){setMessage(\"error\",\"File Upload Error on ".$uperror."\");},".($msgcount*1500).");</script>";
          }
        }
      }
    }
  }
}
if(isset($_POST['appContextPaste']) && !empty($_POST['appContextPaste']))
{
  $limit=((150*1024*1024)-getTotal($dir));
  $arr=explode("|",$_POST['appContextPaste']);
  $operation=$arr[0];
  $sourcedirectory=$arr[1];
  $destinationdirectory=$arr[(count($arr)-1)];
  $i=2;//Ignore Type , Source and Destination
  $fs=0;
  while($i<count($arr)-1)
  {
    $items[]=$arr[$i];
    $fs+=getTotal($sourcedirectory.$arr[$i]);
    $i++;
  }
  if($fs>$limit)
  {
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"warning\",\"Not Enough Space ! Free up ".byte_unit_convert($fs-$limit)."\");},".($msgcount*1500).");</script>";
  }
  else if($operation=="Copy" || $operation=="Cut")
  {
    if($items!="")
    paste($sourcedirectory,$destinationdirectory,$items,$operation);
  }
}
if(isset($_POST['appContextRename']) && !empty($_POST['appContextRename']))
{
  $v=explode("/",$_POST['appContextRename']);
  if(count($v)>=2)
  {
    $old=$v[0];
    $new=$v[1];
    if(strtolower(end(explode(".",$new)))=="fcz")
    {
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"error\",\"Forbidden Extension.\");},".($msgcount*1500).");console.log('Forbidden File Extension : fcz')</script>";
      header("Location:".$_SERVER['PHP_SELF']."?url=".$_GET['url']);
    }
    if(@file_exists($dir.$old))
    {
      if(!@file_exists($dir.$new))
      {
        if(@rename($dir.$old,$dir.$new))
        {
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"success\",\"Renamed ".$old." to ".$new." \");},".($msgcount*1500).");</script>";
        }
        else
        {
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"error\",\"Rename Error on ".$old."\");},".($msgcount*1500).");</script>";
        }
      }
      else
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"warning\",\"File Name ".$new." already Exists.\");},".($msgcount*1500).");</script>";
      }
    }
    else
    {
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"error\",\"File ".$old." does not exists.\");},".($msgcount*1500).");</script>";
    }
  }
  else
  {
    //Something Went Wrong
    //Probably Rename Delete Conflict
    //$msgcount++;
    //echo "<script>setTimeout(function(){setMessage(\"error\",\"Unknown Error occured.\");},".($msgcount*1500).");</script>";
  }
}
if(isset($_POST['appContextDelete']) && !empty($_POST['appContextDelete']))
{
  $d=$_POST['appContextDelete'];
  $delete=explode(":",$d);
  $l=count($delete)-1;
  $success="Deleted Items ";
  $error="Error Deleting Items : ";
  for($i=0;$i<$l;$i++)
  {
    if(@is_dir($dir.$delete[$i]))
    {
      if(delete($dir.$delete[$i]))
      {
        //Folder Deleted
        $success.=" : ".$delete[$i];
      }
      else
      {
        //Error Deleting Folder
        $error.=" : ".$delete[$i];
      }
    }
    else if(@file_exists($dir.$delete[$i]))
    {
      if(@unlink($dir.$delete[$i]))
      {
        //Deleted File
        $success.=" : ".$delete[$i];
      }
      else
      {
        //Error Deleting File
        $error.=" : ".$delete[$i];
      }
    }
    else
    {
      //Can't Delete , It is an App...
    }
  }
  echo "<script>\n";
  if($success!="Deleted Items ")
  {
    $msgcount++;
    echo "setTimeout(function(){setMessage(\"success\",\"".$success."\");},".($msgcount*1500).");";
  }
  else
  {
    $msgcount++;
    echo "setTimeout(function(){setMessage(\"error\",\"Delete Error\");},".($msgcount*1500).");";
  }
  if($error!="Error Deleting Items : ")
  {
    $msgcount++;
    echo "setTimeout(function(){setMessage(\"error\",\"".$error."\");},".($msgcount*1500).");";
  }
  echo "\n</script>";
}
$newEn="";
if(isset($_POST['contextNewFolder']) && $_POST['contextNewFolder']==1)
{
  $ndir=$dir."New Folder";
  $i=1;
  while(@file_exists($ndir))
  {
    $ndir=$dir."New Folder (".$i.")";
    $i++;
  }
  $i--;
  if(!@mkdir($ndir,0777))
  {
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"error\",\"Error Creating Folder.\");},".($msgcount*1500).");</script>";
  }
  else
  {
    $newEn=$ndir;
    if($i==0)
    {
      $temp="New Folder";
    }
    else
    {
      $temp="New Folder (".$i.")";
    }
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"success\",\"Folder Created : ".$temp."\");},".($msgcount*1500).");</script>";
  }
}
if(isset($_POST['contextNewFile']) && $_POST['contextNewFile']!="")
{
  $e=$_POST['contextNewFile'];
  $ndir=$dir."New File (0).".$e;
  $i=1;
  while(@file_exists($ndir))
  {
    $ndir=$dir."New File (".$i.").".$e;
    $i++;
  }
  $i--;
  $f=@fopen($ndir,"w");
  @fclose($f);
  if(@file_exists($ndir))
  {
    $temp="New File (".$i.").".$e;
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"success\",\"File Created : ".$temp."\");},".($msgcount*1500).");</script>";
  }
  else
  {
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"error\",\"Error Creating New File.\");},".($msgcount*1500).");</script>";
  }
  $newEn=$ndir;
}
if($newEn!="")
{
  $newEn=str_replace("\\","/",$newEn);
  $newEn=explode("/",$newEn);
  $newEn=$newEn[count($newEn)-1];
}
$rei="";

$xdisable="false";
if(isset($_GET['type']) && $_GET['type']=="myPC")
{
  if($_SESSION['Logged']=="administrator@gmail.com")
  {
    $xdisable="true";
    echo "<div class='drivecontainer'>";
    foreach(range('A','Z') as $d)
    {
      $freeSpace=0;
      $totalSpace=0;
      $pro=0;
      $per=0;
      $dir=$d.":\\\\";
      if(@is_dir($dir))
      {
        $freeSpace=@disk_free_space($dir);
        $totalSpace=@disk_total_space($dir);
        echo "<div class='drive' ondblclick=\"window.location='".$_SERVER['PHP_SELF']."?url=".$d.":\\\\\\\\"."';\" oncontextmenu='event.stopPropagation();return false;'><div class='icon'></div>";
        if($totalSpace>0)
        {
          $pro=$totalSpace-$freeSpace;
          if($totalSpace!=0)
          $pro/=$totalSpace;
          $per=floor($pro*10000)/100;
          $pro*=350;
        }
        else
        {
          $pro=0;
        }
        if($per>=70 || ($freeSpace<(10*1024*1024*1024)) && $totalSpace>(10*1024*1024*1024))
        {
          $stat="red";
        }
        else
        {
          $stat="green";
        }
        echo "<div class='letter'>".$d.": </div><div class='percentage ".$stat."'>".$per."% <span class='nostyle'>Used</span></div><div class='title'><div class='progressbar'><div class='progress ".$stat."' style='width:".$pro."'></div></div>";
        $vfx=byte_unit_convert($freeSpace);
        echo "<span class='bold'>".$vfx."</span> free of <span class='bold'>";
        $vfx=byte_unit_convert($totalSpace);
        echo $vfx."</span></div></div>";
      }
    }
    echo "</div>";
  }
}
else if($dh=@opendir($dir))
{
  $i=1;
  while(($file=@readdir($dh))!=false)
  {
    if($file=="."||$file=="..")
    {
      //Do Nothing
      continue;
    }
    else
    {
      $ext=end(explode(".",$file));
      //echo "<br>".$dir."/".$file." : ".file_exists($dir."/".$file);
      $skipthisfile=0;
      if(@is_dir($dir."/".$file))
      {
        echo "<div class='item' id='item_".$i."' xid='".$i."' ondblclick='window.location=\"".$_SERVER['PHP_SELF']."?url=";echo urlbaraddress().$file."/\";' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','Folder',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval=\"openFolder('".$dir.$file."');\" attr_type=\"Folder\" attr_prop=\"".urlencode($dir.$file)."\">";
        echo "<div class='icon' style='background-image:url(../Desktop/icon/icon_folder.png);background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
      }
      else if($ext=="lnk")
      {
        $dat=file_get_contents($dir."/".$file);
        $targetLocation=preg_replace('@^.*\00([A-Z]:)(?:[\00\\\\]|\\\\.*?\\\\\\\\.*?\00)([^\00]+?)\00.*$@s', '$1\\\\$2', $dat);
        echo "<div class='item' id='item_".$i."' xid='".$i."' ondblclick='window.location=\"".$_SERVER['PHP_SELF']."?url=".urlencode($targetLocation)."\";' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','Folder',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval=\"openFolder('".$targetLocation."');\" attr_type=\"Folder\" attr_prop=\"".urlencode($targetLocation)."\">";
        echo "<div class='icon' style='background-image:url(../Desktop/icon/icon_shortcut.png);background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
      }
      else if($ext=="fcz")
      {
        //Ignore App Shortcuts
        continue;
      }
      else
      {
        if($skipthisfile!=1)
        {
          $c="";
          global $ImgExt;
          $ext=end(explode(".",$file));
          if(in_array($ext,$ImgExt))
          {
            $c="imageViewer(\"".urlencode($dir.$file)."\")";
          }
          else
          {
            $c="manageFile(\"../Decrypt/index.php?getFile=".urlencode($dir.$file)."\")";
          }
          $down=$ser."/".$file;
          echo "<div class='item' ondblclick='".$c.";' id='item_".$i."' xid='".$i."' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','File',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_down='".$down."' attr_filename='".$file."' attr_eval='".$c.";'  attr_type=\"File\" attr_prop=\"".urlencode($dir.$file)."\">";
          echo "<div class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=".urlencode($ext)."&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
        }
      }
      if($newEn=="" || $newEn!=$file)
      {
        if($skipthisfile!=1)
        {
          echo "<div class='title renamableTitle' id='title_".$i."' def='".$file."' >".$file."</div></div>";
        }
      }
      else
      {
        if($skipthisfile!=1)
        {
          $rei=$i;
          echo "<div class='title renamableTitle' id='title_".$i."' def='".$file."' >".$file."</div></div>";
        }
      }
    }
    $i++;
  }
  @closedir($dh);
}
?>



    <div class="selection" id="selection"></div>

    <!--App Context-->
    <div class="rightMenu" id="appRightMenu" onmousedown="event.stopPropagation();">
      <div class="rightOptions" id="bappContextOpen" onclick="appFunctionOpen(this);"><span class="icon"><div class="icon icon_open"></div></span><span class="title">Open</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextOpenWith" onclick="appFunctionOpenWith(this);"><span class="icon"><div class="icon icon_openwith"></div></span><span class="title">Open With</span><span class="shortcut arrow"></span></div>
      <div class="rightOptions" id="bappContextCut" onclick="appFunctionCut(this);"><span class="icon"><div class="icon icon_cut"></div></span><span class="title">Cut</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextCopy" onclick="appFunctionCopy(this);"><span class="icon"><div class="icon icon_copy"></div></span><span class="title">Copy</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextDelete" onclick="appFunctionDelete(this);"><span class="icon"><div class="icon icon_delete"></div></span><span class="title">Delete</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextRename" onclick="appFunctionRename(this);"><span class="icon"><div class="icon icon_rename"></div></span><span class="title">Rename</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextDownload" onclick="appFunctionDownload(this);"><span class="icon"><div class="icon icon_download"></div></span><span class="title">Download</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextProperties" onclick="appFunctionProperties(this);"><span class="icon"><div class="icon icon_properties"></div></span><span class="title">Properties</span><span class="shortcut"></span></div>
    </div>
    <!--App Context-->

    <!--Menu Option ! Always Bottom-->
    <form id="contextForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']."?url=".$dir; ?>">

      <input type="hidden" name="appContextDelete" id="appContextDelete" value="" />
      <input type="hidden" name="appContextRename" id="appContextRename" value="" />
      <input type="hidden" name="appContextPaste" id="appContextPaste" value="" />
      <input type="hidden" name="tempPaste" id="tempPaste" value="" />
      <input type="hidden" name="appContextDownloadFile" id="appContextDownloadFile" value="" />

      <input type="hidden" id="contextNewFolder" name="contextNewFolder" value="0" />
      <input type="hidden" id="contextNewFile" name="contextNewFile" value="" />
      <input type="hidden" id="contextCut" name="contextCut" value="0" />
      <input type="hidden" id="contextCopy" name="contextCopy" value="0" />

      <input type="hidden" id="logout" name="logout" value="0" />

      <div class="rightMenu" id="rightMenu" onmouseout="hideExpandedMenu()" onmousedown="event.stopPropagation();">
        <div class="rightOptions" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?";if(isset($_GET['url'])){echo "url=".$_GET['url'];}else if(isset($_GET['type'])){echo "type=".$_GET['type'];} ?>';"><span class="icon"><div class="icon icon_refresh"></div></span><span class="title">Refresh</span><span class="shortcut">F5</span></div>
        <div class="rightOptions xzdisable" onclick="contextFunctionUpload();" onmouseover="expandMenu('Upload',this);"><span class="icon"><div class="icon icon_upload"></div></span><span class="title">Upload</span><span class="shortcut arrow"></span></div>
        <div class="rightOptions xzdisable" onclick="contextFunctionNewFolder();"><span class="icon"><div class="icon icon_folder"></div></span><span class="title">New Folder</span><span class="shortcut"></span></div>
        <div class="rightOptions xzdisable" onclick="contextFunctionNewFile('txt');" onmouseover="expandMenu('New File',this);"><span class="icon"><div class="icon icon_file"></div></span><span class="title">New File</span><span class="shortcut arrow"></span></div>
        <div class="rightOptions xzdisable" onclick="contextFunctionPaste(this);" id="bodyContextPaste"><span class="icon"><div class="icon icon_paste"></div></span><span class="title">Paste</span><span class="shortcut">Ctrl + V</span></div>
        <div class="expandedMenu xzdisable" id="expandedMenu"></div>
      </div>

    </form>
    <!--Menu Option ! Always Bottom-->
  </div>
</div>
  <div class="appmenubg" id="appmenubg" oncontextmenu="event.stopPropagation();off();return false;"></div>
  <div class="appmenu" id="appmenu" onclick="event.stopPropagation();" oncontextmenu="event.stopPropagation();return false;">
    <div class="appmenucontent" id="appmenucontent">Nothing Found !</div>
    <input class="menusearch" id="menusearch" type="text" onkeydown="event.stopPropagation();" onkeyup="event.stopPropagation();" autocomplete="off" value="" placeholder="Search in <?php echo $currentDirectory; ?>" spellcheck="false" oninput="searchmenuon(this.value);"/>
  </div>
  <div class="taskbar" id="taskbar" oncontextmenu="event.stopPropagation();event.preventDefault();return false;">
    <div class="menuContainer" onclick="event.stopPropagation();menuShowApps();"><div class="menuIcon"></div></div>
    <!-- Taskbar Items -->
  </div>
</div>


<input type="hidden" id="Ctrl" value=0 />
<input type="hidden" id="Shift" value=0 />
<form class="hiddenForm" id="uploaderform" action="<?php echo $_SERVER['PHP_SELF']."?url=".$dir; ?>" method="POST" enctype="multipart/form-data">
  <input type="file" name="upAllFileFolder[]" id="upAllFileFolder" multiple directory webkitdirectory onchange="validateUpFolder(this);">
  <input type="file" name="upFileUpload[]" id="upFileUpload" accept="*/*" onchange="validateUpFile(this);" multiple>
  <input type="hidden" name="uploadKey" id="uploadKey" value="">
</form>

<!--POPUP-->
<div class="popupbg" id="uploadpopupbg"></div>
<div class="popup" id="uploadpopup">
  <div class="topbox">
    <span class="title">Upload Manager</span>
    <button type="button" class="close closebutton" onclick="closeUploadPopup();"></button>
  </div>
<div class="content" id="xspopcontent">

</div>
<div class="bottombox" id="popupbottombox">

</div>
</div>
<!--POPUP-->

<?php
if($rei!="")
{
  ?>
  <script>
    //New File/Folder Created,  So Rename option ..
    document.getElementById("item_"+<?php echo $rei; ?>).classList.add("Selected");
    renameOk();
  </script>
  <?php
}
?>
<script>
  menuAllFilesAndFolders();
</script>
</body>
</html>
