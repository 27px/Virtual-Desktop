<?php
  function getTotal($x)
  {
    $totalsize=0;
    if(is_dir($x))
    {
      if($dh=opendir($x))
      {
        while($file=readdir($dh))
        {
          if($file=="." || $file=="..")
          {
            continue;
          }
          if(is_dir($x."/".$file))
          {
            $totalsize+=getTotal($x."/".$file);
          }
          else
          {
            $size=filesize($x."/".$file);
            $totalsize+=$size;
          }
        }
      }
    }
    else if(file_exists($x))
    {
      $totalsize+=filesize($x);
    }
    return $totalsize;
  }
  function nextFile($destination,$file)
  {
    $destination=str_replace("\\","/",$destination);
    $file=str_replace("\\","/",$file);
    if(!@file_exists($destination.$file))
    {
      return $file;
    }
    if(!@is_dir($destination.$file))
    {
      //File
      $ext="";
      $f=explode(".",$file);
      $ext=end($f);
      $i=0;
      $fx="";
      while($i<(count($f)-1))
      {
        $fx.=$f[$i];
        if($i!=(count($f)-2))
        {
          $fx.=".";
        }
        $i++;
      }
      $i=1;
      while(@file_exists($destination.$fx." Copy (".$i.").".$ext))
      {
        $i++;
      }
      $file=$fx." Copy (".$i.").".$ext;
      return $file;
    }
    else
    {
      //Directory
      $i=1;
      while(@file_exists($destination.$file." Copy (".$i.")"))
      {
        $i++;
      }
      $file.=" Copy (".$i.")";
      return $file;
    }
    return $file;
  }

  function getDesktopSettings()
  {
    global $dir;
    $file="DesktopSettings.fcz";
    $verifyType=0;
    $r="";
    if(@file_exists($dir."/".$file))
    {
      $f=@fopen($dir."/".$file,"r");
      while(!@feof($f))
      {
        $fx=@fgets($f);
        $fxa=explode("\"",$fx);
        if($fxa[1]=="type" && $fxa[3]=="DesktopSettings")
        {
          $verifyType=1;
        }
        else if($verifyType!=0)
        {
          if($fxa[1]!="" && $fxa[3]!="")
          {
            $r[$fxa[1]]=$fxa[3];
          }
        }
      }
      @fclose($f);
    }
    return $r;
  }
  function newWallpaper($ext)
  {
    $newName="New Wallpaper.";
    return $newName.$ext;
  }
  function getFilesInDirectory($p)
  {
    $files='';
    if($dh=@opendir($p))
    {
      while(($file=@readdir($dh))!=false)
      {
        if($file=="."||$file=="..")
        {
          //Do Nothing
          continue;
        }
        else
        {
          $files[]=$file;
        }
      }
      @closedir($dh);
      return $files;
    }
  }
  function paste($sourcedirectory,$destinationdirectory,$items,$operation)
  {
    if(is_array($items))
    {
      $i=0;
      if($items=="")
      {
        //error
        return;
      }
      $n=count($items);
      while($i<$n)
      {
        $newCopy=nextFile($destinationdirectory,$items[$i]);
        $item=$items[$i];
        if(!@is_dir($sourcedirectory."\\".$items[$i]))
        {
          @copy($sourcedirectory.$items[$i],$destinationdirectory.$newCopy);
          if($operation=="Cut")
          {
            if(@is_dir($sourcedirectory.$item))
            {
              delete($sourcedirectory.$item);
            }
            else if(@file_exists($sourcedirectory.$item))
            {
              @unlink($sourcedirectory.$item);
            }
          }
        }
        else if(is_dir($sourcedirectory."\\".$items[$i]))
        {
          //Directory
          $newCopy=nextFile($destinationdirectory,$items[$i]);
          if(!@file_exists($destinationdirectory.$items[$i]) || !@is_dir($destinationdirectory.$newCopy))
          @mkdir($destinationdirectory.$newCopy,0777);
          $toexecnextpaste=getFilesInDirectory($sourcedirectory.$items[$i]);
          if($toexecnextpaste!="")
          paste($sourcedirectory.$items[$i]."/",$destinationdirectory.$newCopy."/",$toexecnextpaste,$operation);
          if($operation=="Cut")
          {
            if(@is_dir($sourcedirectory.$item))
            {
              @delete($sourcedirectory.$item);
            }
            else if(@file_exists($sourcedirectory.$item))
            {
              @unlink($sourcedirectory.$item);
            }
          }
        }
        $i++;
      }
    }
  }
  function byte_unit_convert($b)
  {
    $Size="";
    $byteA=array(" B"," KB"," MB"," GB"," TB"," PB"," EB"," ZB"," YB");
    $bi=0;
    if($b<1024)
    $Size=$b;
    while($b>=1024 && $bi<8)
    {
      $bi++;
      $b/=1024;
      $Size=$b;
    }
    $Size=floor($Size*100)/100;
    if($bi<9)
    {
      $Size.=$byteA[$bi];
    }
    return $Size;
  }
  function delete($p)
  {
    if($dh=@opendir($p))
    {
      while(($file=@readdir($dh))!=false)
      {
        if($file=="."||$file=="..")
        {
          //Do Nothing
          continue;
        }
        else if(@file_exists($p."/".$file) && !@is_dir($p."/".$file))
        {
          @unlink($p."/".$file);
        }
        else if(@is_dir($p."/".$file))
        {
          delete($p."/".$file);
          if(@file_exists($p."/".$file) && @is_dir($p."/".$file))
          {
            @rmdir($p."/".$file);
          }
        }
      }
      @closedir($dh);
      @rmdir($p);
      if(@is_dir($p))
      {
        return false;
      }
      else
      {
        return true;
      }
    }
  }
?>
