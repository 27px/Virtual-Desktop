<?php
ob_start();
session_start();
if((isset($_POST['LogOut']) && $_POST['LogOut']=="true") || (isset($_GET['logout']) && $_GET['logout']=="true"))
{
  require_once('../Includes/logout.php');
  header('Location:index.php');
}
?>
<html>
  <head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>fCLOUD</title>
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/1.png">
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/2.png">
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/3.png">
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/4.png">
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/5.png">
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/6.png">
    <link rel="preload" as="image" type="image/png" href="Images/Parallax/7.png">
    <link rel="preload" as="image" type="image/svg+xml" href="Images/Illustrations/analyze.svg">
    <link rel="preload" as="image" type="image/svg+xml" href="Images/Illustrations/app-development.svg">
    <link rel="preload" as="image" type="image/svg+xml" href="Images/Illustrations/app-store.svg">
    <link rel="preload" as="image" type="image/svg+xml" href="Images/Illustrations/cloud-storage.svg">
    <link rel="preload" as="image" type="image/svg+xml" href="Images/Illustrations/desktop.svg">
    <link rel="preload" as="image" type="image/svg+xml" href="Images/Illustrations/storage.svg">
    <link rel="stylesheet" type="text/css" href="style/style.min.css" />
    <script src="script/script.min.js"></script>
  </head>
  <body onscroll="parallax();">
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
              $pm="Database connection failed : ".mysqli_connect_error();
            }
            else if(!(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE '".$database."' ")))))
            {
              if($conn->query($useDB)===TRUE)
              {
                if(!(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE '".$table_name."' ")))))
                {
                  if($conn->query("INSERT INTO `".$table_name."`(`Name`,`EMail`,`Message`) VALUES('".$_POST['name']."','".$_POST['email']."','".$_POST['message']."')")==true)
                  {
                    $pm="Message sent successfully !";
                  }
                  else
                  {
                    $pm="Message not sent, Try Again !";
                  }
                }
                else
                {
                  $pm="Error Table does't exist : ".$conn->error." Please contact Admin.";
                }
              }
              else
              {
                $pm="Error changing Database : ".$conn->error;
              }
            }
            else
            {
              $pm="Error Database does't exist : ".$conn->error." Please contact Admin.";
            }
            echo "<div class=\"popupbg\" id=\"popupbg\"></div><div class='popup' id='popup'><div class=\"messagetitle\">Message</div><div class=\"message\">".$pm."</div><div class='close' onclick=\"_('popup').parentNode.removeChild(_('popup'));_('popupbg').parentNode.removeChild(_('popupbg'));\">Close</div><div class='xclose' onclick=\"_('popup').parentNode.removeChild(_('popup'));_('popupbg').parentNode.removeChild(_('popupbg'));\">&#10006;</div></div>";
          }
        }
      }
    ?>
    <div class="header">
      <div class="navbar" id="menubar">
        <div class="topcontainer">
          <div class="title" onclick="gotoURL('Cloud Storage')">Virtual Desktop</div>
          <div class="menu" onclick="toggleMenu(_('menucontainer').classList);">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
          </div>
        </div>
        <div class="menucontainer collapse" id="menucontainer">
          <div class="menuitem" onclick="gotoURL(this.innerHTML);">About Us</div>
          <?php
            if(isset($_SESSION['Logged']) && !empty($_SESSION['Logged']))
            {
              ?>
                <div class="menuitem" onclick="gotoURL(this.innerHTML);">Log Out</div>
                <div class="menuitem" onclick="gotoURL(this.innerHTML);">Cloud Storage</div>
              <?php
            }
            else
            {
              ?>
                <div class="menuitem" onclick="gotoURL(this.innerHTML);">Log In</div>
                <div class="menuitem" onclick="gotoURL(this.innerHTML);">Sign Up</div>
              <?php
            }
          ?>
        </div>
      </div>
      <div class="parallaxcontainer" id="parallaxcontainer">
        <div class="parallax"></div>
        <div class="parallax"></div>
        <div class="parallax"></div>
        <div class="parallax"></div>
        <div class="parallax"></div>
        <div class="parallax"></div>
      </div>
      <div class="main" id="main">
        <div class="title">Virtual Desktop</div>
        <div class="desc">Port your PC anywhere you go.</div>
        <div class="getstarted" onclick="gotoURL('Cloud Storage')">Get Started</div>
      </div>
      <div class="autoscroll" onclick="autoScroll();"><div class="arrow"></div><div class="title">More</div><div class="arrow"></div></div>
    </div>
    <div class="bottombar">
      <div class="features">
        <div class="item">
          <div class="icon"></div>
          <div class="wrap">
            <div class="title">Virtual Desktop</div>
            <div class="contents">Virtual Desktop allows you to customize & personalize your Desktop. Install Widgets like Clock etc.</div>
          </div>
        </div>
        <div class="item">
          <div class="icon"></div>
          <div class="wrap">
            <div class="title">Cloud Storage</div>
            <div class="contents">Store your files securely in a remote storage. The files will be Encrypted with different key for each user. Access your files from any PC, Mobile Phones Tablets etc.</div>
          </div>
        </div>
        <div class="item">
          <div class="icon"></div>
          <div class="wrap">
            <div class="title">File Manager</div>
            <div class="contents">Manage & Organize files using Interactive Graphical User Interface. Upload Multiple files & folders. Download easily. Cut, Copy, Paste, Rename with GUI. View/Open Files like Images, PDF, Text etc.</div>
          </div>
        </div>
        <div class="item">
          <div class="icon"></div>
          <div class="wrap">
            <div class="title">App Store</div>
            <div class="contents">Install & Run Apps, Create & Publish Apps, Built in Editor for developing web based apps. Search Apps , Search by Category.</div>
          </div>
        </div>
        <div class="item">
          <div class="icon"></div>
          <div class="wrap">
            <div class="title">Code Editor</div>
            <div class="contents">Edit text type files, code with preview of code. Edit files like HTML, CSS, JS, SVG, XML, JSON, TXT etc.</div>
          </div>
        </div>
        <div class="item">
          <div class="icon"></div>
          <div class="wrap">
            <div class="title">Storage Analyser</div>
            <div class="contents">View Storage Details, View how much percent a file/folder consume in your total storage in GUI.</div>
          </div>
        </div>
      </div>
      <div class="footer">
        <div class="details">
          <div class="title">Contact Us</div>
          <div class="links">
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=rahulr0047@gmail.com" target="_blank">
              <div class="contact">
                <span class="a">User Manager : </span>
                <span class="b">rahulr0047@gmail.com</span>
              </div>
            </a>
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=virtualappstore@gmail.com" target="_blank">
              <div class="contact">
                <span class="a">Appstore Manager : </span>
                <span class="b">virtualappstore@gmail.com</span>
              </div>
            </a>
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=twentysevenpixels@gmail.com" target="_blank">
              <div class="contact">
                <span class="a">Admin : </span>
                <span class="b">twentysevenpixels@gmail.com</span>
              </div>
            </a>
          </div>
        </div>
        <div class="feedback">
          <div class="title">Feedback</div>
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="messageForm">
            <div class="row">
              <label for="name">Name <sup>*</sup></label>
              <input type="name" id="name" placeholder="Name" name="name" required/>
            </div>
            <div class="row">
              <label for="email">E-Mail <sup>*</sup></label>
              <input type="email" id="email" placeholder="E-Mail" name="email" required/>
            </div>
            <div class="row">
              <label for="message">Message <sup>*</sup></label>
              <textarea class="feedback" id="message" name="message" placeholder="Feedback Message ( 500 Characters Max )" autocomplete="off" required maxlength="500"></textarea>
            </div>
            <div class="row">
              <button type="button" onclick="sendMessage(this);">Send</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
      if(window.history.replaceState)
      {
        window.history.replaceState(null,null,window.location.href);
      }
    </script>
  </body>
</html>
