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
    else
    {
      if(is_writable($url))
      {
        if(isset($_POST['content']))
        {
          $plaintext=htmlspecialchars_decode($_POST["content"]);
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
            $contents['type']="error";
            $contents['message']="Write Error";
          }
          else
          {
            $contents['type']="success";
            $contents['message']="Saved";
          }
        }
        else
        {
          $contents['type']="error";
          $contents['message']="No data found";
        }
      }
      else
      {
        $contents['type']="error";
        $contents['message']="Write access denied";
      }
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
