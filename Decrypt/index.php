<html>
<head>
<style>
div.errhead
{
	color:#FF5050;
	font-size:40px;
	border-bottom:1px solid #FF0000;
	margin:40px;
	text-align:center;
	padding-bottom:20px;
	width:calc(100% - 100px);
	white-space:initial;
}
div.errsubhead
{
	color:#FF5050;
	font-size:20px;
	padding-bottom:100px;
	text-align:center;
}
</style>
</head>
<body oncontextmenu="return true;">
<pre>
<?php
session_start();
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
    			die("<div class=\"errhead\">You are not authorised to open this Folder.</div><div class=\"errsubhead\">You are Logged in as ".$_SESSION['Logged']."</div>");
    		}
        else
        {
          $url=urldecode($_GET['getFile']);
					$ext=strtolower(end(explode(".",$url)));
					if(($ext=="zip" || $ext=="rar") || ($ext=="mp3" || $ext=="mp4"))
					{
						die("<div class=\"errhead\">Sorry, this File type is currently unsupported.</div><div class=\"errsubhead\">We are working on it !</div>");
					}
          $plaintext=file_get_contents($url);
					$decrypted=$plaintext;
					if($_SESSION['Logged']!="administrator@gmail.com")
					{
	          $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
	          $method='aes-256-cbc';
	          $password=substr(hash('sha256', $password, true), 0, 32);
	          $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
	          $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
					}
					if($ext=="pdf")
					{
						header("Content-Type:application/pdf");
						echo $decrypted;
					}
					else
          {
						echo htmlspecialchars($decrypted);
					}
        }
      }
    }
  }
}
?>
</pre>
</body>
</html>
