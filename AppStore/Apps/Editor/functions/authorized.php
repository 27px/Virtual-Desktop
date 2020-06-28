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
?>
