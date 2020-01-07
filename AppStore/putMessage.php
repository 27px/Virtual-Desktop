<?php
  function putMessage($type,$message)
  {
    global $msgcount;
    $msgcount++;
    echo "<script>setTimeout(function(){setMessage(\"".$type."\",\"".$message."\");},".($msgcount*1500).");</script>";
  }
?>
