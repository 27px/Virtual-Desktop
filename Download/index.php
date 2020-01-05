<?php
ob_start();
session_start();
if(!(isset($_SESSION['Logged'])))
{
	die();
}
else if(isset($_GET['download']))
{
	if(!empty(($_GET['download'])))
	{
		if(file_exists(($_GET['download'])))
		{
			$file_url=str_replace("\\","/",$_GET['download']);
			$file_name = end(explode("/",$file_url));
			set_time_limit(0);
			header("Content-disposition: attachment; filename=\"".$file_name."\"");
			$plaintext=@file_get_contents($file_url);
			$decrypted=$plaintext;
			if($_SESSION['Logged']!="administrator@gmail.com")
			{
				$password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
				$method='aes-256-cbc';
				$password=@substr(hash('sha256', $password, true), 0, 32);
				$iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
				$decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
			}
			echo $decrypted;
		}
	}
}
?>
