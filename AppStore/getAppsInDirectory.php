<?php
  function getAppsInDirectory($p)
  {
    $files=array();
    if($dh=@opendir($p))
    {
      while(($file=@readdir($dh))!=false)
      {
        if(($file=="." || $file=="..") || $file=="DesktopSettings.fcz" )
        {
          //Do Nothing
          continue;
        }
        else if(!@is_dir($p.$file))
        {
          $ext=explode(".",$file);
          if(end($ext)=="fcz")
          {
            $files[]=$file;
          }
        }
      }
      @closedir($dh);
      if(is_array($files))
      {
        return $files;
      }
      else
      {
        return 0;
      }
    }
  }
?>
