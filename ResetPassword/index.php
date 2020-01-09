<?php
ob_start();
session_start();
$e="";
$otperror=0;
require_once("../config/root.php");
function emailform()
{
  global $e;
  ?>

  <div class="container">
    <div class="title">Reset Password</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="form" enctype="multipart/form-data">
      <div class="input">
        <input type="email" name="email" id='e' value="<?php echo $e; ?>" placeholder="Enter E-Mail" required autocomplete="off" spellcheck="false">
      </div>
      <button type="button" id="bt" onclick="resetPassword();">Reset Password</button>
    </form>
  </div>
  <div class="message" id="message"></div>

  <?php
}
function otpform()
{
  ?>

  <div class="container">
    <div class="title">Reset Password</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="form" enctype="multipart/form-data">
      <div class="input">
        <input type="text" name="otp" id='o' value="" placeholder="Enter OTP" required autocomplete="off" spellcheck="false" maxlength="6">
        <input type="password" name="password" id="p" placeholder="Enter Password" maxlength="12" required autocomplete="off" spellcheck="false"><div class="switch" onclick="togglePassword(_('p'),this.style);"></div>
        <input type="password" name="confirmpassword" id="cp" placeholder="Confirm Password" maxlength="12" required autocomplete="off" spellcheck="false"><div class="switch" onclick="togglePassword(_('cp'),this.style);"></div>
      </div>
      <button type="button" id="bt" onclick="submitOTP();">Reset Password</button>
    </form>
  </div>
  <div class="message" id="message"></div>

  <?php
}
?>
<html>
<head>
  <title>New Account</title>
  <style>
  *
  {
    padding:0;
    margin:0;
  }
  body
  {
    overflow:hidden;
    background:linear-gradient(180deg,#4caf50,#009688);
    user-select:none;
  }
  div.container
  {
    width:27vw;
    height:80vh;
    min-width:370px;
    min-height:525px;
    background:radial-gradient(#7ed0f3,#046aa9);
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    border-radius:8px;
    overflow:hidden;
    box-shadow:10px 10px 30px 1px #000000;
  }
  div.title
  {
    text-align:center;
    color:#252f69;
    font-size: 30px;
    font-weight:900;
    font-family:arial black,arial,sans-serif,serif;
    padding-top:20px;
  }
  div.container form
  {
    width:100%;
    height:100%;
    position:absolute;
    bottom:0;
    left:0;
    overflow:hidden;
    background-image:url("bg.png");
    background-size:contain;
    background-position:bottom;
    background-repeat:no-repeat;
    box-shadow:0px 0px 10px 1px #000000 inset,0px 0px 10px 5px #00000080 inset,0px 0px 20px 10px #00000060 inset;
  }
  div.input
  {
    position:absolute;
    top:120px;
    left:0;
    width:100%;
    padding:20px;
    vertical-align:middle;
    overflow:visible;
  }
  input
  {
    height:35px;
    font-family:serif;
    box-sizing:border-box;
    vertical-align:middle;
    margin-bottom:25px;
    background-color:transparent;
    padding:0px 10px;
    color:#252f69;
    font-size:20px;
    border-top:none;
    border-left:none;
    border-right:none;
    border-bottom:3px solid #2d3a83;
    letter-spacing:2px;
    overflow:visible;
  }
  input::placeholder
  {
    color:#2d3a83A0;
    letter-spacing:1px;
  }
  input:focus
  {
    outline:none;
  }
  input[type="email"]
  {
    width:calc(100% - 40px);
    position:relative;
    top:30px;
    left:0;
  }
  input::selection
  {
    color:#FFFFFF;
    background-color:#00000060;
  }
  input[type="password"],input[type="text"]
  {
    width:calc(100% - 40px);
  }
  div.switch
  {
    width:25px;
    height:35px;
    background-color:transparent;
    display:inline-block;
    vertical-align:middle;
    margin-bottom:25px;
    transform:translateX(-100%);
  }
  div.symbol
  {
    font-size:30px;
    font-weight:900;
    font-family:arial black,arial,sans-serif,serif;
  }
  div.switch
  {
    background-image:url("locked.svg");
    background-size:75%;
    background-position:center;
    background-repeat:no-repeat;
  }
  div.switch:hover
  {
    cursor:pointer;
  }
  form button
  {
    width:calc(100% - 40px);
    height:45px;
    position:absolute;
    top:330;
    left:0;
    border-top:5px solid transparent;
    border-bottom:5px solid transparent;
    border-left:5px solid #000000;
    border-right:5px solid #000000;
    outline:none;
    background-color:rgba(0,0,0,0.2);
    font-family:consolas,arial,sans-serif,serif;
    font-weight:400;
    font-size:25px;
    color:#24303c;
    transition:background-color 0.5s,font-weight 0.5s;
    margin-left:20px;
    margin-right:20px;
    box-sizing:border-box;
  }
  form button:hover
  {
    background-color:#23bc90;
    font-weight:600;
  }
  form button:focus
  {
    outline:1px solid #000000;
  }
  div.message
  {
    position:absolute;
    top:0;
    left:0;
    background-color:#000000;
    min-width:250px;
    padding:5px;
    display:none;
  }
  div.messagered
  {
    border-top:1px solid #FF0000;
    border-bottom:5px solid #FF0000;
    color:#FF0000;
  }
  div.messageyellow
  {
    border-top:1px solid #FFFF00;
    border-bottom:5px solid #FFFF00;
    color:#FFFF00;
  }
  div.messageleft::before
  {
    content:"";
    border-bottom:10px solid transparent;
    border-left:10px solid transparent;
    border-right:10px solid transparent;
    position:absolute;
    transform:translate(10px,calc(100% + 8px));
    background-color:transparent;
  }
  div.messagered::before
  {
    border-top:10px solid #FF0000;
  }
  div.x
  {
    display:inline-block;
    float:right;
  }
</style>
<script>
function unsetMessage()
{
  message.style.display="none";
  message.innerHTML="";
  message.setAttribute("class","message");//Clear all other classes
}
function setMessage(msg,x,color)
{
  unsetMessage();
  var message=_("message");
  message.innerHTML=msg+"<div class='x' onclick='unsetMessage();'>&#10006;</div>";
  message.style.top=parseInt(x.top)-43;
  message.style.left=parseInt(x.left);
  message.classList.add("messageleft");
  message.classList.add("message"+color);
  message.style.display="inline-block";
}
function _(id)
{
  return document.getElementById(id);
}
function resetPassword()
{
  unsetMessage();
  if(_("e").value=="")
  {
    setMessage("Enter E-Mail ID.",_("e").getBoundingClientRect(),"red");
    _("e").focus();
    return false;
  }
  var re=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if(re.test(String(_("e").value).toLowerCase())==false)
  {
    setMessage("Incorrect",_("e").getBoundingClientRect(),"red");
    _("e").focus();
    return false;
  }
  _("form").submit();
}
function submitOTP()
{
  unsetMessage();
  if(_("o").value=="")
  {
    setMessage("Enter OTP.",_("o").getBoundingClientRect(),"red");
    _("o").focus();
    return false;
  }
  if(_("o").value.length<6)
  {
    setMessage("Enter Minimum 6 Characters",_("o").getBoundingClientRect(),"red");
    _("o").focus();
    return false;
  }
  if(_("p").value=="")
  {
    setMessage("Enter Password",_("p").getBoundingClientRect(),"red");
    _("p").focus();
    return false;
  }
  if(_("p").value.length<8)
  {
    setMessage("Enter Minimum 8 Characters",_("p").getBoundingClientRect(),"red");
    _("p").focus();
    return false;
  }
  if(_("cp").value=="")
  {
    setMessage("Confirm Password",_("cp").getBoundingClientRect(),"red");
    _("cp").focus();
    return false;
  }
  if(_("p").value!=_("cp").value)
  {
    setMessage("Enter Same Password",_("cp").getBoundingClientRect(),"yellow");
    _("cp").focus();
    return false;
  }
  _("form").submit();
}
function togglePassword(x,y)
{
  if(x.getAttribute('type')=="password")
  {
    x.setAttribute('type',"text");
    y.backgroundImage="url('unlocked.svg')";
  }
  else
  {
    x.setAttribute('type',"password");
    y.backgroundImage="url('locked.svg')";
  }
}
</script>
</head>
<body>
  <?php
    require_once("../config/database.php");
    $alreadyregistered=0;
    $conn=new mysqli($servername,$username,$password);
    if(mysqli_connect_error())
    {
      die("Connection Error : ".mysqli_connect_error());
    }
    if(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE '".$database."'"))))
    {
      die("Database not Found");
    }
    if(!($conn->query("USE ".$database)==true))
    {
      die("Could'nt change Database");
    }
    if(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE 'Login'"))))
    {
      die("Table not Found");
    }
    $notregistered=0;
    $e="";
    $xform=0;
    if(isset($_POST['email']) && !empty($_POST['email']))
    {
      $e=$_POST['email'];
      $result=$conn->query("SELECT * FROM `Login` WHERE EMail='".$_POST['email']."' LIMIT 1");
      $n=mysqli_num_rows($result);
      if($n<1)
      {
        $notregistered=1;//Account Does not exists
      }
      else
      {
        $otp="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $otp=str_shuffle($otp);
        $otp=substr($otp,0,6);
        $result=$conn->query("Select * FROM `Verification` WHERE User='".$_POST['email']."' AND Type='reset';");
        $n=mysqli_num_rows($result);
        if($n>0)
        {
          $conn->query("DELETE FROM `Verification` WHERE User='".$_POST['email']."' AND Type='reset';");
        }
        if($conn->query("INSERT INTO `Verification` (`User`,`OTP`,`Type`) VALUES('".$_POST['email']."','".$otp."','reset')")==true)
        {
          $to=$_POST['email'];
          date_default_timezone_set('Asia/Kolkata');
          require_once($_SERVER['DOCUMENT_ROOT']."/".$root.'Plugins\Mail\class.phpmailer.php');
          $mail=new PHPMailer();
          $mail->IsSMTP();
          $mail->SMTPDebug=1;
          $mail->SMTPAuth=true;
          $mail->CharSet="UTF-8";
          $mail->SMTPSecure="tls";
          $mail->Host="smtp.gmail.com";
          $mail->Port=25;
          $mail->Username="virtualappstore@gmail.com";
          $mail->Password="#FF00FF00";
          $mail->SetFrom('rahulr0047@gmail.com',"Virtual Desktop");
          $mail->Subject="Password Reset";
          $body="Password Reset for Virtual Desktop account (".$_POST['email']."), OTP : ".$otp;
          $mail->MsgHTML($body);
          $address=$to;
          $mail->AddAddress($address,"Virtual Desktop");
          if($mail->Send())
          {
            global $xtform;
            $xform=1;
            $_SESSION['resetPassword']=$e;
          }
          else
          {
            echo "OTP Sent Error, Check Internet Connection";
          }
        }
        else
        {
          echo "Database Error";
        }
      }
    }
    else if(isset($_POST['otp']) && !empty($_POST['otp']))
    {
      $result=$conn->query("Select * FROM `Verification` WHERE User='".$_SESSION['resetPassword']."' AND Type='reset' LIMIT 1;");
      $n=mysqli_num_rows($result);
      if($n>0)
      {
        $row=mysqli_fetch_row($result);
        if($row[2]==$_POST['otp'])
        {
          $conn->query("UPDATE `Login` SET `Password`='".$_POST['password']."' WHERE EMail='".$_SESSION['resetPassword']."' ");
          $conn->query("DELETE FROM `Verification` WHERE User='".$_SESSION['resetPassword']."' AND Type='reset';");
          header("Location:../Login/index.php");
        }
        else
        {
          $xform=1;
          $otperror=1;
        }
      }
      else
      {
        $xform=1;
        $otperror=1;
      }
    }
    if($xform==0)
    {
      emailform();
    }
    else
    {
      otpform();
    }
  ?>
  <script>
  var xzc=_("form").getBoundingClientRect();
  _("message").style.width=(parseInt(xzc.width)-49)+"px";
  <?php
  if($notregistered==1)
  {
    echo "setMessage('Email ID Not Registered.',_('e').getBoundingClientRect(),'red');";
  }
  if($otperror==1)
  {
    echo "setMessage('OTP Error.',_('o').getBoundingClientRect(),'red');";
  }
  ?>
  </script>
</body>
</html>
