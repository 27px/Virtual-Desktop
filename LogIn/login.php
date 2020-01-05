<?php
session_start();
if(isset($_SESSION["Logged"]))
{
  header("Location:../Desktop/index.php");
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
  background:url("../Images/2.jpg"),radial-gradient(#8dddff,#0566a9);
  background-size:calc(100vw + 15px) calc(100vh + 15px);
  background-position:center;
  z-index:-100;
  filter:blur(5px);
  transform:translate(-50%,-50%);
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
  background:url("../Images/2.jpg"),radial-gradient(#8dddff,#0566a9);
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
  max-height:475px;
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
  top:0%;
  color:#FFFFFF;
  display:block;
  width:100%;
  color:#FFFFFF;
  font-size:50px;
  font-family:arial black;
  -webkit-text-stroke:2px #000000;
  text-shadow:0px 0px 10px #000000;
  user-select:none;
}
div.avatar
{
  width:150px;
  height:150px;
  background-color:#C0C0C0;
  border-radius:50%;
  position:absolute;
  bottom:0%;
  left:50%;
  transform:translate(-50%,40%);
  border:2.5px solid #000000;
  background-image:url(logprofileicon/1.svg);
  background-size:cover;
  box-shadow:0px 0px 40px 1px #000000 inset;
}
div.PasswordContainer
{
  display:block;
  width:100%;
  position:absolute;
  bottom:25%;
  left:0%;
  text-align:center;
}
input[type="email"]
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
input[type="email"]::placeholder
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
  display:inline-block;
	border:2px solid #00FFFF;
	border-right:50px solid #00FFFF;
	border-radius:10px;
  width:80%;
}
div.error
{
  position:relative;
  border-top:1px solid #FF0000;
  border-bottom:6px solid #FF0000;
  display:block;
  width:90%;
  background-color:#000000;
  color:#FF0000;
  padding:5px;
  display:none;
  text-align:left;
  margin-bottom:10px;
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
}
p.noaccount a:hover
{
  color:#FFFFFF;
  text-decoration:underline;
}
div.eicon
{
  width:45px;
  height:45px;
  background-image:url("eicon.svg");
  background-size:cover;
  position:absolute;
  border-radius:50%;
  right:11px;
  bottom:0%;
  box-sizing:border-box;
  z-index:100;
}
div.error::after
{
  content:"";
  position:absolute;
  bottom:-48%;
  left:5%;
  border-left:10px solid transparent;
  border-right:10px solid transparent;
  border-top:10px solid #FF0000;
}
div.warning
{
  position:absolute;
  bottom:38%;
  left:4%;
  border-top:1px solid #FFFF00;
  border-bottom:6px solid #FFFF00;
  display:block;
  width:90%;
  background-color:#000000;
  color:#FFFF00;
  padding:5px;
  text-align:left;
  display:none;
}
div.warning::after
{
  content:"";
  position:absolute;
  bottom:-48%;
  left:5%;
  border-left:10px solid transparent;
  border-right:10px solid transparent;
  border-top:10px solid #FFFF00;
}
*:focus
{
  outline:none;
}
a,input[type="button"],input[type="reset"],input[type="submit"]
{
  user-select:none;
}
</style>
<script>
function change()
{
  var i=parseInt(document.getElementById("current").value);
  if(i>=10)
  {
    i=1;
  }
  else
  {
    i++;
  }
  document.getElementById("avatar").style.backgroundImage="url('logprofileicon/"+i+".svg')";
  document.getElementById("current").value=i;
}
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
  unsetError();//remove Warning
  var legend=document.getElementById("LE");
  var error=document.getElementById("errorMessage");
  var b=document.getElementById("FE");
  error.style.display="inline-block";
  msg="&#x26A0;&nbsp;"+msg;
  error.innerHTML=msg;
  b.style.borderColor="#FF0000";
  legend.style.color="#FF0000";
}
function setWarning(msg)
{
  unsetError();//remove error
  var legend=document.getElementById("LE");
  var warning=document.getElementById("warningMessage");
  var b=document.getElementById("FE");
  warning.style.display="inline-block";
  msg="&#x26A0;&nbsp;"+msg;
  warning.innerHTML=msg;
  b.style.borderColor="#FFFF00";
  legend.style.color="#FFFF00";
}
function unsetError()
{
  var legend=document.getElementById("LE");
  var error=document.getElementById("errorMessage");
  var warning=document.getElementById("warningMessage");
  var b=document.getElementById("FE");
  b.style.borderColor="#00FF00";
  legend.style.color="#FFFFFF";
  error.style.display="none";
  warning.style.display="none";
}
function check()
{
  var email=document.getElementById("E").value;
  var length=email.length;
  var c='';
  if(email=="")
  {
    setError("Enter E-Mail ID !");
    return false;
  }
  if(email.charAt(0)=='@')
  {
    setError("Enter first Part of E-Mail ID !");
    return false;
  }
  if(email.charAt(length-1)=='@')
  {
    setError("Enter last Part of E-Mail ID !");
    return false;
  }
  if( /[A-Z]/.test(email) )
  {
    setWarning("Uppercase letters are not used in E-Mail");
    return false;
  }
  if( /[^a-zA-Z0-9@.]/.test(email) )
  {
    setError("Invalid Character Found");
    return false;
  }
  var count=0;
  for(let i=0;i<length;i++)
  {
    c=email.charAt(i);
    if(c=='@')
    {
      count++;
    }
  }
  if(count>1)
  {
    setError("Multiple @ character found !");
    return false;
  }
  for(let i=1;i<length-1;i++)
  {
    c=email.charAt(i);
    if(c=='@')
    {
      unsetError();
      return true;
    }
  }
  setError("Invalid E-Mail ID !");
  return false;
}
function r()
{
  var error=document.getElementById("errorMessage");
  var b=document.getElementById("FE");
  b.style.borderColor="#00FFFF";
  document.getElementById("E").value="";
  error.style.display="none";
  unsetError();
}
</script>
</head>
<body>
<div class="blurbg"></div>
<div class="circle"></div>
<input type="hidden" id="current" value="1">
<div class="bg bg1"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<div class="form">
<div class="Segment">
<div class="topcircle">
  <div class="avatar" id="avatar"></div>
