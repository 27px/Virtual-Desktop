<?php
ob_start();
session_start();
if(isset($_SESSION["Logged"]))
{
  header("Location:../Desktop/index.php");
}
require_once("../config/database.php");
$table_name="Login";
$useDB="USE $database";
$selectAllFromTable="SELECT * FROM $table_name";
$col1="E-Mail";
$col2="Password";
$USER="";
$EXT="";
$conn=new mysqli($servername, $username, $password);
$passError="false";
if(mysqli_connect_error())
{
  echo("<p class='error'>Database Connection Failed : ".mysqli_connect_error()."</p>");
  die();
}
if(isset($_POST['EMAIL']))
{
  $val=$_POST['EMAIL'];
}
else
{
  $_SESSION['error']="2";
  header("Location:login.php");
}
if(!(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE '".$database."' ")))))
{
  if($conn->query($useDB)===TRUE)
  {
    if(!(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE '".$table_name."' ")))))
    {
      $sql="SELECT * FROM ".$table_name." WHERE EMail='".$val."';";
      $result=$conn->query($sql);
      if(mysqli_num_rows($result)<1)
      {
        $_SESSION['error']="1";
        header('Location:login.php');
      }
      else
      {
        $row=mysqli_fetch_row($result);
        $USER=$val;
        $EXT=$row[4];
        if(isset($_POST['Login']))
        {
          $pass=$_POST['Password'];
          /////Default
          //$sql="SELECT * FROM ".$table_name." WHERE EMail='".$val."' AND password='".$pass."';";
          //$result=$conn->query($sql);
          /////Default

          //SQL Injection Prevented
          $sql="SELECT * FROM ".$table_name." WHERE EMail=? AND password=?;";
          $pro=$conn->prepare($sql);
          $pro->bind_param("ss",$val,$pass);
          $pro->execute();
          $result=$pro->get_result();
          //SQL Injection Prevented

          if(mysqli_num_rows($result)<1)
          {
            $passError="true";
          }
          else
          {
            $row=mysqli_fetch_row($result);
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
    }
    else
    {
      echo "<p class='error'>Error Table Does/'t exixt: " . $conn->error."</p>";
    }
  }
  else
  {
    echo "<p class='error'>Error Changing Database : ".$conn->error."</p>";
    die();
  }
}
?>
<html>
<head>
<title>Log In</title>
<style>
*
{
  padding:0;
  margin:0;
}
body
{
  overflow:hidden;
}
div.blurbg
{
  width:calc(100vw + 50px);
  height:calc(100vh + 50px);
  position:fixed;
  top:50%;
  left:50%;
  background:url("../Images/3.jpg"),radial-gradient(#8dffdd,#05a966);
  background-size:calc(100vw + 15px) calc(100vh + 15px);
  background-position:center;
  z-index:-100;
  filter:blur(5px);
  transform:translate(-50%,-50%);
}
*:focus
{
  outline:none;
}
div.bg
{
  width:100%;
  height:100%;
}
div.form
{
  width:40%;
  height:80%;
  min-width:400px;
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  background:url("../Images/3.jpg"),radial-gradient(#8dffdd,#05a966);
  background-size:100vw 100vh;
  background-position:center;
  box-shadow:0px 10px 20px 5px #000000;
  border-right:5px solid #000000;
  border-left:5px solid #000000;
  border-radius:15px;
  box-sizing:border-box;
  text-align:center;
}
div.form::before,div.form::after
{
  content:"";
  border-top:17.5px solid rgba(0,0,0,0.5);
  border-bottom:17.5px solid rgba(0,0,0,0.5);
  border-left:140px solid rgba(0,0,0,0.5);
  border-right:20px solid transparent;
  z-index:100;
}
div.form::before
{
  position:absolute;
  top:5px;
  left:-50px;
  transform:rotate(-40deg);
}
div.form::after
{
  position:absolute;
  top:5px;
  right:-50px;
  transform:rotate(40deg);
}
div.l
{
  width:50%;
  height:100%;
}
div.r
{
  width:50%;
  height:100%;
}
div.Segment
{
  width:60%;
  height:90%;
  min-width:350px;
  background:rgba(0,0,0,0.5);
  display:inline-block;
  border-top:5px solid #000000;
  border-bottom:5px solid #000000;
  border-radius:5px;
  box-sizing:border-box;
  position:relative;
  top:50%;
  transform:translateY(-50%);
  overflow:hidden;
}
div.topcircle
{
  width:70vmin;
  height:70vmin;
  background:radial-gradient(#C0C0C0,#505050,#000000);
  border-radius:50%;
  position:relative;
  top:0%;
  left:50%;
  transform:translate(-50%,-60%);
  box-shadow:0px 0px 40px 3px #000000 inset;
}
div.Segment p.title
{
  position:absolute;
  top:5%;
  color:#FFFFFF;
  display:block;
  width:100%;
  color:#FFFFFF;
  font-size:25px;
  font-family:arial black;
  -webkit-text-stroke:1px #000000;
  text-shadow:0px 0px 20px #000000;
  user-select:none;
}
div.avatar
{
  width:150px;
  height:150px;
  border-radius:50%;
  position:absolute;
  bottom:0%;
  left:50%;
  transform:translate(-50%,40%);
  border:2.5px solid #FFFFFF;
  background:#000000 url(logprofileicon/1.svg);
  background-size:80%;
  background-position:center;
  box-shadow:0px 0px 20px 1px #000000 inset;
}
div.PasswordContainer
{
  display:block;
  width:100%;
  position:absolute;
  bottom:12%;
  left:50%;
  text-align:center;
}
input[type="password"],input[type="text"]
{
	width:100%;
	display:block;
	background-color:transparent;
	font-size:20px;
	border:none;
	font-family:serif;
	color:#FFFFFF;
  padding-left:10px;
  padding-right:10px;
  padding-top:10px;
  padding-bottom:10px;
}
input:focus
{
	outline:none;
}
input[type="password"]::placeholder,input[type="text"]::placeholder
{
  color:B0B0B0;
  user-select:none;
}
fieldset legend
{
	color:#FFFFFF;
	font-size:17px;
  padding-left:4px;
  padding-right:4px;
  padding-bottom:0px;
	font-family:serif;
	display:none;
	margin-left:10px;
}
fieldset
{
  overflow:visible;
  text-align:left;
	margin-bottom:15%;
	border:2px solid #00FFFF;
	border-right:50px solid #00FFFF;
	border-radius:10px;
  width:80%;
  transform:translateX(-50%);
}
div.buttonset
{
  position:absolute;
  bottom:5%;
  width:100%;
  padding:10px;
  box-sizing:border-box;
}
div.buttonset input
{
  width:23%;
  min-height:30px;
  font-family:arial black,arial,serif;
  padding-top:5px;
  padding-bottom:5px;
  border-radius:5px;
}
div.buttonset input.reset
{
  background:linear-gradient(135deg,#00A0A0,#009090,#00A0A0);
  border-color:#00FFFF;
}
div.buttonset input.reset:hover
{
  background:linear-gradient(135deg,#00FFFF,#00FFFF,#00FFFF);
}
div.buttonset input.next
{
  background:linear-gradient(135deg,#00A000,#009000,#00A000);
  border-color:#00FF00;
}
div.buttonset input.next:hover
{
  background:linear-gradient(135deg,#00FF00,#00FF00,#00FF00);
}
div.buttonset input.home
{
  background:linear-gradient(135deg,#A0A000,#909000,#A0A000);
  border-color:#FFFF00;
}
div.buttonset input.home:hover
{
  background:linear-gradient(135deg,#FFFF00,#FFFF00,#FFFF00);
}
div.buttonset input.cancel
{
  background:linear-gradient(135deg,#A00000,#900000,#A00000);
  border-color:#FF0000;
  color:#FFFFFF;
}
div.buttonset input.cancel:hover
{
  background:linear-gradient(135deg,#FF0000,#FF0000,#FF0000);
}
p.noaccount
{
  width:100%;
  text-align:center;
  position:absolute;
  bottom:16.5%;
}
p.noaccount a
{
  width:100%;
  height:100%;
  font-family:Edwardian Script ITC,serif;
  font-size:25px;
  color:#C0C0C0;
  text-decoration:none;
  letter-spacing:2px;
}
p.noaccount a:hover
{
  color:#FFFFFF;
  text-decoration:underline;
}
div.picon
{
  width:25px;
  height:25px;
  background-image:url("unlocked.svg");
  background-size:cover;
  position:absolute;
  border-radius:50%;
  right:0%;
  bottom:0%;
  box-sizing:border-box;
  transform:translate(150%,-35%);
  z-index:100;
}
div.error
{
  position:relative;
  left:-50%;
  border-top:1px solid red;
  border-bottom:6px solid red;
  display:block;
  width:90%;
  background-color:#000000;
  color:#FF0000;
  padding:5px;
  display:none;
  text-align:left;
  margin-left:4%;
  margin-bottom:10px;
}
div.error::after
{
  content:"";
  position:absolute;
  bottom:-52%;
  left:5%;
  border-left:10px solid transparent;
  border-right:10px solid transparent;
  border-top:10px solid #FF0000;
}
a,input[type="button"],input[type="reset"],input[type="submit"]
{
  user-select:none;
}
p.error
{
	width:100%;
	height:auto;
	font-size:22px;
	padding:10px;
	text-align:center;
	margin-top:5px;
	font-family:sans-serif;
	font-weight:900;
	box-sizing:border-box;
	background-color:#FF0000;
	color:#FFFFFF;
}
</style>
<script>
function x(e)
{
	var s="L"+e.id;
	document.getElementById(s).style.display="block";
	document.getElementById("E").style.paddingTop="0px";
}
function y(e)
{
	var s="L"+e.id;
	document.getElementById(s).style.display="none";
	document.getElementById("E").style.paddingTop="10px";
}
function setError(msg)
{
  var error=document.getElementById("errorMessage");
  var b=document.getElementById("FE");
  error.style.display="block";
  error.innerHTML=msg;
  b.style.borderColor="#FF0000";
}
function unsetError()
{
  var error=document.getElementById("errorMessage");
  var b=document.getElementById("FE");
  b.style.borderColor="#00FF00";
  error.style.display="none";
}
function check()
{
  var email=document.getElementById("E").value;
  var length=email.length;
  var c='';
  if(email=="")
  {
    setError("Enter Password !");
    return false;
  }
  else if(email.length<=6)
  {
    setError("Less than Limit !");
    return false;
  }
  unsetError();
  return true;
}
function r()
{
  var error=document.getElementById("errorMessage");
  var b=document.getElementById("FE");
  b.style.borderColor="#00FFFF";
  document.getElementById("E").value="";
  error.style.display="none";
}
function toggleView(x)
{
  var E=document.getElementById("E");
  if(E.type==="password")
  {
    E.type="text";
    x.style.backgroundImage="url('locked.svg')";
  }
  else if(E.type==="text")
  {
    E.type="password";
    x.style.backgroundImage="url('unlocked.svg')";
  }
}
</script>
</head>
<body>
<div class="blurbg"></div>
<div class="circle"></div>
<div class="bg bg1"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<div class="form">
<div class="Segment">
<div class="topcircle">
  <div class="avatar" id="avatar"></div>
</div>
<p class="title"><?php echo $USER; ?></p>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="PasswordContainer">
<div class="error" id="errorMessage">Error Message !</div>
<fieldset id="FE">
  <legend id="LE"> Password </legend>
  <input type="password" name="Password" placeholder="Enter Password" oninput="check()" id="E" onfocus="x(this);" onblur="y(this)" spellcheck="false" autocomplete="off">
  <div class="picon" tabindex="0" onclick="toggleView(this);"></div>
</fieldset>
</div>
<p class="noaccount"><a href="../ResetPassword/">Forgot Password ?&nbsp; Reset Password.</a></p>
<div class="buttonset">
<a href="../Login/Login.php"><input class="cancel" type="button" value="Back"></a>
<a href="../Home"><input class="home" type="button" value="Home"></a>
<input class="reset" type="reset" value="Reset" onclick="return r();">
<input class="next" type="submit" name="Login" value="Next" onclick="return check();">
<input type="hidden" name="EMAIL" value="<?php echo $USER; ?>">
</div>
</form>
</div>
</div>
<?php
  echo "<script>";
  echo "document.getElementById('avatar').style.backgroundImage=\"url('../User/Profile/".$USER.".".$EXT."')\";";
?>
if(<?php echo $passError; ?>)
{
  setError("Incorrect Password !");
}
<?php
  echo "</script>";
?>
</body>
</html>
