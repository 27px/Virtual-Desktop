<?php
ob_start();
session_start();
if((isset($_POST['LogOut'] && $_POST['LogOut']=="true") || (isset($_GET['logout']) && $_GET['logout']=="true"))
{
  require_once('../Includes/logout.php');
  header('Location:index.php');
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
  background-image:url("Images/12.jpg");
  background-size:cover;
  background-position:center;
  background-attachment:fixed;
  text-align:center;
  user-select:none;
}
body::-webkit-scrollbar
{
  width:10px;
  height:10px;
  background-color:#b2c022;
}
body::-webkit-scrollbar-thumb
{
  background-color:#673ab7;
}
body::-webkit-scrollbar-thumb:hover
{
  background-color:#9673d3;
  border-radius:20px;
}
div.popupbg
{
  width:100vw;
  height:100vh;
  position:fixed;
  top:0;
  left:0;
  background-color:rgba(0,0,0,0.8);
  z-index:999;
}
div.popup
{
  position:fixed;
  top:50%;
  left:50%;
  width:90vmin;
  height:35vmin;
  min-height:200px;
  min-width:500px;
  transform:translate(-50%,-50%);
  vertical-align:middle;
  box-sizing:border-box;
  background-color:#008080;
  z-index:1000;
  color:#FFFFFF;
  font-size:30px;
  line-height:35vmin;
  font-family:arial black,arial,sans-serif,serif;
  text-align:center;
  font-weight:900;
  outline:1px solid #00FFFF;
  outline-offset:1px;
  text-shadow:2px 2px 5px #000000;
  box-shadow:0px 0px 6px #000000 inset;
}
div.popup div.close
{
  position:absolute;
  right:10px;
  top:0;
  color:#FF0000;
  line-height:normal;
  -webkit-text-stroke:2px #800000;
}
div.cc
{
  width:100%;
  padding-bottom:6vw;
}
div.c
{
  display:inline-block;
  width:40vw;
  height:50vh;
  margin:3vw;
  background-size:100%;
  background-attachment:fixed;
  min-width:400px;
  min-height:300px;
  box-shadow:0px 0px 30px 10px #000000,0px 0px 30px 10px transparent inset;
  user-select:none;
  outline:6px double transparent;
  outline-offset:-20px;
  position:relative;
  transition:1s background-size,1s outline,1s box-shadow;
  border:1px solid #000000;
}
div.c:hover
{
  outline:6px double #FFFFFF;
  background-size:120%;
  box-shadow:0px 0px 30px 10px transparent,0px 0px 30px 10px #000000 inset;
}
div.c div.title
{
  position:absolute;
  top:50%;
  left:50%;
  transform:translateX(-50%) translateY(-50%);
  text-align:center;
  width:100%;
  transition:transform 0.5s 0.5s,top 0.5s 0.5s;
  font-size:50px;
  font-family:arial black,arial,sans-serif,serif;
  color:#FFFFFF;
  text-align:center;
  font-weight:900;
  text-shadow:2px 2px 5px #000000,2px -2px 5px #000000,-2px 2px 5px #000000,-2px -2px 5px #000000;
  display:inline-block;
}
div.c div.contents
{
  position:absolute;
  bottom:0px;
  left:50%;
  transform:translate(-50%,-55px);
  text-shadow:2px 2px 5px rgba(0,0,0,0),2px -2px 5px rgba(0,0,0,0),-2px 2px 5px rgba(0,0,0,0),-2px -2px 5px rgba(0,0,0,0);
  font-family:arial black,arial,sans-serif,serif;
  font-family:arial,sans-serif,serif;
  font-size:30px;
  color:#FFFFFF;
  width:100%;
  text-align:center;
  letter-spacing:2px;
  opacity:0;
  color:rgba(255,255,255,0);
  transition:opacity 0.6s linear 0.5s,color 1s,text-shadow 1s;
}
div.c:hover div.title
{
  top:0%;
  transform:translateX(-50%) translateY(50%);
  text-decoration:underline;
}
div.c:hover div.contents
{
  opacity:1;
  color:rgba(255,255,255,1);
  text-shadow:2px 2px 5px rgba(0,0,0,1),2px -2px 5px rgba(0,0,0,1),-2px 2px 5px rgba(0,0,0,1),-2px -2px 5px rgba(0,0,0,1);
}
div.c1
{
  background-image:url("Images/1.jpg");
}
div.c2
{
  background-image:url("Images/2.png");
}
div.c3
{
  background-image:url("Images/1 (4).jpg");
}
div.c4
{
  background-image:url("Images/1 (20).jpg");
}
div.c5
{
  background-image:url("Images/1 (16).jpg");
}
div.c6
{
  background-image:url("Images/6.jpg");
}
div.c7
{
  background-image:url("Images/1 (7).jpg");
}
div.c8
{
  background-image:url("Images/1 (10).jpg");
}
div.header
{
  height:calc(100vh + 11px);
  width:100%;
  background:#000000 url("Images/11.jpg");
  background-attachment:fixed;
  background-size:cover;
  background-position:center;
}
div.header div.top
{
  width:100%;
  height:100px;
  position:sticky;
  top:150px;
  left:0;
  background:rgba(0,0,0,0.5) url("Images/14.jpg");
  background-size:cover;
  background-attachment:fixed;
  z-index:10;
  border-bottom:1px solid transparent;
  box-shadow:0px 0px 10px 5px #000000;
  transition:height 1s,background-blend-mode 1s,border-bottom-color 1s;
}
div.header div.bottom
{
  width:100%;
  height:10px;
  position:sticky;
  top:100vw;
  bottom:0;
  left:0;
  background-color:#4caf50;
  z-index:2;
  border-bottom:1px solid #000000;
}
div.header div.top div.title
{
  color:#FFFFFF;
  font-size:40px;
  font-family:arial black,arial,sans-serif,serif;
  text-align:center;
  font-weight:900;
  border:1px solid #FFFFFF;
  display:inline-block;
  padding:2.5px 15px;
  margin:17.5px 25px;
  user-select:none;
  transition:0.5s padding,0.5s margin,0.5s font-size;
  float:left;
  text-shadow:2px 2px 5px #000000,2px -2px 5px #000000,-2px 2px 5px #000000,-2px -2px 5px #000000;
}
div.header div.top div.title:hover
{
  padding:7.5px 22.5px;
  margin:12.5px 17.5px;
  border-color:#00b8a7;
  box-shadow:0px 0px 10px 5px #000000;
}
div.menuitem
{
  padding:10px 15px;
  margin:20px 5px;
  display:inline-block;
  text-align:center;
  animation:move 0.8s linear 1 normal;
  font-size:25px;
  font-family:arial black,arial,sans-serif,serif;
  text-shadow:1px 1px 1px #000000,1px -1px 1px #000000,-1px 1px 1px #000000,-1px -1px 1px #000000;
  float:right;
  transition:all 0.5s;
}
div.menuitem:hover
{
  box-shadow:0px 0px 10px 5px #000000;
}
div.menuitem:nth-child(2)
{
  color:#FFFFFF;
  border:2px solid #FFFFFF;
}
div.menuitem:nth-child(2):hover
{
  color:#FFFF00;
  border:2px solid #FFFF00;
}
div.menuitem:nth-child(3)
{
  color:#FFFFFF;
  border:2px solid #FFFFFF;
}
div.menuitem:nth-child(3):hover
{
  color:#FF0000;
  border:2px solid #FF0000;
}
div.menuitem:nth-child(4)
{
  color:#FFFFFF;
  border:2px solid #FFFFFF;
}
div.menuitem:nth-child(4):hover
{
  color:#FF00FF;
  border:2px solid #FF00FF;
}
div.menuitem:nth-child(5)
{
  color:#FFFFFF;
  border:2px solid #FFFFFF;
}
div.menuitem:nth-child(5):hover
{
  color:#00FF00;
  border:2px solid #00FF00;
}
div.menuitem:nth-child(6)
{
  color:#FFFFFF;
  border:2px solid #FFFFFF;
}
div.menuitem:nth-child(6):hover
{
  color:#00FFFF;
  border:2px solid #00FFFF;
}
div.in
{
  padding:0;
  margin:0;
  border:none;
  outline:none;
  display:inline-block;
}
div.header div.top div.title:hover div.in
{
  color:#009688;
}
div.header div.top div.title:hover div.in::first-letter
{
  color:#00b8a7;
}
div.cb
{
  color:#FFFFFF;
  font-size:50px;
  position:absolute;
  top:65%;
  left:calc(50% - 0px);
  transform:translate(-50%,-50%);
  border:2px solid #FFFFFF;
  padding:5px 10px;
  user-select:none;
  text-align:center;
  letter-spacing:2px;
  text-shadow:2px 2px 10px #000000,2px -2px 10px #000000,-2px 2px 10px #000000,-2px -2px 10px #000000;
  z-index:0;
  box-shadow:0px 0px 20px 5px #000000;
  background-color:rgba(0,0,0,0.5);
  transition:padding 0.5s;
}
div.cb:hover
{
  padding:10px 15px;
}
div.bottombar
{
  width:100%;
  bottom:0;
  left:0;
  z-index:2;
  border-bottom:1px solid #000000;
}
div.b1
{
  height:100px;
  background-color:rgba(0,0,0,0.8);
  color:#FFFFFF;
  font-size:40px;
  font-family:arial black,arial,sans-serif,serif;
  text-align:center;
  font-weight:900;
  padding-top:15px;
  box-sizing:border-box;
  z-index:2;
}
div.b2
{
  height:10px;
  background-color:#4caf50;
  z-index:2;
}
div.footer
{
  width:100%;
  min-height:350px;
  background-color:#10091c;
  box-shadow:0px 0px 10px 5px #000000;
}
div.footer div.a,div.footer div.b
{
  width:calc(50% - 5px);
  min-height:350px;
  display:inline-block;
  box-sizing:border-box;
  vertical-align: middle;
  color:#FFFFFF;
}
sup
{
  color:#FF0000;
}
div.footer div.b
{
  padding-bottom:30px;
}
div.footer div.input
{
  display:inline-block;
  padding:30px 20px;
  font-size:25px;
}
div.footer div.input input
{
  padding:5px;
  font-family:serif;
  font-size:20px;
  width:190px;
  background-color:rgba(255,255,255,0.1);
  border:1px solid #FFFFFF;
  color:#FFFFFF;
}
div.footer div.input input:focus,textarea.feedback:focus
{
  outline:none;
}
textarea.feedback
{
  width:calc(100% - 60px);
  resize:none;
  padding:5px;
  font-family:serif;
  font-size:20px;
  background-color:rgba(255,255,255,0.1);
  border:1px solid #FFFFFF;
  color:#FFFFFF;
  height:180px;
}
textarea.feedback::placeholder
{
  text-align:center;
  vertical-align:middle;
  padding-top:65px;
  font-size:30px;
}
textarea.feedback::-webkit-scrollbar
{
  width:10px;
  height:10px;
  background-color:rgba(0,0,0,0.5);
}
textarea.feedback::-webkit-scrollbar-thumb
{
  background-color:rgba(255,255,255,0.5);
}
textarea.feedback::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(0,255,255,0.5);
}
div.a
{
  box-sizing:border-box;
  border-right:1px solid rgba(255,255,255,0.7);
}
div.b button
{
  display:block;
  padding:5px 15px;
  width:calc(100% - 60px);
  margin-top:25px;
  margin-left:30px;
  border:none !important;
  outline:1px solid #FFFFFF;
  background-color:rgba(255,255,255,0.7);
  font-family:serif;
  font-size:20px;
}
button.sender
{
  outline:none !important;
  background:repeating-linear-gradient(45deg,
    #606dbc,
    #606dbc 10px,
    #465298 10px,
    #465298 20px);
  animation:send 1s linear infinite normal;
}
@keyframes send
{
  0%
  {
    background:repeating-linear-gradient(45deg,
      #606dbc 0px,
      #606dbc 10px,
      #465298 10px,
      #465298 20px);
  }
  100%
  {
    background:repeating-linear-gradient(45deg,
      #465298 0px,
      #465298 10px,
      #606dbc 10px,
      #606dbc 20px);
  }
}
div.contacttitle
{
  font-size:25px;
  padding-bottom:25px;
  margin-bottom:25px;
  transition:color 0.5s,width 0.5s,border-color 0.5s;
  border-bottom:1px solid #FFFFFF;
  display:inline-block;
  width:150px;
}
div.contacttitle:hover
{
  color:#FFFF00;
  width:90%;
  border-color:#FFFF00;
}
div.contactcontent
{
  padding:30px 0px;
  font-size:20px;
  display:inline-block;
}
div.contactcontent span.a,div.contactcontent span.b
{
  transition:color 1s;
}
div.contactcontent:hover span.a
{
  color:#00FFFF;
}
div.contactcontent:hover span.b
{
  color:#00FF00;
}
div.a a
{
  display:none !important;
}
div.compressed
{
  height:55px !important;
  border-bottom-color:#FFFFFF !important;
}
div.compressed
{
  background-blend-mode:darken !important;
}
div.compressed:hover
{
  background-blend-mode:lighten !important;
}
div.compressed div.title
{
  padding:4px 10px !important;
  margin:5px !important;
  font-size:25px !important;
}
div.compressed div.menuitem
{
  padding:5px 20px !important;
  margin:7px !important;
}
div.compressed div.menuitem
{
  color:#000000 !important;
  font-family:serif !important;
  text-shadow:none !important;
}
div.compressed div.menuitem:nth-child(2)
{
  border:2px solid #808000 !important;
  background:linear-gradient(45deg,#FFFF00,#808000,#FFFF00) !important;
}
div.compressed div.menuitem:nth-child(3)
{
  border:2px solid #800000 !important;
  background:linear-gradient(45deg,#F00000,#800000,#FF0000) !important;
}
div.compressed div.menuitem:nth-child(4)
{
  border:2px solid #800080 !important;
  background:linear-gradient(45deg,#FF00FF,#800080,#FF00FF) !important;
}
div.compressed div.menuitem:nth-child(5)
{
  border:2px solid #008000 !important;
  background:linear-gradient(45deg,#00FF00,#008000,#00FF00) !important;
}
div.compressed div.menuitem:nth-child(6)
{
  border:2px solid #008080 !important;
  background:linear-gradient(45deg,#00FFFF,#008080,#00FFFF) !important;
}
div.compressed div.menuitem:nth-child(2):hover
{
  border:2px solid #808000 !important;
  background:linear-gradient(45deg,#FFFF00,#FFFF00,#FFFF00) !important;
}
div.compressed div.menuitem:nth-child(3):hover
{
  border:2px solid #800000 !important;
  background:linear-gradient(45deg,#F00000,#FF0000,#FF0000) !important;
}
div.compressed div.menuitem:nth-child(4):hover
{
  border:2px solid #800080 !important;
  background:linear-gradient(45deg,#FF00FF,#FF00FF,#FF00FF) !important;
}
div.compressed div.menuitem:nth-child(5):hover
{
  border:2px solid #008000 !important;
  background:linear-gradient(45deg,#00FF00,#00FF00,#00FF00) !important;
}
div.compressed div.menuitem:nth-child(6):hover
{
  border:2px solid #008080 !important;
  background:linear-gradient(45deg,#00FFFF,#00FFFF,#00FFFF) !important;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function gotoURL(code)
{
  var url="";
  if(code=="Log Out")
  {
    var f=document.createElement("FORM");
    f.setAttribute("method","POST");
    f.setAttribute("action","<?php echo $_SERVER['PHP_SELF']; ?>");
    document.body.appendChild(f);
    var x=document.createElement("INPUT");
    x.setAttribute("type","hidden");
    x.setAttribute("name","LogOut");
    x.setAttribute("value","true");
    f.appendChild(x);
    f.submit();
    return;
  }
  else if(code=="Log In")
  {
    url="../LogIn/index.php";
  }
  else if(code=="Sign Up")
  {
    url="../NewAccount/index.php";
  }
  else if(code=="About Us")
  {
    url="../AboutUs/index.php";
  }
  else if(code=="Cloud Storage")
  {
    url="../Desktop/index.php";
  }
  if(url!="")
  {
    window.location=url;
  }
}
function fixMenu()
{
  var x=_("menubar").style;
  if(((this.scrollY)+89)>this.innerHeight)
  {
    x.position="fixed";
    x.top="0px";
    var z=100-((this.scrollY+89)-this.innerHeight);
    if(z>=50)
    {
      _("menubar").classList.add("compressed");
    }
  }
  else
  {
    x.position="sticky";
    x.top="150px";
    _("menubar").classList.remove("compressed");
    x.height=100;
  }
  x.left="0px";
}
function sendMessage(x)
{
  x.classList.add('sender');
  if(_("name").value=="")
  {
    _("name").value=prompt("Enter your Name.");
    if(_("name").value==null || _("name").value=="")
    {
      x.classList.remove('sender');
      return;
    }
  }
  if(_("email").value=="")
  {
    _("email").value=prompt("Enter your Email ID.");
    if(_("email").value==null || _("email").value=="")
    {
      x.classList.remove('sender');
      return;
    }
  }
  if(_("message").value=="")
  {
    _("message").value=prompt("Enter the Message.");
    if(_("message").value==null || _("message").value=="")
    {
      x.classList.remove('sender');
      return;
    }
  }
  _("messageForm").submit();
}
</script>
</head>
<body onscroll="fixMenu();">
  <?php
  if(isset($_POST['message']) && !empty($_POST['message']))
  {
    if(isset($_POST['email']) && !empty($_POST['email']))
    {
      if(isset($_POST['name']) && !empty($_POST['name']))
      {
        $pm="";
        require_once("../config/database.php");
        $table_name="Feedback";
        $useDB="USE $database";
        $selectAllFromTable="SELECT * FROM $table_name";
        $conn=new mysqli($servername, $username, $password);
        if(mysqli_connect_error())
        {
          $p="Database Connection Failed <br> ".mysqli_connect_error();
        }
        if(!(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE '".$database."' ")))))
        {
          if($conn->query($useDB)===TRUE)
          {
            if(!(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE '".$table_name."' ")))))
            {
              if($conn->query("INSERT INTO `".$table_name."`(`Name`,`EMail`,`Message`) VALUES('".$_POST['name']."','".$_POST['email']."','".$_POST['message']."')")==true)
              {
                $pm="Message Sent Successfully !";
              }
              else
              {
                $pm="Message Not Sent !";
              }
            }
            else
            {
              $pm="Error Table Does't exist<br> " . $conn->error;
            }
          }
          else
          {
            $pm="Error Changing Database <br> ".$conn->error;
          }
        }
        echo "<div class=\"popupbg\" id=\"popupbg\"></div><div class='popup' id='popup'><div class='close' onclick=\"_('popup').style.display='none';_('popupbg').style.display='none';\">&#10006;</div>".$pm."</div>";
      }
    }
  }
  ?>
<div class="header">
  <div class="top" id="menubar">
    <div class="title" onclick="gotoURL('Cloud Storage')"><div class="in">Virtual</div> <div class="in">Desktop</div></div>
    <div class="menuitem" onclick="gotoURL(this.innerHTML);">Cloud Storage</div>
    <div class="menuitem" onclick="gotoURL(this.innerHTML);">About Us</div>
    <div class="menuitem" onclick="gotoURL(this.innerHTML);">Log Out</div>
    <div class="menuitem" onclick="gotoURL(this.innerHTML);">Sign Up</div>
    <div class="menuitem" onclick="gotoURL(this.innerHTML);">Log In</div>
  </div>
  <div class="cb" id="slidethrough" onclick="gotoURL('Cloud Storage')">Virtual Desktop</div>
  <div class="bottom"></div>
</div>
<div class="bottombar b1">Features</div>
<div class="bottombar b2"></div>
<div class="cc">
  <div class="c c1">
    <div class="title">Cloud Storage</div>
    <div class="contents">
      Store Files Remotely,<br>
      Secured Storage,<br>
      Encrypted Storage,<br>
      Access From Any PC.
    </div>
  </div>
  <div class="c c2">
    <div class="title">App Store</div>
    <div class="contents">
      Install Apps,<br>
      Update Apps,<br>
      Create Apps,<br>
      Publish Apps.
    </div>
  </div>
  <div class="c c3">
    <div class="title">File Manager</div>
    <div class="contents">
      Cut, Copy, Paste,<br>Rename, Upload, Download<br>Organize, etc.
    </div>
  </div>
  <div class="c c4">
    <div class="title">Desktop</div>
    <div class="contents">
      Widgets,<br>
      App Shortcuts,<br>
      Wallpaper.
    </div>
  </div>
  <div class="c c5">
    <div class="title">Apps</div>
    <div class="contents">
      Create Apps,<br>
      Run Apps,<br>
      Games.
    </div>
  </div>
  <div class="c c6">
    <div class="title">HTML Editor</div>
    <div class="contents">
      Preview,<br>
      Directory View,<br>
      Edit HTML, SVG, XML, JSON,<br>
      PHP, CSS, JS, TXT etc.
    </div>
  </div>
  <div class="c c7">
    <div class="title">File Viewer</div>
    <div class="contents">
      View File Contents,<br>
      Image Viewer,<br>
      PDF Viewer
    </div>
  </div>
  <div class="c c8">
    <div class="title">Storage Analyser</div>
    <div class="contents">
      View Storage Details,<br>
      Memory Usage Details
    </div>
  </div>
</div>
<div class="footer">
  <div class="a">
    <div class="contacttitle">Contact Us</div><br>
    <div class="contactcontent" onclick="_('a1').click();"><span class="a">Cloud Manager : </span><span class="b">(rahulr0047@gmail.com)</span></div><br>
    <div class="contactcontent" onclick="_('a2').click();"><span class="a">Server Admin : </span><span class="b">(ambuambadi@gmail.com)</span></div><br>
    <div class="contactcontent" onclick="_('a3').click();"><span class="a">Cloud Manager : </span><span class="b">(tysonmorais@gmail.com)</span></div><br>
    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=rahulr0047@gmail.com" target="_blank" id="a1"></a>
    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=ambuambadi@gmail.com" target="_blank" id="a2"></a>
    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=tysonmorais@gmail.com" target="_blank" id="a3"></a>
  </div>
  <div class="b">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="messageForm">
      <div class="input">Name<sup>*</sup> : <input type="name" id="name" placeholder="Name" name="name" required/></div>
      <div class="input">E-Mail<sup>*</sup> : <input type="email" id="email" placeholder="E-Mail" name="email"/></div>
      <textarea class="feedback" id="message" name="message" placeholder="Feedback Message ( 500 Characters Max )" autocomplete="off" required maxlength="500"></textarea>
      <button type="button" onclick="sendMessage(this);">Send</button>
    </form>
  </div>
</div>
</body>
</html>
