<?php
ob_start();
session_start();
$fn="";
$ln="";
$em="";
$alreadyregistered=0;
require_once("../config/root.php");
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
    background:linear-gradient(0deg,#209b97,#23bc90);
    user-select:none;
  }
  div.container
  {
    width:27vw;
    height:80vh;
    min-width:370px;
    min-height:525px;
    background-color:#24303c;
    border:1px solid #1ab188D0;
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    border-radius:8px;
    box-shadow:0px 0px 10px 5px #000000;
    overflow:hidden;
  }
  div.title
  {
    text-align:center;
    color:#1ab188;
    font-size: 30px;
    font-weight:900;
    font-family:arial black,arial,sans-serif,serif;
    padding-top:20px;
  }
  div.container form
  {
    width:100%;
    height:calc(100% - 80px);
    position:absolute;
    bottom:0;
    left:0;
    overflow:hidden;
  }
  div.picture
  {
    width:110px;
    height:110px;
    text-align:center;
    color:#1ab188;
    padding-top:35px;
    box-sizing:border-box;
    border-radius:50%;
    border:2px solid #1ab188;
    position:absolute;
    top:0;
    left:50%;
    transform:translateX(-50%);
  }
  div.picture:hover
  {
    cursor:cell;
    background-color:rgba(0,255,128,0.1);
  }
  div.imgstat
  {
    width:100%;
    position:absolute;
    top:120px;
    left:0;
  }
  div.input
  {
    position:absolute;
    top:120px;
    left:0;
    width:100%;
    padding:20px;
    vertical-align:middle;
  }
  input
  {
    height:35px;
    font-family:serif;
    box-sizing:border-box;
    vertical-align:middle;
    margin-bottom:25px;
    background-color:rgba(255,255,255,0.1);
    padding:0px 10px;
    color:#00ffb6;
    font-size:20px;
    border:1px solid #21a894;
    letter-spacing:2px;
  }
  input::placeholder
  {
    color:#21a894;
    letter-spacing:1px;
  }
  input:focus
  {
    outline:none;
    background-color:rgba(0,255,128,0.1);
  }
  input[type="name"]
  {
    width:calc(50% - 26.5px);
  }
  input.right
  {
    margin-left:5px;
  }
  input.left
  {
    margin-right:5px;
  }
  input[type="email"]
  {
    width:calc(100% - 40px);
  }
  input[type="password"],input[type="text"]
  {
    width:calc(100% - 65px);
  }
  input::selection
  {
    color:#FFFFFF;
    background-color:#21a894;
  }
  div.switch
  {
    width:25px;
    height:35px;
    background-color:#21a894;
    display:inline-block;
    vertical-align:middle;
    margin-bottom:25px;
  }
  form button
  {
    width:100%;
    height:45px;
    position:absolute;
    bottom:0;
    left:0;
    border:none;
    outline:none;
    background-color:#209b97;
    font-family:consolas,arial,sans-serif,serif;
    font-weight:400;
    font-size:25px;
    color:#24303c;
    transition:background-color 0.5s,font-weight 0.5s;
  }
  form button:hover
  {
    background-color:#23bc90;
    font-weight:600;
  }
  form button:focus
  {
    outline:none;
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
  .hidden
  {
    display:none !important;
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
  div.messageyellow::before
  {
    border-top:10px solid #FFFF00;
  }
  div.x
  {
    display:inline-block;
    float:right;
  }
</style>
<script>
  function _(id)
  {
    return document.getElementById(id);
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
  function check(event,x)
  {
    if(!((x>=65 && x<=90) || x==8 || x==32 || x==46 || x==116 || x==37 || x==39 || x==9))
    {
      event.preventDefault();
      return false;
    }
    return true;
  }
  function validateProfilePicture(x)
  {
    var list=x.files;
    var n=list.length;
    if(n>0)
    {
      _("imtitle").style.color="#00FF00";
      _("imtitle").style.borderColor="#00FF00";
      _("imtitle").innerHTML="Selected<div class='symbol' id='xsym'>&#10004</div>";
      unsetMessage();
    }
    else
    {
      _("imtitle").style.color="#FF0000";
      _("imtitle").style.borderColor="#FF0000";
      _("imtitle").innerHTML="Cancelled<div class='symbol' id='xsym'>&#10006</div>";
    }
  }
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
  function createAccount()
  {
    unsetMessage();
    if(_("fn").value=="")
    {
      setMessage("Enter First Name.",_("fn").getBoundingClientRect(),"red");
      _("fn").focus();
      return false;
    }
    if(_("ln").value=="")
    {
      setMessage("Enter Last Name.",_("ln").getBoundingClientRect(),"red");
      _("ln").focus();
      return false;
    }
    if(_("e").value=="")
    {
      setMessage("Enter E_Mail ID",_("e").getBoundingClientRect(),"red");
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
    var list=_("pp").files;
    var n=list.length;
    if(n<1)
    {
      setMessage("Select Profile Picture",_("imtitle").getBoundingClientRect(),"yellow");
      return false;
    }
    _("form").submit();
  }
</script>
</head>
<body>
  <?php
    if(isset($_POST['email']) && !empty($_POST['email']))
    {
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
      $ext="jpg";
      if(isset($_FILES['profilepicture']))
      {
        $name=$_FILES["profilepicture"]["name"];
        $name=basename($name);
        $ext=end(explode(".",$name));
        $tmp=$_FILES["profilepicture"]["tmp_name"];
        $size=$_FILES["profilepicture"]["size"];
        $error=$_FILES["profilepicture"]["error"];
        $path=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Profile/";
        if($error==0)
        {
          if(!@move_uploaded_file($tmp,$path.$_POST['email'].".1.".$ext)==true)
          {
            echo "Move Error";
          }
        }
      }
      else
      {
        $ext="jpg";
      }
      $result=$conn->query("SELECT * FROM `Login` WHERE EMail='".$_POST['email']."' LIMIT 1");
      $n=mysqli_num_rows($result);
      if($n>0)
      {
        $alreadyregistered=1;
        $fn=$_POST['firstname'];
        $ln=$_POST['lastname'];
        $em=$_POST['email'];
      }
      else if($conn->query("INSERT INTO `Login` (`EMail`,`Password`,`Profile`,`fname`,`lname`) VALUES('".$_POST['email']."','".$_POST['password']."','1.".$ext."','".$_POST['firstname']."','".$_POST['lastname']."')")==true)
      {
        $otp="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $otp=str_shuffle($otp);
        $otp=substr($otp,0,6);
        if($conn->query("INSERT INTO `Verification` (`User`,`OTP`,`Type`) VALUES('".$_POST['email']."','".$otp."','verify')")==true)
        {
          $to=$_POST['email'];
          date_default_timezone_set('Asia/Kolkata');
          require_once($_SERVER['DOCUMENT_ROOT']."/".$root.'\Plugins\Mail\class.phpmailer.php');
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
          $mail->Subject="Account Verification";
          $body="Verification for Registering Virtual Desktop account (".$_POST['email']."), Registered for User ".$_POST['firstname']." ".$_POST['lastname'].", OTP : ".$otp;
          $mail->MsgHTML($body);
          $address=$to;
          $mail->AddAddress($address,"Virtual Desktop");
          if($mail->Send())
          {
            $_SESSION['BUser']=$_POST['email'];
            $_SESSION['bstatus']="verify";
            header("Location:../Blocked/verify.php");
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
      else
      {
        echo "Database Error";
      }
    }
  ?>
  <div class="container">
    <div class="title">Sign Up</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="form" enctype="multipart/form-data">
    <input class="hidden" name="profilepicture" id="pp" type="file" accept="image/*" onchange="validateProfilePicture(this);">
      <div class="picture" id="imtitle" onclick="_('pp').click();">Select Picture<div class="symbol" id="xsym">+</div></div>
      <div class="input">
        <input type="name" name="firstname" id='fn' value="<?php echo $fn; ?>" class="left" placeholder="First Name" required autocomplete="off" spellcheck="false" onkeydown="check(event,event.keyCode);">
        <input type="name" name="lastname" id="ln" value="<?php echo $ln; ?>" class="right" placeholder="Last Name" required autocomplete="off" spellcheck="false">
        <input type="email" name="email" id='e' value="<?php echo $em; ?>" placeholder="Enter E-Mail" required autocomplete="off" spellcheck="false">
        <input type="password" name="password" id="p" placeholder="Enter Password" maxlength="12" required autocomplete="off" spellcheck="false"><div class="switch" onclick="togglePassword(_('p'),this.style);"></div>
        <input type="password" name="confirmpassword" id="cp" placeholder="Confirm Password" maxlength="12" required autocomplete="off" spellcheck="false"><div class="switch" onclick="togglePassword(_('cp'),this.style);"></div>
      </div>
      <button type="button" id="bt" onclick="createAccount();">Get Started</button>
    </form>
  </div>
  <div class="message" id="message"></div>
  <script>
  var xzc=_("form").getBoundingClientRect();
  _("message").style.width=(parseInt(xzc.width)-49)+"px";
  <?php
  if($alreadyregistered==1)
  {
    echo "setMessage('Email ID Already Registered.',_('e').getBoundingClientRect(),'red');";
  }
  ?>
  </script>
</body>
</html>
