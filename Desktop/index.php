<?php
  require_once("../Includes/functions.php");
?>
<html>
<head>
<title>Desktop</title>
<script>
//try to bring this to middle of the page
function setMessage(type,message)
{
  var x=document.createElement("P");
  x.setAttribute("class",type);
  x.innerHTML=message;
  var b=document.getElementById("body");
  b.insertBefore(x,b.firstChild);
}
</script>
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
require_once("../config/root.php");
if(!@is_dir($dir))
{
  $dir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
}
if(!@is_dir($dir))
{
  //Desktop Does not Exist , Create one.
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Error : Error opening your Desktop.\");},".($msgcount*1500).");</script>";
  if(@mkdir($dir,0777))
  {
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"warning\",\"Creating new Desktop.\");},".($msgcount*1500).");</script>";
  }
  if(@is_dir($dir))
  {
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"success\",\"Desktop Created.\");},".($msgcount*1500).");</script>";
  }
  else
  {
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"error\",\"Error : Error opening your Desktop, Refresh to retry. If this error keeps occurring again contact our Administrator.\");},".($msgcount*1500).");</script>";
  }
}
$digitalClockTime="Disabled";
$bgColor="#FFFFFF";
$bgImg="bg.jpg";
$bgImgUrl="";
$bgnum=0;
$bgImgExt="jpg";

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
if(!@file_exists($dir."/DesktopSettings.fcz"))
{
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"error\",\"Error : Desktop Personalization Settings File Missing.\");},".($msgcount*1500).");</script>";
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"warning\",\"Creating Desktop Settings File.\");},".($msgcount*1500).");</script>";
  $f=fopen($dir."/DesktopSettings.fcz","w");
  $c="[\"type\"=\"DesktopSettings\"]".PHP_EOL."[\"background-color\"=\"#000000\"]".PHP_EOL."[\"background-image-name\"=\"000Default\"]".PHP_EOL."[\"background-image-number\"=\"0\"]".PHP_EOL."[\"background-image-extension\"=\"jpg\"]".PHP_EOL."[\"icon-size\"=\"M\"]";
  fwrite($f,$c);
  fclose($f);
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"success\",\"Created Settings File Initializing Default Settings.\");},".($msgcount*1500).");</script>";
}
if(@file_exists($dir."/DesktopSettings.fcz"))
{
  $file="DesktopSettings.fcz";
  $verifyType=0;
  $iconSize="";
  $bgImgUrl="";
  $f=@fopen($dir."/".$file,"r");
  while(!@feof($f))
  {
    $fx=@fgets($f);
    $fxa=explode("\"",$fx);
    if(count($fxa)>=3)
    if($fxa[1]=="type" && $fxa[3]=="DesktopSettings")
    {
      $verifyType=1;
    }
    else if($verifyType!=0)
    {
      if($fxa[1]=="background-color")
      {
        if($fxa[3]!="")
        {
          $bgColor=$fxa[3];
        }
      }
      else if($fxa[1]=="background-image-name")
      {
        if($fxa[3]!="")
        {
          $bgImgUrl=$fxa[3];
        }
      }
      else if($fxa[1]=="background-image-number")
      {
        if($fxa[3]!="")
        {
          $bgnum=$fxa[3];
        }
      }
      else if($fxa[1]=="background-image-extension")
      {
        if($fxa[3]!="")
        {
          $bgImgExt=$fxa[3];
        }
      }
      else if($fxa[1]=="icon-size")
      {
        if($fxa[3]!="")
        {
          $iconSize=$fxa[3];
        }
      }
    }
  }
  @fclose($f);
}
$serd=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/".$root."User/Wallpaper/";
$dird=$_SERVER['DOCUMENT_ROOT'].parse_url($serd,PHP_URL_PATH);
if(!@file_exists($dird.$bgImgUrl.".".$bgnum.".".$bgImgExt))
{
  $bgImgUrl=$_SERVER['DOCUMENT_ROOT'].parse_url($serd,PHP_URL_PATH).$bgImgUrl.".".$bgnum.".".$bgImgExt;
  $bgImg=$serd."000Default.0.jpg";
  $msgcount++;
  echo "<script>setTimeout(function(){setMessage(\"warning\",\"Wallpaper specified not found, Using Default Wallpaper . . . \");},".($msgcount*1500).");</script>";
}
else
{
  $bgImg=$serd.$bgImgUrl.".".$bgnum.".".$bgImgExt;
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.min.css">
<style>
body.desktop
{
  background:<?php echo $bgColor; ?> url("<?php echo $bgImg; ?>");
  background-size:cover;
  overflow:hidden;
}
div.desk div.item
{
  <?php
    $sz=getDesktopSettings();
    if(!empty($sz["icon-size"]))
    {
      $s=$sz["icon-size"];
      if($s=="XL")
      {
        echo "width:119px;\n\theight:129px;";
      }
      else if($s=="L")
      {
        echo "width:100px;\n\theight:125px;";
      }
      else if($s=="M")
      {
        echo "width:80px;\n\theight:95px;";
      }
      else if($s=="S")
      {
        echo "width:65px;\n\theight:90px;";
      }
      else
      {
        //Default M
        echo "width:80px;\n\theight:95px;";
      }
    }
  ?>
}
div.digitalClock
{
  <?php
    $dc=getDesktopSettings();
    if(!empty($dc["widget-dClock"]))
    {
      $c=$dc["widget-dClock"];
      if($c!="disabled")
      {
        $digitalClockTime="Enabled";
        echo "text-shadow:1px 1px 2.5px ".$c.",1px -1px 2.5px ".$c.",-1px 1px 2.5px ".$c.",-1px -1px 2.5px ".$c.";";
      }
      else
      {
        echo "display:none !important";
      }
    }
    else
    {
      echo "display:none !important";
    }
  ?>
}
</style>
<script>
  function _(id)
  {
    return document.getElementById(id);
  }
  <?php
    if($digitalClockTime=="Enabled")
    {
  ?>
    var d = new Date(<?php echo time() * 1000 ?>);
    function digitalClock()
    {
      d.setTime(d.getTime() + 1000);
      var hrs = d.getHours();
      var mins = d.getMinutes();
      var secs = d.getSeconds();
      mins = (mins < 10 ? "0" : "") + mins;
      secs = (secs < 10 ? "0" : "") + secs;
      var apm = (hrs < 12) ? "am" : "pm";
      hrs = (hrs > 12) ? hrs - 12 : hrs;
      hrs = (hrs == 0) ? 12 : hrs;
      var ctime = hrs + ":" + mins + ":" + secs + " " + apm;
      _("digitalClock").firstChild.nodeValue = ctime;
    }
    window.onload = function() {
      digitalClock();
      setInterval('digitalClock()', 1000);
    }
<?php
  }
?>
function goToSettings()
{
  window.location="../Settings/index.php";
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
        _("bappContextCut").classList.add("optionDisabled");
        _("bappContextCopy").classList.add("optionDisabled");
        _("bappContextRename").classList.add("optionDisabled");
        _("bappContextDownload").classList.add("optionDisabled");
        _("bappContextDelete").classList.add("optionDisabled");
        _("bappContextProperties").classList.add("optionDisabled");
      }
      else if(all[i].getAttribute("attr_type")=="Folder" || (type=="App" || type=="Folder"))
      {
        _("bappContextDownload").classList.add("optionDisabled");
      }
    }
    if(n>1)
    {
      _("bappContextOpen").classList.add("optionDisabled");
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
    menuInstalledApps();
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
function menuInstalledApps()
{
  var x="";
  var a=document.getElementsByClassName("item");
  var n=a.length;
  for(let i=0;i<n;i++)
  {
    if(a[i].getAttribute("attr_type")=="App")
    {
      var b=a[i].childNodes;
      x+="<div class='appshort' onclick='"+a[i].getAttribute('ondblclick')+"'>";
      x+="<div class='icon' style='background-image:"+b[0].style.backgroundImage+";'></div>";
      x+="<div class='title'>"+b[1].innerHTML+"</div>";
      x+="</div>";
    }
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
  function manageFile(source)
  {
    openAPP("File Viewer","icon/icon_fileviewer.svg",source,600,500,300,200,0,0);
  }
  function imageViewer(source)
  {
    var x="../Decrypt/image.php?getFile="+source;
    openAPP("Image Viewer","icon/icon_image.svg",x,600,500,300,200,0,0);
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
    var virtual=document.getElementById("virtualContainer");
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
  function contextFunctionEditProfile()
  {
    document.getElementById('editprofile').value='true';
    document.getElementById('settings').submit();
  }
  function contextFunctionAnalyse()
  {
    document.getElementById('analyse').value='true';
    document.getElementById('settings').submit();
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
    var content=text+"<?php echo $dir; ?>";
    document.getElementById("appContextPaste").value=content;
    var n=content.split("|");
    if(n.length<3)
    {
      //Invalid Length Error
      return;
    }
    if(n[0]=="Cut")
    {
      if(n[1]=="<?php echo $dir; ?>")
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
      if(n[1]=="<?php echo $dir; ?>")
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
    openAPP("Quick Note","icon/icon_clipboard.svg","data:text/html;charset=utf-8,<html><head><style>*{padding:0;margin:0;}body{background-color:rgba(255,60,60);padding:5px;font-size:30px;}</style></head><body contenteditable='true' autocorrect='off' spellcheck='false'></body></html>",500,250,300,100,0,0);
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
    openAPP("File Manager","../AppStore/icon/icon_File_Manager_10013.png","../FileManager/index.php?url="+path,625,500,500,450);
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
    var xpath=atype+"|<?php echo $dir; ?>|";
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
      openAPP("Properties","icon/icon_properties.png","../Properties/index.php?url="+x[0].getAttribute("attr_prop"),400,400,350,200,450,500);
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
  function setIconSize(x)
  {
    _("iconSize").value=x;
    _("uploader").submit();
  }
  function expandMenu(code,x)
  {
    m=_("expandedMenu");
    m.style.display="block";
    m.style.top=x.offsetTop;
    var z=x.getBoundingClientRect();
    var e=m.getBoundingClientRect();
    var b=_("body").getBoundingClientRect();
    if((z.left+z.width+e.width)>=b.width)
    {
      m.style.left=x.clientWidth-(z.width+e.width+2);
      m.style.boxShadow="-2px 0px 10px 1px rgba(0,0,0,0.7)";
    }
    else
    {
      m.style.left=x.clientWidth;
      m.style.boxShadow="2px 0px 10px 1px rgba(0,0,0,0.7)";
    }
    if(code=="Icon Size")
    {
      m.innerHTML="<div class='rightOptions'><span class='title' onclick='setIconSize(this.innerHTML)'>Small</span></div><div class='rightOptions'><span class='title' onclick='setIconSize(this.innerHTML)'>Medium</span></div><div class='rightOptions'><span class='title' onclick='setIconSize(this.innerHTML)'>Large</span></div><div class='rightOptions'><span class='title' onclick='setIconSize(this.innerHTML)'>Extra Large</span></div>";
    }
    else if(code=="New File")
    {
      m.innerHTML="<div class='rightOptions' onclick=\"contextFunctionNewFile('txt');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=txt&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>Text File</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('html');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=html&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>HTML</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('php');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=php&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>PHP</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('css');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=css&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>CSS</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('js');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=js&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>JS</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('xml');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=xml&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>XML</span></div><div class='rightOptions' onclick=\"contextFunctionNewFile('svg');\"><span class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=svg&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></span><span class='title'>SVG</span></div>";
    }
    else if(code=="Upload")
    {
      m.innerHTML="<div class='rightOptions' onclick='contextFunctionUpload();'><span class='icon'><div class='icon icon_file'></div></span><span class='title'>File</span></div><div class='rightOptions' onclick='contextFunctionFolderUpload();'><span class='icon'><div class='icon icon_folder'></div></span><span class='title'>Folder</span></div>";
    }
    else if(code=="Wallpaper")
    {
      m.innerHTML="<div class='rightOptions' onclick='contextFunctionWallpaperUpload();'><span class='icon'><div class='icon icon_upload'></div></span><span class='title'>Upload</span></div><div class='rightOptions' onclick='wallpaperFromUrl();'><span class='icon'><div class='icon icon_globe'></div></span><span class='title'>Internet URL</span></div>";
    }
  }
  function wallpaperFromUrl()
  {
    var url=prompt("Enter the image URL to set as Wallpaper.");
    _("wallpaperFromURL").value=url;
    _("uploader").submit();
  }
  function wallpaperSelectFromCloud()
  {
    openAPP("File Manager","../AppStore/icon/icon_File_Manager_10013.png","../FileManager/index.php?selection=true",625,500,500,450);
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
  function contextFunctionWallpaperUpload()
  {
    _("wallpaperUpload").click();
  }
  function realUpload()
  {
    _("uploader").submit();
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
    _("wallpaperUpload").value="";
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
    _("popupbottombox").innerHTML="<div class='optionsUpload'><div class='radioOption'><span><input type='radio' name='upradio' form='uploader' value='skip' id='radioSkip' checked='checked'><label class='title rad' for='radioSkip'>Skip file if filename Exists</label></span><span><input type='radio' name='upradio' form='uploader' value='replace' id='radioReplace'><label class='title rad' for='radioReplace'>Replace if filename Exists</label></span></div><center class='cbutton'><button class='redbutton' onclick='closeUploadPopup();'>Cancel Upload</button><button class='greenbutton' onclick='realUpload();'>Upload</button></center></div>";
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
        xs+="<tr><td rowspan=5><div class='iconUploadPreview' id='imageuppreview_"+i+"' "+preview+"></div></td></tr>";
        xs+="<tr><td>File Name </td><td> : </td><td>"+file+"</td></tr>";
        xs+="<tr><td>Size </td><td> : </td><td>"+abSize+"</td></tr>";
        xs+="<tr><td>Size (bytes) </td><td> : </td><td>"+size+" B</td></tr>";
        xs+="<tr><td>Type </td><td> : </td><td>"+type+"</td></tr>";
        xs+="</table>";
        xs+="</fieldset><br>";
        upxsize+=size;
      }
      xs="<div style='padding:10px;font-weight:bold;'>Total "+(i)+" Files Selected</div>."+xs;


    <?php
      if($_SESSION['Logged']!="administrator@gmail.com")
      {
        ?>
        if(upxsize>limit)
        {
          alert("Not Enough Space ! You need "+byte_unit_convert(upxsize-limit)+" more Space");
          _("uploadKey").value="";
          return;
        }
        <?php
      }
    ?>
      openUploadPopup(xs,"upFileUpload");
      for(var i=0;i<n;i++)
      {
        if(list[i].type.split("/")[0]=="image")
        {
          var reader=new FileReader();
          reader.onload=function(){
            //works for single one
            //Pass i into this function
            //_('imageuppreview_i').style.backgroundImage="url("+reader.result+")";
          };
          if(list[i])
          {
            reader.readAsDataURL(list[i]);
          }
        }
      }
    }
  }
  function validateUpWallpaper(x)
  {
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
      }
      xs="<div style='padding:10px;font-weight:bold;'>Wallpaper Image Selected</div>."+xs;
      openUploadPopup(xs,"wallpaperUpload");
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
            _("uploadKey").value="";
            return;
          }
          <?php
        }
      ?>
      xs="<div style='padding:10px;font-weight:bold;'>Files in Selected Folder.</div>."+xs;
      openUploadPopup(xs,"upAllFileFolder");
    }
  }
</script>
</head>
<body oncontextmenu="on(event,'rightMenu','Body',this);return false;" onclick="off('rightMenu');" onmousedown="shiftedDeselect();" id="body" class="desktop" onkeydown="setKey(event);" onkeyup="unsetKey(event);">
  <script>
    <?php
    //Enable This to refresh wallpaper
    //echo "document.getElementById('body').style.backgroundImage=\"url('../User/Wallpaper/".$_SESSION['Logged'].".".$bgImgExt."?dynamic=".time()."')\";\n";
    ?>
    var rectSelect=false;
    var rectX=0;
    var rectY=0;
    var initX=0;
    var initY=0;
  </script>
  <input type="hidden" id="appStackCount" value="0">


  <div class="wContainer" id="wContainer">
    <?php
    $rest=getDesktopSettings();
    foreach($rest as $key => $value)
    {
      if($key=="widget-aClock")
      {
        if($value!="disabled")
        {
          if($value!="")
          {
            $z=explode("#",$value);
            if(!empty($z[1]))
            $a="light=%23".$z[1];
            else
            $a="";
            if(!empty($z[2]))
            $b="&dark=%23".$z[2];
            else
            $b="";
            if(!empty($z[3]))
            $c="&needle=%23".$z[3];
            else
            $c="";
            echo "<iframe class=\"Clock\" src=\"../Desktop/Widgets/Clock/Clock.php?".$a.$b.$c."\"></iframe>";
          }
        }
      }
    }
    ?>
  </div>



  <div class="virtualContainer" id="virtualContainer" onclick="hideVirtualContainer(this);">  </div>



  <div class="deskcontainer">
    <div class="topbar" id="topbar" oncontextmenu="event.stopPropagation();event.preventDefault();return false;">
      <div class="h hl" onclick="window.location='../Home/index.php'">
        <div class="icon icon_home"></div>
        <div class="title">Home</div>
      </div>
      <div class="h hl" onclick="openAPP('File Manager','../AppStore/icon/icon_File_Manager_10013.png','../FileManager/index.php?url='+'<?php echo $dir; ?>',625,500,500,450,0,0);">
        <div class="icon icon_cloud"></div>
        <div class="title">Cloud</div>
      </div>
      <div class="h hl" onclick="hideVirtualContainer(document.getElementById('virtualContainer'));">
        <div class="icon icon_desktop"></div>
        <div class="title">Desktop</div>
      </div>
      <div class="h hl" onclick="window.location='../AppStore/index.php'">
        <div class="icon icon_App_Store"></div>
        <div class="title">App Store</div>
      </div>

      <div class="h hr">
        <div class="icon icon_settings"></div>
        <div class="title" onclick="goToSettings();">Settings</div>
      </div>
      <div class="h hr" onclick="logout();">
        <div class="icon icon_logout"></div>
        <div class="title">Log Out</div>
      </div>

    </div>

    <div class="desk" onmousedown="drawRect(event,document.getElementById('topbar'),document.getElementById('selection'));" onmousemove="growRect(event,document.getElementById('topbar'),document.getElementById('selection'));" onmouseup="endRect(document.getElementById('selection'));" id="Dragcontainer" ondrag="return false;" onscroll="endRect(document.getElementById('selection'));">

<?php
if(isset($_POST['iconSize']))
{
  if(!empty($_POST['iconSize']))
  {
    $s=$_POST['iconSize'];
    if($s=="Small")
    {
      $s="S";
    }
    else if($s=="Medium")
    {
      $s="M";
    }
    else if($s=="Large")
    {
      $s="L";
    }
    else if($s=="Extra Large")
    {
      $s="XL";
    }
    else
    {
      //Default is Medium
      $s="M";
    }
    $rest=getDesktopSettings();
    $c="[\"type\"=\"DesktopSettings\"]";
    foreach($rest as $key => $value)
    {
      if($key=="icon-size")
      {
        $value=$s;
      }
      $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
    }
    $f=@fopen($dir."/DesktopSettings.fcz","w");
    @fwrite($f,$c);
    @fclose($f);
    header("Location:".$_SERVER['PHP_SELF']);
  }
}
if(isset($_POST['wallpaperFromURL']))
{
  if(!empty($_POST['wallpaperFromURL']))
  {
    $url=$_POST['wallpaperFromURL'];
    $ext=explode("?",$url);
    $ext=end(explode(".",$ext[0]));
    $rest=getDesktopSettings();
    $c="[\"type\"=\"DesktopSettings\"]";
    $oldWname=$_SESSION['Logged'];
    $newWname=$_SESSION['Logged'];
    foreach($rest as $key => $value)
    {
      if($key=="background-image-name")
      {
        $value=$_SESSION['Logged'];
      }
      else if($key=="background-image-number")
      {
        $value=(int)$value;
        $oldWname.=".".strval($value);
        $value++;
        $newWname.=".".strval($value);
      }
      else if($key=="background-image-extension")
      {
        $value=$ext;
      }
      $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
    }
    $oldWname.=".".$ext;
    $newWname.=".".$ext;
    if(@copy($url,$_SERVER['DOCUMENT_ROOT']."/".$root."User/Wallpaper/".$newWname))
    {
      if(@file_exists($_SERVER['DOCUMENT_ROOT']."/".$root."User/Wallpaper/".$oldWname))
      {
        if(@unlink($_SERVER['DOCUMENT_ROOT']."/".$root."User/Wallpaper/".$oldWname))
        {
          $f=@fopen($dir."/DesktopSettings.fcz","w");
          @fwrite($f,$c);
          @fclose($f);
        }
      }
      else
      {
        $f=@fopen($dir."/DesktopSettings.fcz","w");
        @fwrite($f,$c);
        @fclose($f);
      }
      header("Location:".$_SERVER['PHP_SELF']);
    }
    else
    {
      $msgcount++;
      echo "<script>setTimeout(function(){setMessage(\"error\",\"Error : Wallpaper not set.\");},".($msgcount*1500).");</script>";
    }
  }
}
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
            if($_POST['upradio']=="skip" && file_exists($dir.$name))
            {
              $msgcount++;
              echo "<script>setTimeout(function(){setMessage(\"warning\",\"File Exists : ".$name."\");},".($msgcount*1500).");</script>";
            }
            else
            {
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
    else if($_POST['uploadKey']=="wallpaperUpload")
    {
      $uperror=" :: ";
      $name=$_FILES["wallpaperUpload"]["name"];
      $name=basename($name);
      $tmp=$_FILES["wallpaperUpload"]["tmp_name"];
      $size=$_FILES["wallpaperUpload"]["size"];
      $error=$_FILES["wallpaperUpload"]["error"];
      if($error!=0)
      {
        //Error
        $uperror.=$name." : ";
      }
      else
      {
        $ext=end(explode(".",$name));
        $rest=getDesktopSettings();
        $c="[\"type\"=\"DesktopSettings\"]";
        $oldWname=$_SESSION['Logged'];
        $newWname=$_SESSION['Logged'];
        foreach($rest as $key => $value)
        {
          if($key=="background-image-name")
          {
            $value=$_SESSION['Logged'];
          }
          else if($key=="background-image-number")
          {
            $value=(int)$value;
            $oldWname.=".".strval($value);
            $value++;
            $newWname.=".".strval($value);
          }
          else if($key=="background-image-extension")
          {
            $value=$ext;
          }
          $c.=PHP_EOL."[\"".$key."\"=\"".$value."\"]";
        }
        $oldWname.=".".$ext;
        $newWname.=".".$ext;
        if(@move_uploaded_file($tmp,$_SERVER['DOCUMENT_ROOT']."/".$root."User/Wallpaper/".$newWname)==true)
        {
          //Success
          if(@file_exists($_SERVER['DOCUMENT_ROOT']."/".$root."User/Wallpaper/".$oldWname))
          {
            if(@unlink($_SERVER['DOCUMENT_ROOT']."/".$root."User/Wallpaper/".$oldWname))
            {
              $f=@fopen($dir."/DesktopSettings.fcz","w");
              @fwrite($f,$c);
              @fclose($f);
            }
          }
          else
          {
            $f=@fopen($dir."/DesktopSettings.fcz","w");
            @fwrite($f,$c);
            @fclose($f);
          }
          header("Location:".$_SERVER['PHP_SELF']);
        }
        else
        {
          //move Error
          $msgcount++;
          echo "<script>setTimeout(function(){setMessage(\"error\",\"Wallpaper could not set.\");},".($msgcount*1500).");</script>";
        }
      }
      if($uperror!=" :: ")
      {
        $msgcount++;
        echo "<script>setTimeout(function(){setMessage(\"error\",\"Wallpaper Image Upload Error\");},".($msgcount*1500).");</script>";
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
    paste($sourcedirectory,$destinationdirectory,$items,$operation);
  }
}
if(isset($_POST['appContextRename']) && !empty($_POST['appContextRename']))
{
  $v=explode("/",$_POST['appContextRename']);
  if(count($v)>1)
  {
    $old=$v[0];
    $new=$v[1];
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
  //echo $_POST['appContextDelete'];
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
if(isset($_SESSION['User']))
if($_SESSION['User']=="Admin")
{
  $file="My PC";
  $icon="../AppStore/icon/icon_myPC_10018.png";
  $src="../FileManager/index.php?type=myPC";
  $width="820";
  $height="520";
  echo "<div class='item' ondblclick='window.location=\"".$src."\"' id='item_0' xid='0' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','App',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval='openAPP(\"".$file."\",\"".$icon."\",\"".$src."\",\"".$width."\",\"".$height."\",500,300);' attr_type=\"App\">";
  echo "<div class='icon' style='background-image:url(".$icon.");background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
  echo "<div class='title renamableTitle' id='title_0' def='".$file."' >".$file."</div></div>";
}
if($_SESSION['Logged']!="")
{
  $file="File Manager";
  $icon="../AppStore/icon/icon_File_Manager_10013.png";
  $src="../FileManager/index.php?url=".$dir;
  $width="625";
  $height="500";
  echo "<div class='item' ondblclick=\"window.location='$src'\" id='item_0' xid='0' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','App',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval='openAPP(\"".$file."\",\"".$icon."\",\"".$src."\",\"".$width."\",\"".$height."\",500,450,0,0);' attr_type=\"App\">";
  echo "<div class='icon' style='background-image:url(".$icon.");background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
  echo "<div class='title renamableTitle' id='title_0' def='".$file."' >".$file."</div></div>";

  $file="App Store";
  $icon="icon/icon_App_Store.svg";
  $src="../AppStore/index.php";
  $width="940";
  $height="300";
  echo "<div class='item' ondblclick='window.location=\"".$src."\";' id='item_0' xid='0' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','App',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval='openAPP(\"".$file."\",\"".$icon."\",\"".$src."\",\"".$width."\",\"".$height."\",750,400,0,0);' attr_type=\"App\">";
  echo "<div class='icon' style='background-image:url(".$icon.");background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
  echo "<div class='title renamableTitle' id='title_0' def='".$file."' >".$file."</div></div>";
}
if($_SESSION['User']=="Manager")
{
  $file="User Manager";
  $icon="icon/icon_User_Manager.png";
  $src="../UserManager/index.php";
  $width="940";
  $height="300";
  echo "<div class='item' ondblclick='window.location=\"".$src."\";' id='item_0' xid='0' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','App',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval='openAPP(\"".$file."\",\"".$icon."\",\"".$src."\",\"".$width."\",\"".$height."\",750,400,0,0);' attr_type=\"App\">";
  echo "<div class='icon' style='background-image:url(".$icon.");background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
  echo "<div class='title renamableTitle' id='title_0' def='".$file."' >".$file."</div></div>";
}
if($dh=@opendir($dir))
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
      $tmpx=explode(".",$file);
      $ext=end($tmpx);
      $skipthisfile=0;
      if(@is_dir($dir."/".$file))
      {
        echo "<div class='item' id='item_".$i."' xid='".$i."' ondblclick=\"openFolder('".$dir.$file."/');\" tabindex=0 oncontextmenu=\"on(event,'appRightMenu','Folder',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval=\"openFolder('".$dir.$file."/');\" attr_type=\"Folder\" attr_prop=\"".urlencode($dir."/".$file)."\">";
        echo "<div class='icon' style='background-image:url(icon/icon_folder.png);background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
      }
      else if($ext=="fcz")
      {
        //Read & Fetch Contents from the file to display System Default Apps.
        $pathx=$dir."/".$file;
        $ptype="r";
        $f=@fopen($pathx,$ptype);
        $verifyType=0;
        $skipthisfile=0;
        $minwidth=0;
        $minheight=0;
        $maxwidth=0;
        $maxheight=0;
        $icon="";
        $src="";
        while(!@feof($f))
        {
          $fx=@fgets($f);
          $fxa=explode("\"",$fx);
          if($fxa[1]=="type" && $fxa[3]=="Application")
          {
            $verifyType=1;
          }
          if($fxa[1]=="type" && $fxa[3]=="DesktopSettings")
          {
            $skipthisfile=1;
          }
          else if($verifyType!=0)
          {
            if($fxa[1]=="icon")
            {
              if($fxa[3]!="")
              {
                $icon=$fxa[3];
              }
            }
            else if($fxa[1]=="title")
            {
              if($fxa[3]!="")
              {
                $file=$fxa[3];
              }
            }
            else if($fxa[1]=="width")
            {
              if($fxa[3]!="")
              {
                $width=$fxa[3];
                $width+=10;
              }
            }
            else if($fxa[1]=="height")
            {
              if($fxa[3]!="")
              {
                $height=$fxa[3];
                $height+=45;
              }
            }
            else if($fxa[1]=="min-width")
            {
              if($fxa[3]!="")
              {
                $minwidth=$fxa[3];
                $minwidth+=10;
              }
            }
            else if($fxa[1]=="min-height")
            {
              if($fxa[3]!="")
              {
                $minheight=$fxa[3];
                $minheight+=45;
              }
            }
            else if($fxa[1]=="max-width")
            {
              if($fxa[3]!="")
              {
                $maxwidth=$fxa[3];
              }
            }
            else if($fxa[1]=="max-height")
            {
              if($fxa[3]!="")
              {
                $maxheight=$fxa[3];
              }
            }
          }
        }
        $src="../AppStore/Apps/".rawurlencode($file);
        $icon="../AppStore/icon/".rawurlencode($icon);
        @fclose($f);
        if($skipthisfile!=1)
        {
          echo "<div class='item' ondblclick='openAPP(\"".$file."\",\"".$icon."\",\"".$src."\",\"".$width."\",\"".$height."\",\"".$minwidth."\",\"".$minheight."\",\"".$maxwidth."\",\"".$maxheight."\");' id='item_".$i."' xid='".$i."' tabindex=0 oncontextmenu=\"on(event,'appRightMenu','App',this);return false;\" onclick=\"off();event.stopPropagation();\" onfocus=\"selectItem(this,event);\" attr_eval='openAPP(\"".$file."\",\"".$icon."\",\"".$src."\",\"".$width."\",\"".$height."\",\"".$minwidth."\",\"".$minheight."\",\"".$maxwidth."\",\"".$maxheight."\");' attr_type=\"App\" attr_prop=\"".urlencode($dir.$file)."\">";
          echo "<div class='icon' style='background-image:url(".$icon.");background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
        }
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
          echo "<div class='icon' style='background-image:url(../FileIcon/icongenerator.php?ext=".$ext."&case=upper&predefined=alpha);background-size:contain;background-position:center;background-repeat:no-repeat;'></div>";
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


    <form id="settings" class="hiddenForm" method="POST" action="../Settings/index.php">
      <input type="hidden" name="profile" id="editprofile" value="" />
      <input type="hidden" name="analyse" id="analyse" value="" />
    </form>


    <div class="selection" id="selection"></div>

    <!--App Context-->
    <div class="rightMenu" id="appRightMenu" onmousedown="event.stopPropagation();">
      <div class="rightOptions" id="bappContextOpen" onclick="appFunctionOpen(this);"><span class="icon"><div class="icon icon_open"></div></span><span class="title">Open</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextCut" onclick="appFunctionCut(this);"><span class="icon"><div class="icon icon_cut"></div></span><span class="title">Cut</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextCopy" onclick="appFunctionCopy(this);"><span class="icon"><div class="icon icon_copy"></div></span><span class="title">Copy</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextDelete" onclick="appFunctionDelete(this);"><span class="icon"><div class="icon icon_delete"></div></span><span class="title">Delete</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextRename" onclick="appFunctionRename(this);"><span class="icon"><div class="icon icon_rename"></div></span><span class="title">Rename</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextDownload" onclick="appFunctionDownload(this);"><span class="icon"><div class="icon icon_download"></div></span><span class="title">Download</span><span class="shortcut"></span></div>
      <div class="rightOptions" id="bappContextProperties" onclick="appFunctionProperties(this);"><span class="icon"><div class="icon icon_properties"></div></span><span class="title">Properties</span><span class="shortcut"></span></div>
    </div>
    <!--App Context-->

    <!--Menu Option ! Always Bottom-->
    <form id="contextForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

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
        <div class="rightOptions" onclick="window.location='<?php echo $_SERVER['PHP_SELF']; ?>'"><span class="icon"><div class="icon icon_refresh"></div></span><span class="title">Refresh</span><span class="shortcut">F5</span></div>
        <div class="rightOptions" onclick="contextFunctionUpload();" onmouseover="expandMenu('Upload',this);"><span class="icon"><div class="icon icon_upload"></div></span><span class="title">Upload</span><span class="shortcut arrow"></span></div>
        <div class="rightOptions" onclick="contextFunctionNewFolder();"><span class="icon"><div class="icon icon_folder"></div></span><span class="title">New Folder</span><span class="shortcut"></span></div>
        <div class="rightOptions" onclick="contextFunctionNewFile('txt');" onmouseover="expandMenu('New File',this);"><span class="icon"><div class="icon icon_file"></div></span><span class="title">New File</span><span class="shortcut arrow"></span></div>
        <div class="rightOptions" onclick="contextFunctionPaste(this);" id="bodyContextPaste"><span class="icon"><div class="icon icon_paste"></div></span><span class="title">Paste</span><span class="shortcut">Ctrl + V</span></div>
        <div class="rightOptions" onclick="contextFunctionQuickNote();"><span class="icon"><div class="icon icon_clipboard"></div></span><span class="title">Quick Note</span><span class="shortcut"></span></div>
        <div class="rightOptions" onclick="contextFunctionEditProfile();"><span class="icon"><div class="icon icon_profile"></div></span><span class="title">Profile</span><span class="shortcut"></span></div>
        <div class="rightOptions" onmouseover="expandMenu('Icon Size',this);"><span class="icon"><div class="icon icon_size"></div></span><span class="title">Icon Size</span><span class="shortcut arrow"></span></div>
        <div class="rightOptions" onclick="contextFunctionWallpaperUpload();" onmouseover="expandMenu('Wallpaper',this);"><span class="icon"><div class="icon icon_wallpaper"></div></span><span class="title">Wallpaper</span><span class="shortcut arrow"></span></div>
        <div class="rightOptions" onclick="contextFunctionAnalyse();"><span class="icon"><div class="icon icon_widgets"></div></span><span class="title">Analyse</span><span class="shortcut"></span></div>
        <div class="rightOptions" onclick="goToSettings();"><span class="icon"><div class="icon icon_settings"></div></span><span class="title">Settings</span><span class="shortcut"></span></div>
        <div class="expandedMenu" id="expandedMenu"></div>
      </div>

    </form>
    <!--Menu Option ! Always Bottom-->
  </div>

  <div class="appmenubg" id="appmenubg" oncontextmenu="event.stopPropagation();off();return false;"></div>
  <div class="appmenu" id="appmenu" onclick="event.stopPropagation();" oncontextmenu="event.stopPropagation();return false;">
    <div class="appmenucontent" id="appmenucontent">Nothing Found !</div>
    <input class="menusearch" id="menusearch" type="text" value="" autocomplete="off" placeholder="Search in Desktop" spellcheck="false"  onkeydown='event.stopPropagation();' onkeyup='event.stopPropagation();' oninput="searchmenuon(this.value);"/>
  </div>
  <div class="taskbar" id="taskbar" oncontextmenu="event.stopPropagation();event.preventDefault();return false;">
    <div class="menuContainer" onclick="event.stopPropagation();menuShowApps();"><div class="menuIcon"></div></div>
    <!-- Taskbar Items -->
    <div class="digitalClock" id="digitalClock">00:00:00 --</div>
  </div>
</div>

<input type="hidden" id="Ctrl" value=0 />
<input type="hidden" id="Shift" value=0 />
<form class="hiddenForm" id="uploader" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
  <input type="file" name="upAllFileFolder[]" id="upAllFileFolder" multiple directory webkitdirectory onchange="validateUpFolder(this);">
  <input type="file" name="wallpaperUpload" id="wallpaperUpload" accept="image/*" onchange="validateUpWallpaper(this);">
  <input type="file" name="upFileUpload[]" id="upFileUpload" accept="*/*" onchange="validateUpFile(this);" multiple>
  <input type="hidden" name="uploadKey" id="uploadKey" value="">
  <input type="hidden" name="wallpaperFromURL" id="wallpaperFromURL" value="">
  <input type="hidden" name="iconSize" id="iconSize" value="">
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
  menuInstalledApps();
</script>
</body>
</html>
