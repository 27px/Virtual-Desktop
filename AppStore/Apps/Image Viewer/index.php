<?php
ob_start();
session_start();
if(!(isset($_SESSION['Logged'])))
{
  die("<div style='color:#FF6060;font-size:45px;border-bottom:3px solid #FF6060;padding:10px;text-align:center;margin-left:50px;margin-right:50px;'>You are not Logged In.</div>");
}
else
{
  $dir=$_SESSION['Logged'];
  $msgcount=-1;
}
require_once("../../../config/root.php");//Server Root
if(!@is_dir($dir))//Backup Server Root Alternative in Windows
{
  $dir=realpath(getcwd()."/../../../User/Desktop/".$_SESSION['Logged']);

  $ser=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/".$root."User/Desktop/".$_SESSION['Logged'];
}
$imagecounter=0;
function putImage($url,$type)
{
  global $imagecounter;
  $imagecounter++;
  if($type=="svg" || $type=="svg+xml")
  {
    $class="image svg";
  }
  else
  {
    $class="image realimage";
  }
  $plaintext=@file_get_contents($url);
  $imageData=base64_encode($plaintext);//For Admin No Decryption necessary
  if($_SESSION['Logged']!="administrator@gmail.com")
  {
    $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
    $method='aes-256-cbc';
    $password=@substr(hash('sha256', $password, true), 0, 32);
    $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
    $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
    $imageData=base64_encode($decrypted);
  }
  $url='data:image/'.$type.';base64,'.$imageData;
  echo "<div class='impre ".$class."' onclick=\"showImage('".$url."','".$imagecounter."');\" id='image_".$imagecounter."' attr_num='".$imagecounter."' style='background-image:url(\"".$url."\");background-size:contain;'></div>";
}
$ImgExt=array();
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
function getImage($x)
{
  global $dir;
  global $ser;
  global $ImgExt;
  if($dh=@opendir($dir.$x))
  {
    while(($file=@readdir($dh))!=false)
    {
      if($file=="."||$file=="..")
      {
        continue;
      }
      else if(@is_dir($dir.$x.$file))
      {
        getImage($file."/");
      }
      else
      {
        $ext=explode(".",$file);
        $ext=end($ext);
        if($ext=="svg")
        {
          putImage($dir.$x.$file,"svg+xml");
        }
        else if(in_array($ext,$ImgExt))
        {
          putImage($dir.$x.$file,$ext);
        }
        ob_flush();
        flush();
      }
    }
    @closedir($dh);
  }
}
?>
<html>
<head>
<style>
*
{
  padding:0;
  margin:0;
}
body
{
  padding-bottom:10px;
  background-image:url("bg.jpg");
  background-attachment:fixed;
}
body::-webkit-scrollbar
{
  width:10px;
  background-color:#C0C0C0;
  border-radius:50px;
}
body::-webkit-scrollbar-thumb
{
  background-color:#808080;
  border-radius:50px;
}
body::-webkit-scrollbar-thumb:hover
{
  background-color:#000000;
}
div.image
{
  width:23.78%;
  padding-top:23.78%;
  margin-left:1%;
  margin-top:1%;
  background-color:rgba(0,0,0,0.5);
  display:inline-block;
  background-position:center;
  transition:1s background-size;
  background-repeat:no-repeat;
}
div.realimage
{
  background-size:cover;
}
div.svg
{
  background-size:contain;
}
div.preview
{
  height:95vh;
  width:95vw;
  position:fixed;
  top:50%;
  left:50%;
  display:none;
  transform:translate(-50%,-50%);
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
  resize:both;
}
div.previewbg
{
  width:100vw;
  height:100vh;
  background-image:url("bg.jpg");
  opacity:0.7;
  position:fixed;
  top:0;
  left:0;
  display:none;
}
div.close
{
  background-image:url("icon_close.svg");
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
  display:none;
  position:fixed;
  top:0;
  right:0;
  height:30px;
  width:30px;
  margin:30px;
}
div.close:hover
{
  filter:brightness(150%);
}
div.left,div.right
{
  width:60px;
  height:50px;
  background-color:rgba(0,0,0,0.5);
  color:#FFFFFF;
  text-align:center;
  font-size:30px;
  font-family:serif;
  letter-spacing:-6px;
  padding-top:20px;
  user-select:none;
  display:none;
}
div.left
{
  position:fixed;
  top:50%;
  left:0;
  transform:translate(0,-50%);
}
div.right
{
  position:fixed;
  top:50%;
  right:0;
  transform:translate(0,-50%);
}
div.left:hover,div.right:hover
{
  color:#00FFFF;
  background-color:rgba(0,0,0,0.7);
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function showImage(url,x)
{
  var a=_("preview"),b=_("previewbg"),c=_("close"),d=_("left"),e=_("right");
  a.style.display="block";
  b.style.display="block";
  c.style.display="block";
  d.style.display="block";
  e.style.display="block";
  a.style.backgroundImage="url(\""+url+"\")";
  a.setAttribute("attr_current",x);
}
function hidePreview()
{
  var a=_("preview"),b=_("previewbg"),c=_("close"),d=_("left"),e=_("right");
  a.style.display="none";
  b.style.display="none";
  c.style.display="none";
  d.style.display="none";
  e.style.display="none";
}
function changeImage(x)
{
  var a=_("preview");
  var b=parseInt(a.getAttribute("attr_current"));
  if(x=="+")
  {
    b++;
  }
  else if(x=="-" && b!=1)
  {
    b--;
  }
  var c=_("image_"+b);
  if(c==null)
  {
    b--;
    c=_("image_"+b);
  }
  a.style.backgroundImage=c.style.backgroundImage;
  a.setAttribute("attr_current",b);
}
</script>
</head>
<body oncontextmenu="return false;">
<div class="previewbg" id="previewbg"></div>
<div class="preview" id="preview"></div>
<div class="left" id="left" onclick="changeImage('-');">&lt;&lt;&lt;</div>
<div class="right" id="right" onclick="changeImage('+');">&gt;&gt;&gt;</div>
<div class="close" id="close" onclick="hidePreview();"></div>
<?php
  getImage("");
?>
</body>
</html>
