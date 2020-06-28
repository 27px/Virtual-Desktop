<?php
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
  function getDirData($d)
  {
    $files=array();
    if($dh=opendir($d))
    {
      while(($file=readdir($dh))!=false)
      {
        if($file=="."||$file=="..")
        {
          continue;
        }
        else if(is_dir($d."/".$file))
        {
          $files[]=$d."/".$file;
        }
      }
    }
    if($dh=opendir($d))
    {
      while(($file=readdir($dh))!=false)
      {
        if($file=="."||$file=="..")
        {
          continue;
        }
        else if(is_dir($d."/".$file))
        {
          continue;
        }
        else
        {
          $e=explode(".",$file);
          $e=end($e);
          if($e=="fcz")
          {
            continue;
          }
          $files[]=$d."/".$file;
        }
      }
    }
    return $files;
  }
?>
