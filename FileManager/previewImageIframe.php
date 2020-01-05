<html>
<head>
<style>
*
{
	padding:0;
	margin:0;
}
.xdebug-error,.xewarning
{
	//display:none;
}
body
{
  background-color:rgba(128,128,128,0.1);
}
body::-webkit-scrollbar
{
  display:none;
}
div.row
{
  width:30vw;
	height:30vw;
  border:1px solid #000000;
  margin-top:2.3vw;
  margin-left:2.3vw;
  display:inline-block;
  box-sizing:content-box;
	overflow:hidden;
  background-color:#FFFFFF;
  box-shadow:0px 0px 20px 1px #606060;
  border-radius:10px;
	position:relative;
}
iframe,div.preview
{
  position:absolute;
  left:0;
  top:0;
  width:30vw;
  height:30vw;
  overflow:hidden;
  border:none;
}
div.row:focus
{
	outline:2px dotted #000000;
	outline-offset:1vw;
}
div.row span
{
  padding:5px;
}
span.name
{
  font-weight:900;
  font-family:sans-serif;
  overflow-x:auto;
  white-space:nowrap;
	font-size:16px;
  display:block;
}
span.name::-webkit-scrollbar
{
  height:5px;
  border:1px solid #808080;
  border-radius:20px;
}
span.name::-webkit-scrollbar-thumb
{
  background-color:#404040;
  border-radius:20px;
}
div.details
{
	position:absolute;
	bottom:0;
	left:0;
	width:100%;
	background-color:rgba(255,255,255,0.8);
	transform:translateY(100%);
	transition:0.5s transform;
}
div.row:hover div.details
{
	transform:translateY(0%);
}
div.preview
{
  background-size:cover;
}
span.size
{
	font-size:14px;
	font-family:courier,serif;
	float:left;
}
span.date
{
	float:right;
}
span.size,span.date
{
	display:inline;
}
</style>
</head>
<body>
  <?php
  	$dir=getcwd();
  	$dir.="/Up";
  	if($dh=opendir($dir))
  	{
  		while(($file=readdir($dh))!=false)
  		{
  			if($file=="."||$file=="..")
  			{
  				//Do Nothing
  			}
  			else
  			{
  				$r=strrev($file);
  				$ext=substr($file,strlen($file)-strpos($r,"."));
					$s=filesize("Up/".$file);
					$Size=$s;
					$byte=array(" B"," KB"," MB"," GB"," TB");
					$bi=0;
					while($s>1024)
					{
						$bi++;
						$s/=1024;
						$Size=number_format($s,2);
					}
					if($bi<5)
					{
						$Size.=$byte[$bi];
					}
					else
					{
						$Size="Too Large";
					}
					$d=date("d/m/Y",filemtime("Up/".$file));
  				echo "<div class='row' onclick='clicked(this)' tabindex=0>";
          if($ext=="jpg" || $ext=="png" || $ext=="jpeg" || $ext=="gif" || $ext=="ico")
          {
            echo "<div class='preview' style=\"background-image:url('/".$root."FileManager/Up/".$file."');\"></div>";
          }
          else
          {
            echo "<iframe class='preview' src='/".$root."FileManager/Up/".$file."' sandbox scrolling='no' tabindex=-1></iframe>";
						echo "<div class='preview'></div>";
					}
					echo "<div class='details'>";
  				echo "<span class='name'>$file</span>";
  				echo "<span class='size'>$Size</span>";
  				echo "<span class='date'>$d</span>";
  				echo "</div></div>";
  			}
  		}
  		closedir($dh);
  	}
  ?>
</body>
</html>
