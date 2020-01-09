<?php
ob_start();
session_start();
if(!(isset($_SESSION['bstatus'])))
{
  $_SESSION=array();//Clear all SESSION Variables
  session_destroy();
  header("Location:../Login/index.php");
}
?>
<html>
<head>
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
div.bg
{
  width:150%;
  height:100%;
  background-color:rgba(0,0,0,0.15);
  position:fixed;
  top:0;
  left:30%;
  transform:skewX(-30deg);
  z-index:-100;
  user-select:none;
  filter:blur(10px);
}
div.top
{
  width:100%;
  height:80px;
  user-select:none;
  background:repeating-linear-gradient(45deg,rgba(0,0,0,0.1) 0%,rgba(0,0,0,0.05) 2%);
}
div.icon
{
  width:80px;
  height:100%;
  display:inline-block;
  background:url("blocked.png");
  background-size:contain;
  background-repeat:no-repeat;
  user-select:none;
}
div.title
{
  height:100%;
  display:inline-block;
  vertical-align:top;
  color:#FF2828;
  -webkit-text-stroke:1px #000000;
  font-size:55px;
  font-family:arial black,arial,sans-serif,serif;
  text-decoration:underline;
  text-decoration-color:#FF6060;
  user-select:none;
}
div.subtop
{
  width:100%;
  padding:10px;
  box-sizing:border-box;
  background:linear-gradient(0deg,#FF2828,#C00000,#FF2828);
  color:#FFFFFF;
  font-weight:900;
  letter-spacing:1px;
  user-select:none;
}
div.message
{
  padding-top:20px;
  padding-right:20px;
  padding-left:20px;
  box-sizing:border-box;
  font-family:arial,courier new,serif;
  font-weight:600;
  font-size:25px;
  line-height:45px;
  user-select:none;
}
span.usn
{
  color:#800000;
  user-select:all;
}
span.usn:hover
{
  cursor:copy;
}
div.list
{
  padding:20px;
}
div.item
{
  padding:10px;
}
span.listtitle
{
  border-bottom:1px solid #805050;
  font-size:20px;
  color:#800000;
  margin-bottom:10px;
  display:inline-block;
  font-weight:900;
  font-family:courier new;
  user-select:none;
}
div.list>div.item::before
{
  content:"";
  border-left:10px solid #D00000;
  border-top:5px solid transparent;
  border-bottom:5px solid transparent;
  border-right:5px solid transparent;
}
button
{
  margin:5px 30px;
  padding:5px 10px;
  background:linear-gradient(135deg,#00FFFF,#00C0C0,#008080,#00C0C0,#00FFFF);
  transition:background 1s,box-shadow 0.5s,font-size 0.3s;
  font-size:20px;
  color:#000000;
  font-family:courier new,arial,serif;
  font-weight:900;
  border:3px solid #008080;
  box-shadow:0px 0px 20px 5px rgba(0,0,0,0.5);
  vertical-align:top;
  user-select:none;
}
button:hover
{
  background:linear-gradient(135deg,#00FFFF,#00FFFF,#00FFFF,#00FFFF,#00FFFF);
  box-shadow:0px 0px 20px 0px rgba(0,0,0,0);
  font-size:18px;
}
div.bottom
{
  width:100%;
  padding-top:30px;
  padding-left:30px;
  padding-right:30px;
  box-sizing:border-box;
  position:fixed;
  bottom:0;
  left:0;
}
div.bottom div.dhr
{
  height:2px;
  width:100%;
  background-color:#800000;
}
div.bottom div.contact
{
  padding:20px;
}
div.bottom span.title
{
  color:#800000;
  font-family:serif;
  font-weight:900;
  font-size:25px;
  padding-bottom:10px;
  user-select:none;
}
label
{
  color:#800000;
  font-size:20px;
  font-weight:400;
}
span.details,a
{
  color:#000080;
  text-decoration:none;
  cursor:pointer;
  font-size:20px;
  font-weight:400;
}
div.in
{
  display:inline-block;
  color:#800000;
}
a:hover
{
  text-decoration:underline;
}
a:focus,button:focus
{
  outline:none;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
</script>
</head>
<body>
<div class="bg"></div>
<div class="top"><div class="icon"></div><div class="title">Blocked</div></div>
<div class="subtop">Login is Blocked for this Account.</div>
<?php
if(!empty($_SESSION['bstatus']))
{
  if($_SESSION['bstatus']=="Allowed")
  {
    header("Location:../Home/index.php");
  }
  else if(isset($_SESSION['BUser']) && !empty($_SESSION['BUser']))
  {
    if($_SESSION['bstatus']=="TBlock")
    {
      ?>
        <div class="message">Your Account ( <span class="usn"><?php echo $_SESSION["BUser"] ?></span> ) is Temporarily Blocked from Logging in,<br> Please verify E-Mail once to Log in.</div>
        <div class="list">
          <span class="listtitle">Possibilities</span>
          <div class="item">Your E-Mail ID is not verified yet.</div>
          <div class="item">Somebody Else had claimed that this is their E-Mail.</div>
        </div>
        <button type="button" onclick="window.open('verifyMail.php');">Verify E-Mail</button>
      <?php
    }
    else if($_SESSION['bstatus']=="PBlock")
    {
      ?>
        <div class="message">This Account ( <span class="usn"><?php echo $_SESSION['BUser']; ?></span> ) is blocked,<br> Contact Administrator here : <button type="button" onclick="_('mail').click();">Contact</button>
          <br><label>If the above method didn't work try these Alternative methods below : </label>
        </div>
        <div class="list">
          <div class="item in">Contact Method 1 : </div><a href="https://mail.google.com/mail/?view=cm&fs=1&to=rahulr0047@gmail.com" target="_blank">rahulr0047@gmail.com</a><br>
          <div class="item in">Contact Method 2 :</div><a href="mailto:rahulr0047@gmail.com" target="_blank">rahulr0047@gmail.com</a>
        </div>
      <?php
    }
  }
}
?>
<div class="bottom">
<div class="dhr"></div>
<div class="contact">
  <span class="title">Contact Administrator</span><br><br>
  <label>E-Mail : </label><span class="details"><a id="mail" href="https://mail.google.com/mail/?view=cm&fs=1&to=rahulr0047@gmail.com" target="_blank">rahulr0047@gmail.com</a></span>
  <br><br>
  <label>Phone : </label><span class="details">+91 9895951554</span>
</div>
</div>
</body>
