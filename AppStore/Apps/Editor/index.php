<?php
ob_start();
session_start();
if(!(isset($_SESSION['Logged'])))
{
  header("Location:../../../Login/index.php");
}
else
{
  $dir=$_SESSION['Logged'];
}
require_once("../../../config/root.php");
if(!@is_dir($dir))
{
  $dir=realpath(getcwd()."/../../../User/Desktop/".$_SESSION['Logged']);
}
require_once("../../../config/database.php");
$conn=new mysqli($servername,$username,$password);
$msgcount=0;
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
if(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE 'editor'"))))
{
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Table not Found.\");},".($msgcount*1500).");</script>";
  die();
}
$fontmenubar="serif";
$fonteditor="serif";
$dbaccentcolor="cyan";
$dbtheme="dark";
$result=$conn->query("SELECT * FROM editor WHERE User='".$_SESSION['Logged']."'");
$n=mysqli_num_rows($result);
if($n<=0)
{
  $conn->query("INSERT INTO `editor`(`User`,`MenuBar`,`Editor`,`AccentColor`,`Theme`) VALUES('".$_SESSION['Logged']."','serif','serif','Cyan','Dark')");
}
else
{
  $result=$conn->query("SELECT * FROM editor WHERE `User`='".$_SESSION['Logged']."' LIMIT 1");
  $n=mysqli_num_rows($result);
  if($n>0)
  {
    $row=mysqli_fetch_row($result);
    $fontmenubar=$row[2];
    $fonteditor=$row[3];
    $dbaccentcolor=$row[4];
    $dbtheme=$row[5];
  }
}
if(isset($_POST['savesettings']))
{
  $conn->query("UPDATE `editor` SET `MenuBar`='".$_POST['MenuBar']."',`Editor`='".$_POST['Editor']."',`AccentColor`='".$_POST['AccentColor']."',`Theme`='".$_POST['Theme']."' WHERE `User`='".$_SESSION['Logged']."'");
  header("Location:".$_SERVER['PHP_SELF']);
}
$num="";
?>
<html>
<?php
//Image Array
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
//Image Array
$fontSize=18;
//  !! important Theme should come after Accent

if($dbaccentcolor=="Red")
{
  $Accent="#FF0000";
  $AccentDark="#C00000";
  $AccentLight="#FF8080";
  $AccentBorder="#800000";
  $NumText="#A00000";
  $skin="#ff7575";
}
else if($dbaccentcolor=="Green")
{
  $Accent="#00FF00";
  $AccentDark="#00C000";
  $AccentLight="#80FF80";
  $AccentBorder="#008000";
  $NumText="#00A000";
  $skin="#75ff75";
}
else if($dbaccentcolor=="Blue")
{
  $Accent="#0000FF";
  $AccentDark="#0000C0";
  $AccentLight="#8080FF";
  $AccentBorder="#000080";
  $NumText="#0000A0";
  $skin="#7575ff";
}
else if($dbaccentcolor=="Magenta")
{
  $Accent="#FF00FF";
  $AccentDark="#C000C0";
  $AccentLight="#FF80FF";
  $AccentBorder="#800080";
  $NumText="#A000A0";
  $skin="#ff75ff";
}
else if($dbaccentcolor=="Yellow")
{
  $Accent="#FFFF00";
  $AccentDark="#C0C000";
  $AccentLight="#FFFF80";
  $AccentBorder="#808000";
  $NumText="#A0A000";
  $skin="#ffff75";
}
else//Cyan
{
  $Accent="#00FFFF";
  $AccentDark="#00C0C0";
  $AccentLight="#80FFFF";
  $AccentBorder="#008080";
  $NumText="#00A0A0";
  $skin="#75ffff";
}
if($dbtheme=="Light")
{
  $ThemeBG="#C0C0C0";
  $EditorBG="#E0E0E0";
  $ThemeText="#000000";
  $MenuText="#000000";
  $MenuListBG="#D0D0D0";
  $MenuListText="#000000";
  //Additional Change//Shoul be after Accent
  $Border=$AccentDark;
}
else//dark
{
  $ThemeBG="#000000";
  $EditorBG="#202025";
  $ThemeText="#E0E0E0";
  $MenuText="#FFFFFF";
  $MenuListBG="#505050";
  $MenuListText="#E0E0E0";
  //Additional Change//Shoul be after Accent
  $Border=$Accent;
}


  $decrypted="";
  $encrypted="";
  $content="";
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
  if(isset($_POST['OpenFile']) && !empty($_POST['OpenFile']))
  {
    global $decrypted;
    $url=$_POST['OpenFile'];
    if(!authorised(urldecode($url),$_SESSION['Logged']))
    {
      die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;margin:40px;text-align:center;padding-bottom:20px;width:calc(100% - 100px);white-space:initial;\">You are not authorised to open this Folder.</div><div style=\"color:#FF5050;font-size:20px;padding-bottom:100px;text-align:center;\">You are Logged in as ".$_SESSION['Logged']."</div>");
    }
    if(!file_exists($url))
    {
      header("Location:".$_SERVER['PHP_SELF']);
    }
    $plaintext=@file_get_contents($url);
    $decrypted=$plaintext;
    if($_SESSION['Logged']!="administrator@gmail.com")
    {
      $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
      $method='aes-256-cbc';
      $password=@substr(hash('sha256', $password, true), 0, 32);
      $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
      $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
    }
  }
  else
  {
    $url="";
  }
  if(isset($_POST["Save"]))
  {
    if(!is_readable($url))
    {
      die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Read Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
    }
    if(!is_writable($url))
    {
      die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Write Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
    }
    if($url!="")
    {
      $content=$_POST["cont"];
      $plaintext=$content;
      $encrypted=$plaintext;
      if($_SESSION['Logged']!="administrator@gmail.com")
      {
        $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
        $method='aes-256-cbc';
        $password=substr(hash('sha256', $password, true), 0, 32);
        $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
        $encrypted=base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
      }
      if(!file_put_contents($url,$encrypted)==true)
      {
        echo "Error Saving File";
        //$msgcount++;
        //echo "<script>setTimeout(function(){setMessage(\"error\",\"File Upload Error on ".$name."\");},".($msgcount*1500).");</script>";
      }
    }
  }
  else if(isset($_POST["edit"]))
  {
    $content=$_POST["cont"];
  }
  else
  {
    if($url!="")
    {
      if(!is_readable($url))
      {
        die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Read Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
      }
      if(!is_writable($url))
      {
        die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Write Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
      }
      else if(!($content=file_get_contents($url)))
      {
        $content="";
      }
      else
      {
        $plaintext=$content;
        if($_SESSION['Logged']!="administrator@gmail.com")
        {
          $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
          $method='aes-256-cbc';
          $password=@substr(hash('sha256', $password, true), 0, 32);
          $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
          $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
          $content=$decrypted;
        }
      }
    }
  }
  if($url!="")
  {
    if(!($content=file_get_contents($url)))
    {
      $content="";
    }
    else
    {
      $plaintext=$content;
      if($_SESSION['Logged']!="administrator@gmail.com")
      {
        $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
        $method='aes-256-cbc';
        $password=@substr(hash('sha256', $password, true), 0, 32);
        $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
        $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
        $content=$decrypted;
      }
    }
  }