</div>
<div class="warning" id="warningMessage">Warning Message !</div>
<p class="title">Log In</p>
<form method="POST" action="authenticate.php">
<div class="PasswordContainer">
<div class="error" id="errorMessage">Error Message !</div>
<fieldset id="FE">
  <legend id="LE"> E - Mail </legend>
  <input type="email" name="EMAIL" oninput="check();" placeholder="Enter User ID" id="E" onfocus="x(this);" onblur="y(this)" spellcheck="false" autocomplete="off">
  <div class="eicon"></div>
</fieldset>
</div>
<p class="noaccount"><a href="../NewAccount/index.php">Don't have an Account ? &nbsp; Create One.</a></p>
<div class="buttonset">
<a href="../Home"><input class="cancel" type="button" value="Cancel"></a>
<a href="../Home"><input class="home" type="button" value="Home"></a>
<input class="reset" type="reset" value="Reset" onclick="return r();">
<input class="next" type="submit" value="Next" onclick="return check();">
</div>
</form>
</div>
</div>
<script>
<?php
if(isset($_SESSION['error']))
{
  if($_SESSION['error']=="1")
  {
    echo "setError(\"E-Mail ID not Registered !\");\n";
  }
  else if($_SESSION['error']=="2")
  {
    echo "setError(\"E-Mail not Entered, Redirected !\");\n";
  }
  else if($_SESSION['error']=="3")
  {
    echo "setWarning(\"E-Mail Verified !\");\n";
  }
  $_SESSION['error']=0;
}
?>
setInterval(change,1500);
</script>
</body>
</html>
