<?php
  if(!isset($dir) || $dir=="")
  {
    if((session_id()=="") && !isset($_SESSION['Logged']))
    {
      session_start();
    }
    $dir="";
    if(isset($_SESSION['Logged']))
    {
      $dir=$_SESSION['Logged'];
    }
  }
  $root="Virtual-Desktop/";//Path of Project from Server Root
  $ser=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/".$root."User/Desktop/".$dir;
  $dir=$_SERVER['DOCUMENT_ROOT'].parse_url($ser,PHP_URL_PATH)."/";
?>
