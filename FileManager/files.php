<?php
if(isset($_GET['url']) && !empty($_GET['url']))
{
	$ser=$_GET['url'];
	$dir=str_replace((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$ser);
}
else
{
	die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Invalid URL</div></div>");
}
function showContents($dir)
{
	$ser=str_replace("\\","/",$dir);
	$ser=str_replace($_SERVER['DOCUMENT_ROOT'],(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'],$ser);
	if($dh=@opendir($dir))
	{
		$i=0;
		while(($file=@readdir($dh))!=false)
		{
			$i++;
			if($file=="."||$file=="..")
			{
				//Do Nothing
			}
			else if(@is_dir($dir."/".$file))
			{
				showContents($dir."/".$file);
			}
			else
			{
				$ext=end(explode(".",$file));
				$s=filesize($dir."/".$file);
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
				$d=date("d/m/Y",@filemtime($dir."/".$file));
        $finfo=@finfo_open(FILEINFO_MIME_TYPE);
        $mimeType=@finfo_file($finfo,$dir."/".$file);
        $mime=explode("/",$mimeType)[0];
        @finfo_close($finfo);
				echo "<tr tabindex=0 class='rows' onclick='showPreview()'>";
				echo "<td class='name' tabindex=-1>$file</td>";
				echo "<td class='other' tabindex=-1>$ext</td>";
				echo "<td class='other' tabindex=-1>$d</td>";
				echo "<td class='other' tabindex=-1>$Size</td>";
				echo "<td class='preview' tabindex=-1>";
				if($mime=="image")
				{
					echo "<div class='imgpre' style=\"background-image:url('".$ser."/".$file."');\"></div>";
				}
				else
				{
					echo "<pre class='code'>";
					echo htmlspecialchars(file_get_contents($dir."/".$file));
					echo "</pre>";
				}
				echo "</td>";
				echo "</tr>";
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
table,tbody,tr
{
	width:100%;
}
table
{
	display:flex;
	flex-direction:column;
}
table,table *
{
	margin:0;
	padding:0;
	border:none;
	outline:none;
}
.preview
{
	padding:20px;
}
td,th
{
	padding:20px;
	font-family:arial black,arial,sans-serif;
	letter-spacing:3px;
	font-size:15px;
	user-select:none;
}
td
{
	background-color:rgba(0,0,0,0.5);
	color:#FFFFFF;
	overflow:hidden;
}
.other
{
	text-align:center;
}
div.imgpre
{
	width:100%;
	height:200px;
	background-size:contain;
	background-position:center;
	background-repeat:no-repeat;
	margin:20px;
}
td:nth-child(1),td:nth-child(2),td:nth-child(3),td:nth-child(4),th:nth-child(1),th:nth-child(2),th:nth-child(3),th:nth-child(4)
{
	width:10%;
}
td:nth-child(5),th:nth-child(5)
{
	width:60%;
	padding:0px;
}
td:nth-child(2)
{
	text-transform:uppercase;
}
body
{
	background-image:url("2.jpg");
	background-size:cover;
	background-position:center;
	background-attachment:fixed;
	overflow-y:auto;
	overflow-x:hidden;
}
pre.code
{
	display:block;
	padding:10px;
	height:500px;
	width:60vw;
	min-height:500px;
	background-color:rgba(0,0,60,0.8);
	font-family:consolas,courier new,courier,monospace;
	letter-spacing:1px;
	user-select:text;
	overflow:auto;
	display:flex;

}
pre.code::-webkit-scrollbar
{
	width:5px;
	height:5px;
	background-color:rgba(255,255,255,0.5);
	border:none;
}
pre.code::-webkit-scrollbar-thumb
{
	background-color:rgba(255,255,255,1);
}
pre.code::-webkit-scrollbar-thumb:hover
{
	background-color:rgba(0,0,60,1);
}
body::-webkit-scrollbar
{
	width:5px;
	height:5px;
	background-color:rgba(255,255,255,0.5);
	border:none;
}
body::-webkit-scrollbar-thumb
{
	background-color:rgba(255,255,255,1);
}
body::-webkit-scrollbar-thumb:hover
{
	background-color:rgba(0,0,60,1);
}
table tr#ttitle th
{
	position:sticky;
	top:0;
	left:0;
	background-color:rgba(0,0,0,0.8);
	color:#FFFFFF;
}
</style>
<script>

</script>
</head>
<body>
<table border=0>
<tr class="title" id="ttitle">
	<th class="name">Name</th>
	<th class="other">Type</th>
	<th class="other">Date</th>
	<th class="other">Size</th>
	<th class="other">Preview</th>
</tr>
<?php
	showContents($dir);
?>
</table>
</body>
</html>
