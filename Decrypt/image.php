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
	overflow:hidden;
	background-image:url("bg.jpg");
}
<?php
session_start();
function authorised($path,$log)
{
	if($log=="administrator@gmail.com")
	return true;
	$path=realpath($path);
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
if(isset($_SESSION['Logged']))
{
  if(!empty($_SESSION['Logged']))
  {
    if(isset($_GET['getFile']))
    {
      if(!empty($_GET['getFile']))
      {
    		if(!authorised(urldecode($_GET['getFile']),$_SESSION['Logged']))
    		{
    			die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center\">You are not authorised to open this Folder.</div><div style=\"color:#FF5050;font-size:20px;padding-bottom:100px;text-align:center;\">You are Logged in as ".$_SESSION['Logged']."</div>");
    		}
        else
        {
          $url=urldecode($_GET['getFile']);
          $plaintext=@file_get_contents($url);
					$imageData=base64_encode($plaintext);
					$ext=explode(".",$url);
					$ext=end($ext);
					if($_SESSION['Logged']!="administrator@gmail.com")
					{
						$password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
	          $method='aes-256-cbc';
	          $password=@substr(hash('sha256', $password, true), 0, 32);
	          $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
	          $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
						$imageData=base64_encode($decrypted);
					}
					if(strtolower($ext)=="svg")
					{
						$ext="svg+xml";
					}
				  $img='data:image/'.$ext.';base64,'.$imageData;
					echo "div.image{width:100vw;height:100vh;background-image:url(\"".$img."\");background-size:contain;background-position:center;background-repeat:no-repeat;}";
        }
      }
    }
  }
}
?>
</style>
</head>
<body oncontextmenu="return false;">
	<div class="image">
</body>
</html>
