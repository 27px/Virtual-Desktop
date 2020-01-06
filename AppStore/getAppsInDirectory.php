<?php
  function getAppsInDirectory($p)
  {
    $files='';
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
          if(end(explode(".",$file))=="fcz")
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
