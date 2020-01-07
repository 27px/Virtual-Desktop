<?php
require_once("getAppsInDirectory.php");
function checkInstalledApp()
{
    global $dir;
    if($dir=="")
    {
      if(!isset($_SESSION['Logged']))
      {
        session_start();
      }
      require_once("dir.php");
    }
    global $inversion;
    if(!is_array($inversion))
    {
      $inversion=array();
    }
    $r[]=5;//File Manager // Already Installed
    $v[]=1;//File Manager // Version
    $apps=getAppsInDirectory($dir);
    if(!is_array($apps))
    {
      $inversion[]=$r;
      $inversion[]=$v;
      return $inversion;
    }
    $n=count($apps);
    $i=0;
    while($i<$n)
    {
      $file=$apps[$i];
      if(@file_exists($dir.$file))
      {
        $f=@fopen($dir.$file,"r");
        while(!@feof($f))
        {
          $fx=@fgets($f);
          $fxa=explode("\"",$fx);
          if($fxa[1]=="id" && $fxa[3]!="")
          {
            $r[]=$fxa[3];
          }
          else if($fxa[1]=="version" && $fxa[3]!="")
          {
            $v[]=$fxa[3];
          }
        }
        @fclose($f);
      }
      $i++;
    }
    $inversion[]=$r;
    $inversion[]=$v;
    return $inversion;
  }
?>
