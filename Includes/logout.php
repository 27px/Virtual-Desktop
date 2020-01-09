<?php
  if(session_id()=="" || !isset($_SESSION))
  {
    session_start();
  }
  $_SESSION=array();//Clear all SESSION Variables
  session_destroy();
  setcookie("user_id","",time()-(86400*30),"/");
  setcookie("password","",time()-(86400*30),"/");
?>
