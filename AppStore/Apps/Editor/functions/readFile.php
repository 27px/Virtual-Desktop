<?php
  ob_start();
  session_start();
  $contents=array();
  if(isset($_GET['url']) && !empty($_GET['url']))
  {
    require_once("authorized.php");
    $url=$_GET['url'];
    if(!isset($_SESSION['Logged']) && !empty($_SESSION['Logged']))
    {
      $contents['type']="warning";
      $contents['message']="Not Logged in";
    }
    else if(!authorised(urldecode($url),$_SESSION['Logged']))
    {
      $contents['type']="warning";
      $contents['message']="Authorization Error";
    }
    else if(is_dir($url))
    {
      $contents['type']="error";
      $contents['message']="Not a file. It is directory";
    }
    else if(!file_exists($url))
    {
      $contents['type']="error";
      $contents['message']="File Not Found";
    }
    else if(!is_readable($url))
    {
      $contents['type']="error";
      $contents['message']="Read Access Denied";
    }
    else
    {
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
      $FileName=explode("/",$url);
      $FileName=end($FileName);
      $FileExt=explode(".",$FileName);
      $FileExt=end($FileExt);
      $FileExt=strtoupper($FileExt);
      $FileType=mime_content_type($url);
      $fileModifiedDate=date("d/F/Y H:i:s",filemtime($url));
      $FilePropertyRead="-";
      $FilePropertyWrite="-";
      $FilePropertyExecute="-";
      if(is_readable($url))
      {
        $FilePropertyRead="r";
      }
      if(is_writable($url))
      {
        $FilePropertyWrite="w";
      }
      if(is_executable($url))
      {
        $FilePropertyExecute="x";
      }
      $u=realpath($url);
      if($u==false)
      {
        $u=$url;
      }
      $u=explode($_SESSION['Logged'],$u);
      $FilePath="Root".end($u);
      $contents['type']="success";
      $contents['message']=$decrypted;
      $contents['filename']=$FileName;
      $contents['filepath']=$FilePath;
      $contents['fileext']=$FileExt;
      $contents['filetype']=$FileType;
      $contents['filemodifieddate']=$fileModifiedDate;
      $contents['filepropertyread']=$FilePropertyRead;
      $contents['filepropertywrite']=$FilePropertyWrite;
      $contents['filepropertyexecute']=$FilePropertyExecute;
    }
  }
  else
  {
    $contents['type']="error";
    $contents['message']="No URL found";
  }
  $result=json_encode($contents);
  echo $result;
?>
