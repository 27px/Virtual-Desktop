<?php
ob_start();
session_start();
$notreg="";
$passError=0;
$userType=0;
require_once("../config/connect_db.php");
$table_name="login";
function login($val,$pass,$x)
{
  global $notreg;
  global $passError;
  global $conn;
  global $table_name;
  $sql="SELECT * FROM ".$table_name." WHERE EMail=?;";
  $pro=$conn->prepare($sql);
  $pro->bind_param("s",$val);
  $pro->execute();
  $result=$pro->get_result();
  if(mysqli_num_rows($result)<1)
  {
    $notreg=$val;
  }
  else
  {
    $sql="SELECT * FROM ".$table_name." WHERE EMail=? AND Password=?;";
    $pro=$conn->prepare($sql);
    $pro->bind_param("ss",$val,$pass);
    $pro->execute();
    $result=$pro->get_result();
    if(mysqli_num_rows($result)<1)
    {
      if($x==1)
      $passError=2;
      else
      $passError=1;
    }
    else
    {
      $row=mysqli_fetch_row($result);
      $_SESSION["Logged"]=$row[1];
      $_SESSION['EMail']=$row[1];
      $_SESSION['password']=$row[2];
      $_SESSION['userType']=$row[3];
      if(isset($_POST['remember']) && $_POST['remember']=="yes")
      {
        setcookie("user_id",$row[1],time()+(86400*30),"/");
        setcookie("password",$row[2],time()+(86400*30),"/");
      }
      $_SESSION["bstatus"]=$row[5];
      $_SESSION["User"]=$row[3];
      if($row[5]=="Allowed")
      {
        $_SESSION["Logged"]=$val;
        header("Location:../Desktop/index.php");
      }
      else if($row[3]=="User")//Not Admin or Manager but Blocked
      {
        $_SESSION["BUser"]=$row[1];
        if($_SESSION['bstatus']=="PBlock")
        {
          header("Location:../Blocked/index.php");
        }
        else
        {
          header("Location:../Blocked/verify.php");
        }
      }
    }
  }
}
if(isset($_POST['login']) && $_POST['login']=="login")
{
  if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['password']) && !empty($_POST['password']))
  {
    login($_POST['user'],$_POST['password'],0);
  }
}
else if(isset($_COOKIE['user_id']) && isset($_COOKIE['password']))
{
  login($_COOKIE['user_id'],$_COOKIE['password'],1);
}
?>
<html>
<head>
  <title>Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
  <link rel="stylesheet" type="text/css" href="style.min.css">
  <script src="script.min.js"></script>
</head>
<body>
  <form class="hidden" id="f1" name="f1" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"><input type="hidden" value="" id="lg" name="login"><input type="hidden" value="no" id="remember" name="remember"></form>
  <div class="container">
    <div class="img">
      <div class="nouser"></div>
      <div class="image" id="image"></div>
    </div>
    <?php
      if($notreg!="")
      {
        $xuserid=$notreg;
      }
      else if(isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id']))
      {
        $xuserid=$_COOKIE['user_id'];
      }
      else
      {
        $xuserid="";
      }
    ?>
    <div class="row"><div class="icon usericon"></div><input type="text" form="f1" name="user" id="user" placeholder="User Id" oninput="changeImage(this.value);" autocomplete="off" value="<?php echo $xuserid; ?>"></div>
    <div class="row"><div class="icon passicon"></div><input type="password" form="f1" name="password" id="password" placeholder="Password"><div class="icon view" id="viewable" onclick="togglePassword(this);viewHoverOn();" onmouseenter="viewHoverOn();" onmouseleave="viewHoverOff();"></div></div>
    <div class="rememberme"><div class="checkbox" id="chb" tabindex="0" checked="no" onkeydown="checkkey(_('chb'),event);" onclick="checkb(_('chb'));"></div><div onclick="checkb(_('chb'));" class="rm">Remember Me</div><div class="forgot" tabindex="0" onclick="window.location='../ResetPassword/index.php';" onkeypress="checkfp(event,'../Login/resetpassword.php');">Forgot Password ?</div></div>
    <div class="button" onclick="login();" onkeydown="checksub(event);" tabindex="0">Log In</div>
    <div class="newaccount" tabindex="0" onclick="window.location='../NewAccount/index.php';" onkeypress="checkfp(event,'../NewAccount/index.php');">Don't have an Account ? Create one.</div>
  </div>
  <div class="reset" onclick="reset();" onkeydown="checkreset(event);" tabindex="0"></div>
  <div class="home" onclick="window.location='../Home/index.php';" onkeydown="checkhome(event);" tabindex="0"></div>
  <div class="message yellow" id="err">Message !<span class='x' onclick='this.parentNode.style.display=\"none\";'>&#10006;</span></div>
  <script id="xsp">
    <?php
      if($notreg!="")
      {
        echo "setMessage('User Id not registered !','user','yellow');";
        $notreg="";
      }
      else if($passError===1)
      {
        echo "setMessage('Incorrect Password !','password','red');";
        $passError="";
      }
      else if($passError===2)
      {
        echo "setMessage('Password Changed','password','red');";
        $passError="";
      }
    ?>
    changeImage(_('user').value);
    _('xsp').parentNode.removeChild(_('xsp'));
  </script>
</body>
</html>
