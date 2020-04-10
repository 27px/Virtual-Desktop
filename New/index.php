<?php
ob_start();
session_start();
$fn="";
$ln="";
$em="";
$alreadyregistered=0;
?>
<html>
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
  <meta name="theme-color" content="#1ab188">
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
    width:365px;
    height:475px;
    min-width:275px;
    min-height:400px;
    background-color:#24303c;
    border:1px solid #1ab188D0;
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    border-radius:8px;
    box-shadow:0px 0px 10px 3px #000000;
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
    display:inline-block;
    position:relative;
    top:0;
    left:50%;
    transform:translateX(-50%);
    background-position:center;
    background-repeat:repeat-x;
    background-size:80%;
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
    width:calc(100% - 40px);
    padding:0px 20px;
    vertical-align:middle;
  }
  div.input *
  {
    transform:translateY(12.5px);
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
    width:calc(50% - 7px);
  }
  input.right
  {
    margin-left:5px;
    float:right;
  }
  input.left
  {
    margin-right:5px;
    float:left;
  }
  input[type="email"]
  {
    width:100%;
  }
  input[type="password"],input[type="text"]
  {
    width:calc(100% - 25px);
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
  form button:hover,form button:focus
  {
    background-color:#23bc90;
    font-weight:600;
  }
  form button:focus
  {
    outline:none;
  }
  div.link
  {
    color:#1ab188;
    text-decoration:underline;
    text-align:center;
    position:relative;
    left:50%;
    transform:translate(-50%,0px);
    transition:color 0.8s;
    display:inline-block;
  }
  div.link:hover,div.link:focus
  {
    color:#FFFFFF;
    text-decoration:none;
    outline:0px solid transparent;
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
  div.switch:hover,div.switch:focus
  {
    background-color:#23bc90;
    outline:1px solid #FFFFFF;
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
  @media only screen and (max-width:400px),screen and (max-height:550px)
  {
    body
    {
      background-color:#24303c;
      overflow:auto;
    }
    div.container
    {
      width:100% !important;
      height:100% !important;
      border-radius:0px;
      box-shadow:0px 0px 0px 0px transparent;
      border:1px solid transparent;
      overflow:auto;
      position:absolute;
      top:0;
      left:0;
      transform:translate(0%,0%);
    }
    input[type="name"]
    {
      width:100%;
    }
    input.right
    {
      margin-left:0px;
    }
    input.left
    {
      margin-right:0px;
    }
    div.picture
    {
      font-size:14px;
    }
  }
  @media only screen and (min-width:500px) and (max-width:900px)
  {
    div.container
    {
      padding-bottom:5px;
    }
    input[type="name"]
    {
      width:calc(50% - 10px);
    }
    input.right
    {
      margin-left:5px;
    }
    input.left
    {
      margin-right:5px;
    }
  }
  @media screen and (max-height:570px) and (max-width:499px),screen and (min-width:901px)
  {
    div.container
    {
      padding-bottom:50px;
    }
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
    var i=_("imtitle");
    if(n>0)
    {
      i.style.color="#00FF00";
      i.style.borderColor="#00FF00";
      i.innerHTML="Selected<div class='symbol' id='xsym'>&#10004</div>";
      unsetMessage();
      var z=list[0];
      var r=new FileReader();
      r.addEventListener("load",function(){
        i.innerHTML="";
        i.style.borderColor="#000000";
        i.style.backgroundImage="url('"+r.result+"')";
      },false);
      r.readAsDataURL(z);
    }
    else
    {
      i.style.color="#FF0000";
      i.style.borderColor="#FF0000";
      i.innerHTML="Cancelled<div class='symbol' id='xsym'>&#10006</div>";
      i.style.backgroundImage="";
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
    require_once("../config/connect_db.php");
    if(isset($_POST['email']) && !empty($_POST['email']))
    {
      $dir=$_POST['email'];
      require_once("../config/root.php");
      $alreadyregistered=0;
      $ext="jpg";
      if(isset($_FILES['profilepicture']))
      {
        $name=$_FILES["profilepicture"]["name"];
        $name=basename($name);
        $ext=explode(".",$name);
        $ext=end($ext);
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
        $xe="";
        if($conn->query("INSERT INTO `Verification` (`User`,`OTP`,`Type`) VALUES('".$_POST['email']."','".$otp."','verify')")==true)
        {
          echo "<div style='display:none'>";
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
          if(@$mail->Send())
          {
            $_SESSION['BUser']=$_POST['email'];
            $_SESSION['bstatus']="verify";
            header("Location:../Blocked/verify.php");
          }
          else
          {
            $xe="OTP Sent Error, Check Internet Connection";
          }
          echo "</div>";
        }
        else
        {
          echo "Database Error";
        }
        if($xe!="")
        {
          echo "<div class='error'>".$xe."</div>";
        }
        //OTP Not Sent
        $_SESSION['BUser']=$_POST['email'];
        $_SESSION['bstatus']="verify";
        header("Location:../Blocked/verify.php");
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
        <input type="password" name="password" id="p" placeholder="Enter Password" maxlength="12" required autocomplete="off" spellcheck="false"><div class="switch" tabindex="0" onclick="togglePassword(_('p'),this.style);" onkeydown="if(event.keyCode==13){togglePassword(_('p'),this.style)};"></div>
        <input type="password" name="confirmpassword" id="cp" placeholder="Confirm Password" maxlength="12" required autocomplete="off" spellcheck="false"><div class="switch" tabindex="0" onclick="togglePassword(_('cp'),this.style);" onkeydown="if(event.keyCode==13){togglePassword(_('cp'),this.style)};"></div>
        <div class="link" onclick="window.location='../Login/index.php';" onkeyup="if(event.keyCode==13){window.location='../Login/index.php'};" tabindex="0">Already have an account ? Login.</div>
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