if($content!="")
{
  $nc=substr_count($content,PHP_EOL);
  $fc=explode(PHP_EOL,$content);
  $i=1;
  $num="";
  while($i<$nc)
  {
    $num.=$i++.'
';
  }
}
else
{
  $num="1
  ";
}

  if($url!="")
  {
    $FileName=end(explode("/",$url));
    $FileExt=end(explode(".",$FileName));
    $FileType=strtolower($FileExt);
    $FileExt=".".$FileExt;
    $fileModifiedDate=date("d/F/Y H:i:s", filemtime($url));
    $FileProperties="";
    if(is_readable($url))
    {
      $FileProperties.="-r ";
    }
    if(is_writable($url))
    {
      $FileProperties.="-w ";
    }
    if(is_executable($url))
    {
      $FileProperties.="-x ";
    }
  }
  else
  {
    $FileName="[ No File Opened ]";
    $FileExt="-";
    $FileType="-";
    $FileExt="-";
    $fileModifiedDate="-";
    $FileProperties="-";
  }
  function getDirData($d)
  {
    $files='';
    if($dh=opendir($d))
    {
      while(($file=readdir($dh))!=false)
      {
        if($file=="."||$file=="..")
        {
          continue;
        }
        else if(is_dir($d."/".$file))
        {
          $files[]=$d."/".$file;
        }
      }
    }
    if($dh=opendir($d))
    {
      while(($file=readdir($dh))!=false)
      {
        if($file=="."||$file=="..")
        {
          continue;
        }
        else if(is_dir($d."/".$file))
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
      if(is_dir($d.$file))
      {
        ?>
          <div class="directory">
            <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
              <div class="xtitle">
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
          <div class="directory" onclick="')">
            <div class="xtcontainer" onclick="togglefolder(event,this);" ondblclick="openfile('<?php echo $d.$file."','".$ext; ?>');" tabindex="0">
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
?>
<head>
<title>Editor</title>
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no">
<meta name="theme-color" content="<?php echo $ThemeBG; ?>">
<style>
*
{
  margin:0;
  padding:0;
}
*:focus
{
  outline:none;
}
body
{
  overflow:hidden;
  background-color:<?php echo $ThemeBG; ?>;
}
div.MainContainer
{
  width:100%;
  height:100%;
  display:flex;
  flex-wrap:nowrap;
  flex-direction:column;
}
div.FrameContainer
{
  width:100%;
  height:100%;
  display:flex;
  flex-wrap:nowrap;
}
div.SubContainer
{
  width:100%;
  height:100%;
  display:flex;
  flex-wrap:nowrap;
}
div.PreContainer
{
  width:50%;
  height100%;
  display:flex;
  flex-wrap:nowrap;
  flex-direction:column;
  z-index:10;
  border-left:1px solid <?php echo $Accent; ?>;
}
textarea.edtext
{
	margin:0;
	outline:none;
	resize:none;
	border:none;
	height:100%;
	background-color:<?php echo $EditorBG; ?>;
	font-size:18px;
	line-height:1.4em;
	//white-space:nowrap;
	padding-top:10px;
	padding-bottom:5px;
}
textarea.txt
{
  width:50%;
  color:<?php echo $ThemeText; ?>;
	padding-left:10px;
	padding-right:10px;
	white-space:nowrap;
  letter-spacing:1.5px;
}
textarea
{
  font-family:<?php echo $fonteditor; ?>;
}
textarea.txt::selection
{
  color:<?php echo $EditorBG; ?>;
  background-color:<?php echo $Accent; ?>;
}
textarea.num
{
  height:100%;
  color:<?php echo $NumText; ?>;
	padding-left:0px;
	padding-right:5px;
	text-align:right;
  user-select:none !important;
	border-right:1px solid <?php echo $Border; ?>;
	white-space:wrap;
}
div.ResizeButtonContainer
{
  width:0px;
  overflow:visible;
  z-index:5;
  border:none;
  outline:none;
}
input.hidePreview
{
  position:relative;
  top:50%;
  right:0%;
  width:40px;
  padding:8px;
  border-top:1px solid <?php echo $Accent; ?>;
  border-left:1px solid <?php echo $Accent; ?>;
  border-bottom:1px solid <?php echo $Accent; ?>;
  border-right:none;
  background-color:<?php echo $ThemeBG; ?>;
  color:<?php echo $Accent; ?>;
  font-family:monospace;
  border-radius:100px 0% 0% 100px;
  transform:translate(0%,-50%);
  transition:transform 1s,box-shadow 1s;
}
iframe
{
  width:100%;
  height:45%;
  border:none;
  background-color:#FFFFFF;
  overflow:auto;
  z-index:9;
  min-width:100%;
  min-height:45%;
  max-width:100%;
  resize:both;
}
div.status
{
  width:100%;
  height:55%;
  background-color:<?php echo $EditorBG; ?>;
  color:<?php echo $ThemeText; ?>;
  padding:10px;
  overflow-y:auto;
  overflow-x:hidden;
}
div.menuset
{
	width:100%;
	background-color:<?php echo $ThemeBG; ?>;
	border-bottom:1px solid <?php echo $Border; ?>;
	z-index:15;
	box-shadow:0px 0px 10px <?php echo $Border; ?>;
  user-select:none;
}
div.menuset
{
  font-family:<?php echo $fontmenubar; ?>;
}
div.menuset>div.menu
{
	position:relative;
	top:0;
	left:0;
	text-align:center;
	min-width:80px;
	display:inline-block;
	float:left;
	color:<?php echo $MenuText; ?>;
	border-right:1px solid <?php echo $MenuText; ?>;
	text-transform:uppercase;
	padding-top:3px;
	padding-bottom:3px;
  user-select:none;
}
div.menu>div.list
{
	position:absolute;
	z-index:10;
	display:none;
	width:auto;
	min-width:160px;
}
div.menuset>div.menu>div.list>button
{
	display:block;
	width:100%;
	text-align:left;
	padding:5px;
	background-color:<?php echo $MenuListBG; ?>;
	color:<?php echo $MenuListText; ?>;
	border:1px solid #000000;
	margin:0;
}
div.menu>div.list>button>span.r
{
	float:right;
}
div.menu:focus div.list
{
	display:block;
}
div.menu div.list:hover
{
	display:block;
}
div.menu:focus
{
	color:<?php echo $MenuText; ?>;
	background-color:<?php echo $MenuListBG; ?>;
}
div.menu:hover
{
  cursor:pointer;
	color:<?php echo $MenuText; ?>;
	background-color:<?php echo $MenuListBG; ?>;
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
div.popup
{
	width:85vmin;
	height:55vmin;
	background-color:<?php echo $AccentLight; ?>;
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
	background-color:<?php echo $AccentLight; ?>;
}
div.popup button
{
  font-weight:400;
  font-size:20px;
}
div.popup table tr td select
{
  width:100%;
}
div.popup div.topbox
{
	position:relative;
	top:0;
	right:0;
	width:100%;
	height:auto;
	background-color:<?php echo $AccentDark; ?>;
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
  background-image:url("icon_close.svg");
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
div.popup div.bottombox button
{
	width:28%;
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
  padding-top: 0;
	overflow:hidden;
	z-index:37;
}
div.popup table
{
	width:100%;
}
div.popup table td
{
	padding:5px;
  width:calc(100% / 3);
}
div.popup div.content fieldset
{
	padding:5px;
  margin-bottom:5px;
	display:block;
	border:2px solid <?php echo $AccentBorder; ?>;
}
div.popup .redbutton
{
	border:1px solid #900000;
	background:linear-gradient(135deg,#C00000,#F06868,#C00000);
}
div.popup .redbutton:hover
{
	background:linear-gradient(135deg,#FF0000,#FF0000,#FF0000);
}
div.popup .yellowbutton
{
	border:1px solid #909000;
	background:linear-gradient(135deg,#C0C000,#F0F068,#C0C000);
}
div.popup .yellowbutton:hover
{
	background:linear-gradient(135deg,#FFFF00,#FFFF00,#FFFF00);
}
div.popup .greenbutton
{
	border:1px solid #009000;
	background:linear-gradient(135deg,#00C000,#68F068,#00C000);
}
div.popup .greenbutton:hover
{
	background:linear-gradient(135deg,#00FF00,#00FF00,#00FF00);
}
table.tstatus
{
  width:100%;
  color:<?php echo $ThemeText; ?>;
  font-size:20px;
}
table.tstatus td
{
  padding:5px;
  box-sizing:border-box;
}
body::-webkit-scrollbar,textarea.num::-webkit-scrollbar
{
  display:none;
}
textarea.txt::-webkit-scrollbar
{
  width:8px;
  height:8px;
}
textarea.txt::-webkit-scrollbar-track
{
  border-radius: 100px;
  background-color:transparent;
  border:1px solid #C0C0C0;
}
textarea.txt::-webkit-scrollbar-thumb
{
  background-color:rgba(128,128,128,0.8);
  border-radius:100px;
}
textarea.txt::-webkit-scrollbar-thumb:hover
{
  background-color:#FFFFFF;
  border:1px solid #000000;
}
textarea.txt::-webkit-scrollbar-button
{
  background-color:rgba(0,0,0,0.5);
  width:8px;
  height:8px;
  border-radius:100px;
  border:1px solid #C0C0C0;
}
textarea.txt::-webkit-scrollbar-button:hover
{
  background-color:#C0C0C0;
  border:1px solid rgba(0,0,0,0.5);
}
div.directorytree
{
  width:320px;
  height:100%;
  border-right:1px solid <?php echo $AccentBorder; ?>;
  background-color:rgba(128,128,128,0.2);
  user-select:none;
  overflow-x:auto;
  overflow-y:auto;
  position:relative;
  display:none;
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
div.xtcontainer:focus::before
{
  content:"";
  width:100vmax;
  height:32px;
  background-color:#3f51b5;
  position:absolute;
  left:0;
  z-index:-100;
}
div.xtcontainer:focus::before
{
  background-color:<?php echo $skin; ?>;
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
div.xtcontainer:focus,div.xtcontainer:focus>.xtitle>.st,div.xtcontainer:focus>.xtitle>.str
{
  color:#000000;
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
</style>
<script>
var ignoreScrollEvents=false;
var UnsupportedExt=["jpg","jpeg","svg","png","gif","bmp","svgz","tiff","exif","ico","zip","rar","docx","doc","xls","xlsx","ppt","pptx","pdf","psd","ai"];
function _(id)
{
  return document.getElementById(id);
}
function xreloadiframe()
{
  <?php
    echo "var axtemp=encodeURIComponent(document.getElementById('txt').value);";
    if($FileType=="html")
    {
      echo "document.getElementById('pre').src='data:text/html;charset=utf-8,'+axtemp;";
    }
    else if($FileType=="svg")
    {
      echo "document.getElementById('pre').src='data:image/svg+xml;charset=utf-8,'+axtemp;";
    }
    else if($FileType=="txt")
    {
      echo "document.getElementById('pre').src='data:text/plain;charset=utf-8,'+axtemp;";
    }
    else
    {
      echo "document.getElementById('pre').src='data:text/html;charset=utf-8,<html><body style=\"color:rgb(255,100,100);font-size:30px;text-align:center;padding-top:100px;\">No File Opened or No Preview Available.<hr style=\"border-color:rgb(255,100,100);\"></body></html>';";
    }
  ?>
}
function insertSpanTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+15;
  var p="<span class=\"\"></span>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  xreloadiframe();
}
function insertInput()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+13;
  var p="<input type=\"text\" value=\"\" placeholder=\"\" class=\"\" />";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+4;
  xreloadiframe();
}
function insertButton()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+42;
  var p="<button type=\"button\" onclick=\"\" class=\"\">Button</button>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+6;
  xreloadiframe();
}
function insertTextarea()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+34;
  var p="<textarea placeholder=\"\" class=\"\"></textarea>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  xreloadiframe();
}
function insertDivTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+14;
  var p="<div class=\"\"></div>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  xreloadiframe();
}
function insertPTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+12;
  var p="<p class=\"\"></p>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  xreloadiframe();
}
function insertAnchorTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+9;
  var p="<a href=\"url\" target=\"_blank\" class=\"\">Link</a>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+3;
  xreloadiframe();
}
function insertImageTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+10;
  var p="<img src=\"url\" alt=\"\" class=\"\"/>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+3;
  xreloadiframe();
}
function insertColor(p)
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+7;
  if((txt.value.substring(end,end+1))!=";")
  {
    p+=";";
    to++;
  }
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  xreloadiframe();
}
function updateStatus()
{
  var fsb=parseInt(document.getElementById('txt').value.length);
  var Size="";
  var byteA=[" B"," KB"," MB"," GB"," TB"," PB"," EB"," ZB"," YB"];
  var bi=0;
  if(fsb<=1024)
    Size=fsb;
  while(fsb>1024)
  {
    bi++;
    fsb/=1024;
    Size=fsb.toFixed(2);
  }
  if(bi<9)
  {
    Size+=byteA[bi];
  }
  else
  {
    Size="Too Large";
  }
  document.getElementById('fname').innerHTML="<?php echo $FileName; ?>";
  document.getElementById('fpath').innerHTML="Root<?php if($url!="") echo end(explode($_SESSION['Logged'],$url));else echo "/"; ?>";
  document.getElementById('fsize').innerHTML=Size;
  document.getElementById('fsizeb').innerHTML=document.getElementById('txt').value.length+" B";
  document.getElementById('ftype').innerHTML="<?php echo strtoupper($FileType); ?>";
  document.getElementById('fext').innerHTML="<?php echo $FileExt; ?>";
  document.getElementById('fmd').innerHTML="<?php echo $fileModifiedDate; ?>";
  document.getElementById('fproperties').innerHTML="<?php echo $FileProperties; ?>";
}
function togglefolder(event,x)
{
  event.stopPropagation();
  x.parentNode.classList.toggle("maxfolder");
}
function openfile(file,ext)
{
  if(UnsupportedExt.indexOf(ext)!=-1)
  {
    alert("Unsupported File Type.");
    return;
  }
  else
  {
    _("OpenFile").value=file;
    _("Editor").submit();
  }
}
function updatePreview()
{
  <?php
    echo "//".$FileType."\n";
    if($FileType=="html")
    {
      echo "var axtemp=encodeURIComponent(document.getElementById('txt').value);";
      echo "document.getElementById('pre').src='data:text/html;charset=utf-8,'+axtemp;";
      //echo "document.getElementById('pre').src=\"".$url."\"";
    }
    else if($FileType=="svg")
    {
      echo "var axtemp=encodeURIComponent(document.getElementById('txt').value);";
      echo "document.getElementById('pre').src='data:image/svg+xml;charset=utf-8,'+axtemp;";
      //echo "document.getElementById('pre').src=\"".$url."\"";
    }
    else if($FileType=="txt")
    {
      echo "var axtemp=encodeURIComponent(document.getElementById('txt').value);";
      echo "document.getElementById('pre').src='data:text/plain;charset=utf-8,'+axtemp;";
    }
    else
    {
      echo "document.getElementById('pre').src='data:text/html;charset=utf-8,<html><body style=\"color:rgb(255,100,100);font-size:30px;text-align:center;padding-top:100px;\">No File Opened or No Preview Available.<hr style=\"border-color:rgb(255,100,100);\"></body></html>';";
    }
    //echo "var z=parseInt(document.getElementById('z').value);\n  ++z;\n  ";
    /////////////echo "document.getElementById('z').value=z;";
    //echo "document.getElementById('pre').src=\"".$url."?z=\"+z;\n";
  ?>
}
function keychanged(s)
{
	let text=document.getElementById("txt").value;
	let tlines=text.split("\n");
	let tcount=tlines.length;
	let n=document.getElementById("num").value;
	let nlines=n.split("\n");
	let ncount=nlines.length;
	let nlast=nlines[ncount-2];
	let cn=0;
	if(nlast>tcount)
	{
		document.getElementById("num").value="";
		for(let i=0;i<tcount;)
		{
			document.getElementById("num").value+=(++i)+"\n";
		}
	}
	else
	{
		for(let i=nlast;i<tcount;)
		{
			document.getElementById("num").value+=(++i)+"\n";
		}
	}
	tcount=parseInt(tcount);
	if(tcount == 0)
	cn=1;
	else
	cn=0;
	while(tcount>0)
	{
		tcount/=10;
		tcount=parseInt(tcount);
		cn++;
	}
  if(cn<=1)
  cn=1;
  else
  cn--;
	document.getElementById('num').cols=cn;
  updateStatus();
	updatePreview();
}
function showPbutton()
{
  document.getElementById("hidePreviewButton").style.transform="translate(-100%,-50%)";
  document.getElementById("hidePreviewButton").style.boxShadow="0px 0px 12px 0px <?php echo $Accent; ?>";
}
function hidePbutton()
{
  document.getElementById("hidePreviewButton").style.transform="translate(0%,-50%)";
  document.getElementById("hidePreviewButton").style.boxShadow="none";
}
function changePreview(b)
{
  if(b.value=="<")
  {
    b.value=">";
    document.getElementById("preCont").style.width="50%";
    document.getElementById("txt").style.width="50%";
    document.getElementById("hbuttonContainer").style.width="0%";
  }
  else
  {
    b.value="<";
    document.getElementById("preCont").style.width="0%";
    document.getElementById("txt").style.width="100%";
    document.getElementById("hbuttonContainer").style.width="1px";
  }
}
function openPopup()
{
	document.getElementById("settingspopup").style.display="block";
	document.getElementById("settingspopupbg").style.display="block";
	if(typeof(Storage) !== "undefined")
	{
		sessionStorage.cursorStart=document.getElementById("txt").selectionStart;
		sessionStorage.cursorEnd=document.getElementById("txt").selectionEnd;
	}
	document.getElementById("txt").blur();
}
function closePopup()
{
	document.getElementById("settingspopup").style.display="none";
	document.getElementById("settingspopupbg").style.display="none";
	if(typeof(Storage) !== "undefined")
	{
		document.getElementById("txt").selectionStart=parseInt(sessionStorage.cursorStart);
		document.getElementById("txt").selectionEnd=parseInt(sessionStorage.cursorEnd);
	}
}
function exe(command)
{
	document.execCommand(command,null,null);
}
function scrollAll(e,event)
{
	if(ignoreScrollEvents==true)
	{
		ignoreScrollEvents=false;
		return;
	}
	else
	{
		ignoreScrollEvents=true;
		document.getElementById('num').scrollTop=e.scrollTop;
	}
}
function scrollN()
{
	if(ignoreScrollEvents==true)
	{
		ignoreScrollEvents=false;
		return;
	}
	else
	{
		ignoreScrollEvents=true;
		if(document.getElementById('num').scrollTop>=document.getElementById('txt').scrollTop)
		{
			document.getElementById('txt').scrollTop=document.getElementById('num').scrollTop;
			document.getElementById('num').scrollTop=document.getElementById('txt').scrollTop;
		}
		else
		{
			document.getElementById('txt').scrollTop=document.getElementById('num').scrollTop;
		}
	}
}
function fontSize(operator)
{
	var size=<?php echo $fontSize; ?>;
	if(typeof(Storage) !== "undefined")
	{
		if(sessionStorage.fontSize)
		{
			size=parseInt(sessionStorage.fontSize);
			if(isNaN(size))
			{
				return;
			}
			if(operator=="+")
			{
				size++;
			}
			else if(operator=="-")
			{
				size--;
			}
			else
			{
				size=<?php echo $fontSize; ?>;
			}
			if(size<10)
			{
				alert("Minimum Font size reached");
				size=10;
			}
			else if(size>45)
			{
				alert("Maximum Font size reached");
				size=45;
			}
			sessionStorage.fontSize=size;
		}
		else
		{
			sessionStorage.fontSize=<?php echo $fontSize; ?>;
		}
		size=sessionStorage.fontSize;
	}
	document.getElementById("txt").style.fontSize=size+"px";
	document.getElementById("num").style.fontSize=size+"px";
}
function saveCursor()
{
	var x = document.createElement("INPUT");
	x.setAttribute("type", "hidden");
	x.setAttribute("name", "Cursor");
	x.setAttribute("form", "Editor");
	x.setAttribute("value", document.getElementById("txt").selectionEnd);
	document.body.appendChild(x);
}
function keypressed(event,txt)
{
	if(event.keyCode==16 || event.which==16)
	{
		document.getElementById("Shift").value="ON";
	}
	if(event.keyCode==17 || event.which==17)
	{
		document.getElementById("Ctrl").value="ON";
	}
	if(event.keyCode==18 || event.which==18)
	{
    event.preventDefault();
		document.getElementById("Alt").value="ON";
	}
	if(event.keyCode==83 || event.which==83)
	{
		if(document.getElementById("Ctrl").value=="ON")
		{
			var x = document.createElement("INPUT");
			event.preventDefault();
			x.setAttribute("type", "hidden");
			x.setAttribute("name", "Save");
			x.setAttribute("form", "Editor");
			document.body.appendChild(x);
			saveCursor();
			document.getElementById("Editor").submit();
		}
	}
	if((event.keyCode==219 || event.which==219) && (document.getElementById("Shift").value=="ON"))
	{
		event.preventDefault();
		var txt=document.getElementById("txt");
		var to=0,start=txt.selectionStart,end=txt.selectionEnd,p="";
		if(txt.value.substring(start-1,start)!="\n")
		{
			p="\n{\n\t\n}";
			to=start+4;
		}
		else
		{
			p="{\n\t\n}";
			to=start+3;
		}
		if(txt.value.substring(end,end+1)!="\n")
		{
			p+="\n";
		}
		txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
		document.getElementById("txt").selectionStart=to;
		document.getElementById("txt").selectionEnd=to;
	}
	if(event.keyCode==9 || event.which==9)
	{
		event.preventDefault();
		cursor=txt.selectionEnd;
		if(document.getElementById("Shift").value=="OFF")
		{
			txt.value=txt.value.substring(0,txt.selectionStart)+"\t"+txt.value.substring(txt.selectionEnd,txt.value.length);
			document.getElementById("txt").selectionEnd=cursor+1;
		}
		else if(document.getElementById("Shift").value=="ON")
		{
			if(txt.value.substring(txt.selectionStart-1,txt.selectionStart)=="\t")
			{
				txt.value=txt.value.substring(0,txt.selectionStart-1)+txt.value.substring(txt.selectionEnd,txt.value.length);
				document.getElementById("txt").selectionEnd=cursor-1;
			}
		}
	}
	if(event.keyCode==70 && _("Alt").value=="ON" && _("Ctrl").value=="ON")//Ctrl+Alt+F
  {
    _("menuFile").focus();
  }
	if(event.keyCode==69 && _("Alt").value=="ON" && _("Ctrl").value=="ON")//Ctrl+Alt+E
  {
    _("menuEdit").focus();
  }
	if(event.keyCode==86 && _("Alt").value=="ON" && _("Ctrl").value=="ON")//Ctrl+Alt+V
  {
    _("menuView").focus();
  }
	if(event.keyCode==73 && _("Alt").value=="ON" && _("Ctrl").value=="ON")//Ctrl+Alt+I
  {
    _("menuInsert").focus();
  }
}
function release(event)
{
	if(event.keyCode==16 || event.which==16)
	document.getElementById("Shift").value="OFF";
	else if(event.keyCode==17 || event.which==17)
	document.getElementById("Ctrl").value="OFF";
	else if(event.keyCode==18 || event.which==18)
	document.getElementById("Alt").value="OFF";
}
function setCursor(pos)
{
	document.getElementById('txt').selectionStart=parseInt(pos);
}
function menuFileOpen()
{
  _("directorytree").style.display="block";
}
function setMessage(type,message)
{
  var x=document.createElement("P");
  x.setAttribute("class",type);
  x.innerHTML=message;
  var b=document.getElementById("body");
  b.insertBefore(x,b.firstChild);
}
</script>
</head>
<body>
<input type="hidden" value ="1" id="z">
<input type="color" style="display:none;" id="colorselector" value="#FF0000" onchange="insertColor(this.value);">
<form id="Editor" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="saveCursor();">
<input type="hidden" value ="<?php echo $url; ?>" id="OpenFile" name="OpenFile">
  <input type="hidden" id="Shift" value="OFF">
  <input type="hidden" id="Ctrl" value="OFF">
  <input type="hidden" id="Alt" value="OFF">
  <div class="MainContainer">
    <div class="menuset">
      <div class="menu" name="File" id="menuFile" tabindex="0">File<div class="list"><button type="button" onclick="menuFileOpen();">Open</button><button name="Save">Save<span class='r'>Ctrl + S</span></button></div></div>
      <div class="menu" name="Edit" id="menuEdit" tabindex="0">Edit<div class="list"><button onclick="exe('undo');" name="edit" type="button">Undo<span class='r'>Ctrl + Z</span></button><button onclick="exe('redo');" name="edit" type="button">Redo<span class='r'>Ctrl + Y</span></button><button type="button" onclick="document.getElementById('txt').select();">Select All<span class='r'>Ctrl + A</span></button><button onclick="exe('cut');" name="edit" type="button">Cut<span class='r'>Ctrl + X</span></button><button onclick="exe('copy');" name="edit" type="button">Copy<span class='r'>Ctrl + C</span></button></div></div>
      <div class="menu" name="View" id="menuView" tabindex="0">View<div class="list"><button type="button" onclick="openPopup()">Editor Settings</button></div></div>
      <div class="menu" name="Insert" id="menuInsert" tabindex="0">Insert<div class="list"><button type="button" onclick="_('colorselector').click();">Color</button><button type="button" onclick="insertImageTag();">Image Tag</button><button type="button" onclick="insertInput();">Input</button><button type="button" onclick="insertTextarea();">Textarea</button><button type="button" onclick="insertButton();">Button</button><button type="button" onclick="insertDivTag();">Division Tag</button><button type="button" onclick="insertSpanTag();">Span Tag</button><button type="button" onclick="insertPTag();">Paragraph Tag</button><button type="button" onclick="insertAnchorTag();">Anchor Tag</button></div></div>
    </div>
    <div class="FrameContainer">
      <div class="directorytree" id="directorytree">
        <div class="dtree">Directory</div>
        <!--Directory-->
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle nb">
              <div class="st">&gt;</div>
              <span class="dtitle">Root Folder</span>
            </div>
            <div class="dcontents nb">
              <?php
                getContentsFromDirectory($dir);
              ?>
            </div>
            <hr class="bb" />
          </div>
        </div>
        <!--Directory-->
      </div>
      <textarea class="edtext num" name="Num" id="num" cols=1 readonly onscroll='scrollN()' onfocus="this.blur;"><?php echo $num; ?></textarea>
      <div class="SubContainer">
        <textarea class="edtext txt" name='cont' id="txt" autofocus onscroll='scrollAll(this,event)' onkeydown='keypressed(event,this)' oninput='keychanged(0)' onkeyup='release(event)' autocorrect='off' autocapitalize='off' spellcheck='false'><?php echo $content; ?></textarea>
        <div class="ResizeButtonContainer" id="hbuttonContainer" onmouseover="showPbutton();" onmouseout="hidePbutton();">
          <input type="button" class="hidePreview" value="<" id="hidePreviewButton" onmouseover="showPbutton();" onmouseout="hidePbutton();" onclick="changePreview(this)">
        </div>
        <div class="PreContainer" onmouseover="showPbutton();" onmouseout="hidePbutton();" id="preCont">
          <iframe class="Preview" id="pre" sandbox src="data:text/html;charset=utf-8,<html><body style='color:rgb(255,100,100);font-size:30px;text-align:center;padding-top:100px;'>No File Opened or No Preview Available.<hr style='border-color:rgb(255,100,100);'></body></html>"></iframe>
          <div class="status">
            <table class="tstatus">
              <tr>
                <td>File Name</td>
                <td>:</td>
                <td id="fname">Untitled.html</td>
              </tr>
              <tr>
                <td>File Path</td>
                <td>:</td>
                <td id="fpath">/Untitled.html</td>
              </tr>
              <tr>
                <td>File Size</td>
                <td>:</td>
                <td id="fsize">0 B</td>
              </tr>
              <tr>
                <td>File Size (Bytes)</td>
                <td>:</td>
                <td id="fsizeb">0 B</td>
              </tr>
              <tr>
                <td>File Type</td>
                <td>:</td>
                <td id="ftype">HTML</td>
              </tr>
              <tr>
                <td>File Extension</td>
                <td>:</td>
                <td id="fext">.html</td>
              </tr>
              <tr>
                <td>Last Modified</td>
                <td>:</td>
                <td id="fmd"></td>
              </tr>
              <tr>
                <td>File Properties</td>
                <td>:</td>
                <td id="fproperties">-</td>
              </tr>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</form>
<div class="popupbg" id="settingspopupbg"></div>
<div class="popup" id="settingspopup">
  <div class="topbox">
    <span class="title">Settings</span>
    <button type="button" class="close" onclick="closePopup();"></button>
  </div>
<div class="content">
  <form id="Settings" name="Settings" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="saveCursor();">
    <br>
    <fieldset>
      <legend>&nbsp;&nbsp;Font&nbsp;&nbsp;</legend>
      <table border=0>
        <tr>
          <td>Menu Bar</td>
          <td>:</td>
          <td><select name="MenuBar"><option>Arial</option><option>Arial Black</option><option>Serif</option><option>Sans-Serif</option><option>Monospace</option><option>Cursive</option><option>Helvetica</option><option>Times New Roman</option><option>Times</option><option>Courier New</option><option>Courier</option><option>Verdana</option><option>Georgia</option><option>Palatino</option><option>Garamond</option><option>Bookman</option><option>Comic Sans MS</option><option>Trebuchet MS</option><option>Impact</option></select></td>
        </tr>
        <tr>
          <td>Editor</td>
          <td>:</td>
          <td><select name="Editor"><option>Arial</option><option>Arial Black</option><option>Serif</option><option>Sans-Serif</option><option>Monospace</option><option>Cursive</option><option>Helvetica</option><option>Times New Roman</option><option>Times</option><option>Courier New</option><option>Courier</option><option>Verdana</option><option>Georgia</option><option>Palatino</option><option>Garamond</option><option>Bookman</option><option>Comic Sans MS</option><option>Trebuchet MS</option><option>Impact</option></select></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend>&nbsp;&nbsp;Theme&nbsp;&nbsp;</legend>
      <table>
        <tr>
          <td>Select Accent Color</td>
          <td>:</td>
          <td><select name="AccentColor"><option selected>Cyan</option><option>Magenta</option><option>Yellow</option><option>Red</option><option>Green</option><option>Blue</option></select></td>
        </tr>
          <tr>
            <td>Select Theme</td>
            <td>:</td>
            <td><select name="Theme"><option selected>Dark</option><option>Light</option></td>
          </tr>
      </table>
    </fieldset>
  </form>
</div>
<div class="bottombox"><center><button class="redbutton" onclick="closePopup();" form="">Cancel</button><button form="Settings" class="yellowbutton" type="reset">Reset</button><button form="Settings" class="greenbutton" type="submit" name="savesettings">Save</button></center></div>
</div>

<script>
  document.getElementsByTagName('title')[0].innerHTML='Editor : <?php echo $FileName; ?>';
  if(window.history.replaceState)
  {
    window.history.replaceState(null,null,window.location.href);
  }
  <?php
    if(isset($_POST['Cursor']))
    {
      echo "setCursor(".$_POST['Cursor'].");";
    }
  ?>
	keychanged(0);
  changePreview(document.getElementById("hidePreviewButton"));
</script>
</body>
</html>
