




//Nofun
function dumpFileProperties()
{
  document.getElementById('fname').innerHTML="<?php echo $FileName; ?>";
  document.getElementById('fpath').innerHTML="Root<?php if($url!=""){$tmp=explode($_SESSION['Logged'],$url); echo end($tmp);} else echo "/"; ?>";
  document.getElementById('ftype').innerHTML="<?php echo strtoupper($FileType); ?>";
  document.getElementById('fext').innerHTML="<?php echo $FileExt; ?>";
  document.getElementById('fmd').innerHTML="<?php echo $fileModifiedDate; ?>";
  document.getElementById('fproperties').innerHTML="<?php echo $FileProperties; ?>";
}
