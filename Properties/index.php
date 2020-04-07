<?php
  ob_start();
  session_start();
  if(!(isset($_SESSION['Logged'])))
  {
    //die("<div style='color:#FF6060;font-size:30px;border-bottom:3px solid #FF6060;padding:10px;text-align:center;margin-left:25px;margin-right:25px;'>You are not Logged In.</div>");
  }
  /*

    //$attr=exec('attrib +R '.$file);
    //fnmatch($key,$filename);

    echo "User Computer Details : <br>";
    echo php_uname("s")."<br>";
    echo php_uname("n")."<br>";
    echo php_uname("r")."<br>";
    echo php_uname("v")."<br>";
    echo php_uname("m")."<br>";
    echo "<hr>";

  */
  if(!isset($_GET['url']) || empty($_GET['url']))
  {
    die("<div style='color:#FF6060;font-size:30px;border-bottom:3px solid #FF6060;padding:10px;text-align:center;margin-left:25px;margin-right:25px;'>No File / Directory Selected.</div>");
  }
  else
  {
    $file=$_GET['url'];
    $file=str_replace(":\\",":\\\\",$file);
    $xr=explode("/",$file);
    $filename=$xr[count($xr)-1];
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
  function property($title,$expr)
  {
    echo "<tr class='td2'><td>".$title."</td><td>";
    if($expr==true)
    {
      echo "Yes";
    }
    else
    {
      echo "No";
    }
    echo "</td></tr>";
  }
  function puttablecell($title,$data,$class)
  {
    echo "<tr class=".$class."><td>".$title."</td><td>".$data."</td></tr>";
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
  function process_cmd($cmd)
  {
    if(function_exists('exec'))
    {
      return exec($cmd);
    }
    else if(function_exists('shell_exec'))
    {
      return shell_exec($cmd);
    }
    else if(function_exists('system'))
    {
      return system($cmd);
    }
    else if(function_exists('passthru'))
    {
      return passthru($cmd);
    }
    else
    {
      return "";
    }
  }
  function splitter($x)
  {
    $r="";
    if($x>=4)
    {
      $r.="R";
      $x-=4;
    }
    if($x>=2)
    {
      $r.="W";
      $x-=2;
    }
    if($x>=1)
    {
      $r.="X";
      $x-=1;
    }
    return $r;
  }
  function ch($u,$k,$i,$c,$no)
  {
    if($k=="A")
    {
      $onclick="\"toggleAllCheck(this,'".$i."');\"";
      $checked="&#10004;";
      $unChecked="&#10006;";
    }
    else
    {
      $onclick="\"if(this.getAttribute('checked')=='yes')toggleCheck(this,'uncheck');else toggleCheck(this,'check');\"";
      $checked="&#10003;";
      $unChecked="&#10005;";
    }
    $i=$k.$i;
    $u=str_split($u);
    $j=0;
    $ch=0;
    while($j<(count($u)))
    {
      if($u[$j]==$k)
      {
        $ch++;
      }
      $j++;
    }
    if($ch>0)
    {
      echo "<div class=\"tickbox\" checked=\"yes\" style=\"color:#00FF00;\" name=\"".$i."\" id=\"".$i."\" onclick=".$onclick.">".$checked."</div>";
    }
    else
    {
      echo "<div class=\"tickbox\" checked=\"no\" style=\"color:#FF0000;\" name=\"".$i."\" id=\"".$i."\" onclick=".$onclick.">".$unChecked."</div>";
    }
  }
  if(is_dir($file))
  {
    $type="Directory";
  }
  else if(file_exists($file))
  {
    $type="File";
  }
  else
  {
    die("<div style=\"color:#FF5050;font-size:30px;border-bottom:1px solid #FF0000;padding:20px;margin:25px;text-align:center;\">Invalid File Path</div>");
  }
  if(!authorised($file,$_SESSION['Logged']))
  {
    die("<div style=\"color:#FF5050;font-size:30px;border-bottom:1px solid #FF0000;padding:20px;margin:25px;text-align:center;\">You are not authorised to View Properties of this ".$type.".</div><div style=\"color:#FF5050;font-size:20px;padding-bottom:50px;text-align:center;\">You are Logged in as ".$_SESSION['Logged']."</div>");
  }
  if($_SESSION['Logged']!="administrator@gmail.com")
  {
    $root=explode("/",$file);
    $root=$root[count($root)-2]."/";
    if($root==$_SESSION['Logged'])
    {
      $root="Root Folder/";
    }
    $loc=explode($_SESSION['Logged'],$file);
    $loc="Root Folder".$loc[1];
  }
  else
  {
    $root=dirname($file);
    $loc=$file;
  }
?>

<html>
<head>
<style>
*
{
  padding:0;
  margin:0;
  user-select:none !important;
}
body
{
  overflow:auto;
}
body::-webkit-scrollbar
{
  width:8px;
  height:8px;
  background-color:#9c27b0;
  border-left:1px solid #000080;
}
body::-webkit-scrollbar-thumb
{
  background-color:#52155d;
  border-top:1px solid #000080;
  border-bottom:1px solid #000080;
}
body::-webkit-scrollbar-thumb:hover
{
  background-color:#ca61dc;
}
table
{
  width:100%;
  border-collapse:collapse;
}
table tr td:first-child,table tr th:first-child
{
  padding-left:25px;
}
table tr th:nth-child(1)
{
  text-align:left;
}
table tr td:nth-child(2)
{
  text-align:center;
}
div.header
{
  width:100%;
  padding:20px 0px;
  text-align:center;
  background-color:#000000;
  color:#FFFFFF;
  font-size:35px;
  font-family:arial black,arial,sans-serif,serif;
  background-image:url("bg1.jpg");
  background-size:cover;
  background-attachment:fixed;
  background-position:center;
  height:200px;
  -webkit-text-stroke:1px #000000;
}
table tr th
{
  position:sticky;
  top:0;
  left:0;
  padding:10px;
  color:#000000;
}
table tr td
{
  min-width:20px;
  padding:20px 10px;
}
table tr.th1 th
{
  background-color:#4caf50;
}
table tr.td1 td
{
  background-color:#91cf94;
  color:#005000;
}
table tr.th2 th
{
  background-color:#03a9f4;
}
table tr.td2 td
{
  background-color:#3ec1fd;
  color:#000080;
}
table tr.th3 th
{
  background-color:#f44336;
}
table tr.td3 td
{
  background-color:#f99d97;
  color:#800000;
}
table tr.th4 th
{
  background-color:#9c27b0;
}
table tr.td4 td
{
  background-color:#ca61dc;
  color:#600060;
}
form.edit
{
  width:100%;
  text-align:center;
  padding:20px;
  box-sizing:border-box;
  background-image:url("bg2.jpg");
  background-size:cover;
  background-attachment:fixed;
  background-position:bottom;
  min-height:300px;
}
button.edit,button.cancel
{
  padding:5px 10px;
  min-width:100px;
  font-family:serif;
  font-size:20px;
  margin:5px;
}
button.edit
{
  background:linear-gradient(135deg,#00FF00,#00B000);
  border:3px solid #008000;
}
button.edit:hover
{
  background:linear-gradient(135deg,#00B000,#00FF00);
}
button.edit:focus
{
  outline:none;
}
button.cancel
{
  background:linear-gradient(135deg,#FF0000,#B00000);
  border:3px solid #800000;
  color:#FFFFFF;
}
button.cancel:hover
{
  background:linear-gradient(135deg,#B00000,#FF0000);
}
button.cancel:focus
{
  outline:none;
}
tr
{
  border-bottom:1px solid rgba(0,0,0,0.5);
}
table tr:first-child
{
  border-top:1px solid rgba(0,0,0,0.5);
}
table.eqw tr td,table.eqw tr th
{
  width:calc(100% / 4);
  text-align:center;
}
div.tickbox
{
  background-color:rgba(0,0,0,0.5);
  display:inline-block;
  font-size:30px;
  width:40px;
  height:40px;
  outline:1px solid #000000;
  color:#00FF00;
}
div.tickbox:hover
{
  background-color:rgba(0,0,0,0.6);
}
div.inverse
{
  color:#FF0000;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function toggleCheck(x,n)
{
  if(n=="check")
  {
    x.innerHTML="&#10003;";
    x.style.color="#00FF00";
    x.setAttribute("checked","yes");
  }
  else
  {
    x.innerHTML="&#10005;";
    x.style.color="#FF0000";
    x.setAttribute("checked","no");
  }
}
function toggleAllCheck(x,a)
{
  if(x.getAttribute("checked")=="yes")
  {
    x.innerHTML="&#10006;";
    x.style.color="#FF0000";
    toggleCheck(_("R"+a),"uncheck");
    toggleCheck(_("W"+a),"uncheck");
    toggleCheck(_("X"+a),"uncheck");
    x.setAttribute("checked","no");
  }
  else
  {
    x.innerHTML="&#10004;";
    x.style.color="#00FF00";
    toggleCheck(_("R"+a),"check");
    toggleCheck(_("W"+a),"check");
    toggleCheck(_("X"+a),"check");
    x.setAttribute("checked","yes");
  }
}
function isChecked(id)
{
  if(_(id).getAttribute("checked")=="yes")
  {
    return true;
  }
  else
  {
    return false;
  }
}
function modCheck(x)
{
  var y=0;
  if(isChecked("R"+x))
  {
    y+=4;
  }
  if(isChecked("W"+x))
  {
    y+=2;
  }
  if(isChecked("X"+x))
  {
    y+=1;
  }
  return y;
}
function applyChanges()
{
  alert("Sorry, This Feature is currently Unavailable.");
  console.warn("Permission changes using chmod() is not available on Windows Server.");
  return;//chmod() will only work on Linux Server, So disabled this Feature.
  var mod="0";
  var owner=modCheck(1);
  var ownergroup=modCheck(2);
  var others=modCheck(3);
  mod+=owner;
  mod+=ownergroup;
  mod+=others;
  _("apply").value=mod;
  _("formapply").submit();
}
</script>
</head>
<body id="body" oncontextmenu="return false;">
<?php
  if(isset($_POST['apply']) && !empty($_POST['apply']))
  {
    chmod($file,$_POST['apply']);
  }
  if(isset($_POST['edit']))
  {
      $fp=substr(sprintf('%o', fileperms($file)), -4);
    ?>
      <div class="header">Edit Properties</div>
    <?php
      $owner=splitter($fp[1]);
      $ownergroup=splitter($fp[2]);
      $others=splitter($fp[3]);
    ?>
      <table class="eqw">
        <tr class="th2">
          <th>Property</th>
          <th>Owner</th>
          <th>Owner's Group</th>
          <th>Others</th>
        </tr>
        <tr class="td2">
          <td>All</td>
          <td><?php ch($owner,"A","1","&#10004;","&#10006;"); ?></td>
          <td><?php ch($ownergroup,"A","2","&#10004;","&#10006;"); ?></td>
          <td><?php ch($others,"A","3","&#10004;","&#10006;"); ?></td>
        </tr>
        <tr class="td2">
          <td>Read</td>
          <td><?php ch($owner,"R","1","&#10003;","&#10005;"); ?></td>
          <td><?php ch($ownergroup,"R","2","&#10003;","&#10005;"); ?></td>
          <td><?php ch($others,"R","3","&#10003;","&#10005;"); ?></td>
        </tr>
        <tr class="td2">
          <td>Write</td>
          <td><?php ch($owner,"W","1","&#10003;","&#10005;"); ?></td>
          <td><?php ch($ownergroup,"W","2","&#10003;","&#10005;"); ?></td>
          <td><?php ch($others,"W","3","&#10003;","&#10005;"); ?></td>
        </tr>
        <tr class="td2">
          <td>Execute</td>
          <td><?php ch($owner,"X","1","&#10003;","&#10005;"); ?></td>
          <td><?php ch($ownergroup,"X","2","&#10003;","&#10005;"); ?></td>
          <td><?php ch($others,"X","3","&#10003;","&#10005;"); ?></td>
        </tr>
      </table>
      <form class="edit" id="formapply" method="POST" action="<?php echo $_SERVER['PHP_SELF']."?url=".$_GET['url']; ?>">
        <button class="cancel" type="button" onclick="window.location=window.location;">Cancel</button>
        <input type="hidden" value="0000" name="apply" id="apply" />
        <button class="edit" type="button" onclick="applyChanges();">Apply Changes</button>
      </form>
    <?php
  }
  else if(true)
  {
?>
<div class="header"><?php echo $type; ?> Properties</div>
<table border=0 class="m2">
  <tr class="th1">
    <th>Details</th>
    <th>Value</th>
  </tr>
  <tr class="td1">
    <td>Name</td>
    <td><?php echo $filename; ?></td>
  </tr>
  <tr class="td1">
    <td>Type</td>
    <td><?php echo $type; ?></td>
  </tr>
  <tr class="td1">
    <td>In</td>
    <td><?php echo $root; ?></td>
  </tr>
  <tr class="td1">
    <td>Location</td>
    <td><?php echo $loc; ?></td>
  </tr>
  <tr class="th2">
    <th>Property</th>
    <th>Value</th>
  </tr>
<?php
  property("Execute",is_executable($file));
  property("Write Access",is_writable($file));
  property("Read Access",is_readable($file));
  $attr=process_cmd('attrib '.$file);
  if($attr!="")
  {
    $attr=str_replace("\\","/",$attr);
    $attr=str_replace($file,"",$attr);
    $attr=str_replace(" ","",$attr);
    property("Read Only",strpos($attr,"R")!="");
    property("Archivable",strpos($attr,"A")!="");
    property("System File",strpos($attr,"S")!="");
    property("Hidden",strpos($attr,"H")!="");
    property("Content Indexed",strpos($attr,"I")!="");
    property("Scrub File",strpos($attr,"X")!="");
    property("Integrity",strpos($attr,"V")!="");
  }
  ?>
    <tr class="th3">
      <th>Date</th>
      <th>Value</th>
    </tr>
  <?php
  date_default_timezone_set("Asia/Kolkata");
  puttablecell("Last Accessed",date("h:i:s l d/m/Y F", fileatime($file)),"td3");
  puttablecell("Last Modified",date("h:i:s l d/m/Y F", filemtime($file)),"td3");
  puttablecell("Last Changed",date("h:i:s l d/m/Y F", filectime($file)),"td3");
  ?>
    <tr class="th4">
      <th>Size</th>
      <th>Value</th>
    </tr>
  <?php
  $s=filesize($file);
  puttablecell("Size ( Bytes )",$s." B","td4");
  puttablecell("Size",byte_unit_convert($s),"td4");
?>
</table>
<form class="edit" method="POST" action="<?php echo $_SERVER['PHP_SELF']."?url=".$_GET['url']; ?>">
  <button class="edit" type="submit" name="edit">Edit</button>
</form>
<?php
  }
?>
</body>
</html>
