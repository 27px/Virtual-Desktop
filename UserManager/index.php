<?php
  ob_start();
  session_start();
  if(!(isset($_SESSION['Logged'])))
  {
    header("Location:../Login/Login.php");
  }
  if(!(isset($_SESSION['User'])) || $_SESSION['User']!="Manager")
  {
    die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">You are not Authorised to access this Page.</div>");
  }
  require_once("../config/database.php");
  $conn=new mysqli($servername,$username,$password);
  if(mysqli_connect_error())
  {
    die("Connection Error");
  }
  if(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE 'cloud'"))))
  {
    die("Database not Found");
  }
  if(!($conn->query("USE cloud")==true))
  {
    die("Couldn't change Database");
  }
  if(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE 'Apps'"))))
  {
    die("Table not Found");
  }
  function showUser($x)
  {
    $p="style=\"background-image:url('../User/Profile/".$x[1].".".$x[4]."')\"";
    ?>
      <div class="usercontainer">
        <div class="firstname"><?php echo $x[6]; ?></div><div class="lastname"><?php echo $x[7]; ?></div>
        <div class="profilepic" <?php echo $p; ?>></div>
        <div class="status">Login Status : <?php echo $x[5]; ?></div>
        <div class="email"><?php echo $x[1]; ?></div>
        <?php
          if($x[1]=="administrator@gmail.com")
          {
            ?>
              <button class="ubutton buttonadmin" >Admin</button>
            <?php
          }
          else if($x[5]=="Allowed")
          {
            ?>
              <button class="ubutton buttonblockuser" <?php echo "onclick=\"window.location='".$_SERVER['PHP_SELF']."?";
              if(isset($_GET['option']))
              {
                echo "option=".$_GET['option']."&";
              }
              echo "blockuser=".$x[0]."';\""; ?>>Block</button>
            <?php
          }
          else
          {
            ?>
              <button class="ubutton buttonallowuser" <?php echo "onclick=\"window.location='".$_SERVER['PHP_SELF']."?";
              if(isset($_GET['option']))
              {
                echo "option=".$_GET['option']."&";
              }
              echo "allowuser=".$x[0]."';\""; ?>>Allow</button>
            <?php
          }
        ?>
      </div>
    <?php
  }
  $fn=0;
  function showFeedback($x)
  {
    global $conn;
    global $fn;
    $fn++;
    $p="";
    $result=$conn->query("SELECT * FROM Login WHERE EMail='".$x[2]."' LIMIT 1;");
    $n=mysqli_num_rows($result);
    if($n>0)
    {
      $row=mysqli_fetch_row($result);
      $p="style=\"background-image:url('../User/Profile/".$x[2].".".$row[4]."')\"";
    }
    else
    {
      $p="style=\"background-image:url('../User/Profile/nouser.jpg')\"";
    }
    $read="";
    if($x[4]=="false")
    {
      $read="<div class='read' onclick=\"window.location='".$_SERVER['PHP_SELF']."?option=".$_GET['option']."&markasread=".$x[0]."';\">&#10004;</div>";
    }
    ?>
    <div class="feedbackcontainer">
        <div class="person" onclick="toggleMessage(<?php echo $fn; ?>);" <?php echo $p; ?>></div>
        <div class="feedback" id="feedback_<?php echo $fn; ?>">
          <div class="name"><?php echo htmlspecialchars($x[1]); ?></div>
          <div class="mail"><?php echo htmlspecialchars($x[2]); ?></div>
          <div class="message"><?php echo htmlspecialchars($x[3]); ?></div>
          <?php
            echo "<div class='delete' onclick=\"window.location='".$_SERVER['PHP_SELF']."?option=".$_GET['option']."&delete=".$x[0]."';\">&#10006;</div>";
            echo $read;
          ?>
        </div>
    </div>
    <?php
  }
?>
<html>
<head>
<title>User Manager</title>
<style>
*
{
  padding:0;
  margin:0;
}
body
{
  background:url("1.jpg"),radial-gradient(#A0A0A0,#505050);
  background-size:cover;
  user-select:none;
  color:#FFFFFF;
  overflow:hidden;
}
div.bmainc
{
  width:100vw;
  height:100vh;
display:flex;
flex-direction:row;
}
div.sidemenu
{
  background-color:rgba(255,255,255,0.1);
  width:300px;
  height:100%;
  display:inline-block;
  border-right:2px solid #000000;
  box-shadow:0px 0px 10px 5px #000000;
  position:relative;
}
div.container
{
  width:calc(100% - 300px);
  height:100%;
  display:inline-block;
  overflow-x:hidden;
  overflow-y:auto;
}
div.container::-webkit-scrollbar
{
  width:10px;
  height:10px;
  background-color:rgba(255,255,255,0.5);
}
div.container::-webkit-scrollbar-thumb
{
  background-color:rgba(255,255,255,0.5);
}
div.container::-webkit-scrollbar-thumb:hover
{
  background-color:rgba(255,255,255,1);
}
div.sidemenu div.menuTitle
{
  color:#FFFFFF;
  width:100%;
  text-align:center;
  padding:10px;
  box-sizing:border-box;
  background-color:rgba(255,255,255,0.3);
  font-size:30px;
  font-weight:900;
  font-family:arial black,arial,sans-serif,serif;
  border-bottom:1px solid #000000;
  box-shadow:0px 0px 10px 5px #000000;
  transition:color 1s,background-color 1s;
}
div.sidemenu div.menuTitle:hover
{
  background-color:#FFFFFF;
  color:#000000;
}
div.sidemenu div.menuitem
{
  color:#FFFFFF;
  width:100%;
  text-align:center;
  padding:10px;
  padding-left:20px;
  box-sizing:border-box;
  background-color:rgba(255,255,255,0.1);
  font-size:22px;
  font-weight:400;
  letter-spacing:1px;
  font-family:serif;
  text-align:left;
  padding-left:15px;
  height:45px;
  border-bottom:1px solid #FFFFFF;
  transition:border-left 0.5s,padding-left 0.5s;
}
div.sidemenu div.menuitem:hover
{
  background-color:rgba(0,0,0,0.2);
  border-left:10px solid #FFFFFF;
  padding-left:5px;
}
div.cube
{
  width:100px;
  height:100px;
  position:relative;
  top:30%;
  left:50%;
  transform-style:preserve-3d;
  transform-origin:center;
  transform:translate(-50%,-50%) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
  animation:roll 5s linear infinite normal;
}
div.face
{
  width:100px;
  height:100px;
  outline:1px solid #000000;
  position:absolute;
  transform-style:preserve-3d;
}
div.front
{
  background-color:hsla(0deg,100%,50%,0.5);
  top:0;
  left:0;
}
div.back
{
  background-color:hsla(300deg,100%,50%,0.5);
  top:0;
  left:0;
  transform:translateZ(-100px);
}
div.left
{
  background-color:hsla(60deg,100%,50%,0.5);
  top:0;
  left:0;
  transform-origin:left;
  transform:rotateY(90deg);
}
div.right
{
  background-color:hsla(120deg,100%,50%,0.5);
  top:0;
  right:0;
  transform-origin:right;
  transform:rotateY(-90deg);
}
div.top
{
  background-color:hsla(180deg,100%,50%,0.5);
  top:0;
  right:0;
  transform-origin:top;
  transform:rotateX(-90deg);
}
div.bottom
{
  background-color:hsla(240deg,100%,50%,0.5);
  bottom:0;
  left:0;
  transform-origin:bottom;
  transform:rotateX(90deg);
}
@keyframes roll
{
  0%
  {
    transform:translate(-50%,-50%) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
  }
  100%
  {
    transform:translate(-50%,-50%) rotateX(360deg) rotateY(360deg) rotateZ(360deg);
  }
}
div.dashboard
{
  width:100%;
  height:50%;
  position:relative;
  bottom:1px;
  right:0;
  min-width:400px;
  transform:translate(0,65%);
  display:inline-block;
  text-align:center;
  color:#000000;
}
div.dashboard div.noofusers,div.dashboard div.noofapps,div.dashboard div.noofotherapps
{
  width:250px;
  height:250px;
  background-color:#FFFFFFA0;
  margin-left:40px;
  margin-right:50px;
  margin-top:50px;
  display:inline-block;
  box-shadow:0px 0px 10px 5px #000000,0px 0px 15px 10px #000000;
  border-radius:15px;
}
div.dashboard div.noofusers div.icon,div.dashboard div.noofapps div.icon,div.dashboard div.noofotherapps div.icon
{
  width:180px;
  height:180px;
  display:inline-block;
  background-position:center;
  background-size:contain;
  background-repeat:no-repeat;
  margin-top:10px;
}
div.dashboard div.noofusers div.icon
{
  background-image:url("user.svg");
}
div.dashboard div.noofapps div.icon
{
  background-image:url("app.svg");
}
div.dashboard div.noofusers div.title,div.dashboard div.noofapps div.title,div.dashboard div.noofotherapps div.title
{
  font-size:24px;
  font-weight:900;
  font-family:arial,sans-serif,serif;
  line-height:23px;
}
div.dashboard div.noofotherapps div.icon
{
  background-image:url("otherapps.svg");
}
div.dashboard div.d1:hover
{
  background-color:#FFFFFF;
}
div.searchcontainer
{
  width:600px;
  height:50px;
  position:relative;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
}
div.searchcontainer input
{
  width:calc(100% - 45px);
  padding:5px 20px;
  border-radius:20px;
  font-size:20px;
  background-color:rgba(255,255,255,0.65);
  font-weight:900;
  border:2px solid #00C0FF;
  letter-spacing:1.5px;
  vertical-align:middle;
}
div.searchcontainer input::selection
{
  color:#FFFFFF;
  background-color:#000000A0;
}
div.searchcontainer input:focus
{
  outline:none;
}
div.searchcontainer div.searchbutton
{
  width:42px;
  height:42px;
  background-image:url("search.svg");
  background-position:center;
  background-size:60%;
  background-repeat:no-repeat;
  position:relative;
  top:0;
  left:0;
  display:inline-block;
  transform:translateX(-110%);
  vertical-align:middle;
}
div.resultError
{
  position:relative;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  width:60%;
  min-width:350px;
  padding:50px;
  font-size:30px;
  font-family:arial,sans-serif,serif;
  font-weight:600;
  text-align:center;
  color:#FF0000;
  background-color:#000000;
  border-top:2px solid #FF0000;
  border-bottom:2px solid #FF0000;
  border-left:5px solid #FF0000;
  border-right:5px solid #FF0000;
  border-radius:20px;
  transition:color 1s,border-color 1s;
}
div.resultError::after
{
  content:"";
  position:absolute;
  bottom:-40;
  left:15;
  width:0;
  height:0;
  border-top:20px solid #FF0000;
  border-left:20px solid transparent;
  border-bottom:20px solid transparent;
  border-right:20px solid transparent;
  transition:border-top-color 1s;
}
div.resultError:hover
{
  border-color:#FFFFFF;
  color:#FFFFFF;
}
div.resultError:hover::after
{
  border-top-color:#FFFFFF;
}
div.feedbackcontainer
{
  position:relative;
  top:40px;
  left:50%;
  transform:translate(-50%,0%);
  width:80%;
  min-width:350px;
  font-size:30px;
  margin-top:20px;
  margin-bottom:20px;
}
div.feedback
{
  position:relative;
  color:#FFFFFF;
  background-color:#000000;
  border-top:2px solid #FFFFFF;
  border-bottom:2px solid #FFFFFF;
  border-left:5px solid #FFFFFF;
  border-right:5px solid #FFFFFF;
  border-radius:20px;
  margin-bottom:40px;
  margin-top:20px;
  transition:color 1s,border-color 1s;
  display:block;
}
div.feedback::after
{
  content:"";
  position:absolute;
  top:-40;
  left:51px;
  width:0;
  height:0;
  border-top:20px solid transparent;
  border-left:20px solid transparent;
  border-bottom:20px solid #FFFFFF;
  border-right:20px solid transparent;
  transition:border-bottom-color 1s;
}
div.feedback:hover
{
  border-color:#00FF00;
  color:#00FF00;
}
div.feedback:hover::after
{
  border-bottom-color:#00FF00;
}
div.feedback div.name
{
  position:absolute;
  top:10px;
  left:10px;
  padding:10px;
  text-transform:uppercase;
  font-size:16px;
}
div.feedback div.mail
{
  position:absolute;
  top:10px;
  right:10px;
  padding:10px;
  text-transform:lowercase;
  font-size:20px;
}
div.feedback div.message
{
  margin-top:50px;
  font-size:22px;
  font-family:arial,sans-serif;
  text-align:justify;
  padding:20px;
  margin-bottom:30px;
  line-height:35px;
}
div.feedbackcontainer div.person
{
  width:150px;
  height:150px;
  border-radius:50%;
  background-size:contain;
  background-position:center;
  background-repeat:no-repeat;
  background-color:#C0C0C0;
  border:2px solid #FFFFFF;
  display:inline-block;
}
div.feedbackcontainer div.feedback div.delete
{
  float:right;
  color:#FF0000;
}
div.feedbackcontainer div.feedback div.read
{
  float:right;
  color:#00FF00;
  margin-right:20px;
}
div.usercontainer
{
  position:relative;
  width:35%;
  min-width:300px;
  margin-left:60px;
  margin-top:50px;
  margin-bottom:50px;
  margin-right:80px;
  display:inline-block;
  background-color:#A0A0A0C0;
  color:#000000;
  box-sizing:border-box;
  padding:20px;
  box-shadow:0px 0px 10px 5px #000000;
}
div.usercontainer:hover
{
  background-color:#D0D0D0;
}
div.usercontainer div.profilepic
{
  width:150px;
  height:150px;
  border:2px solid #000000;
  background-position:center;
  background-size:contain;
  background-repeat:no-repeat;
  border-radius:50%;
  background-color:#C0C0C0;
  box-shadow:0px 0px 10px 2.5px #000000;
  position:absolute;
  top:50%;
  right:0;
  transform:translate(50%,-50%);
}
div.usercontainer div.firstname,div.usercontainer div.lastname
{
  display:inline-block;
  padding:2.5px;
  font-weight:900;
  font-family:arial black,arial,sans-serif,serif;
}
div.usercontainer button.ubutton
{
  font-weight:900;
  padding:5px 10px;
  position:absolute;
  bottom:-55px;
  left:20px;
  min-width:150px;
  font-size:18px;
  box-shadow:0px 0px 10px 2.5px #000000;
}
div.usercontainer button.ubutton:focus
{
  outline:none;
}
div.usercontainer button.buttonblockuser
{
  background-color:#000000;
  color:#FF0000;
  border:1px solid #FF0000;
}
div.usercontainer button.buttonblockuser::before
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  top:calc(-50% + 5px);
  left:15px;
  transform:translate(0,0%);
  border-top:0px solid transparent;
  border-left:12px solid transparent;
  border-right:12px solid transparent;
  border-bottom:12px solid #FF0000;
}
div.usercontainer button.buttonallowuser
{
  background-color:#000000;
  color:#00FF00;
  border:1px solid #00FF00;
}
div.usercontainer button.buttonallowuser::before
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  top:calc(-50% + 5px);
  left:15px;
  transform:translate(0,0%);
  border-top:0px solid transparent;
  border-left:12px solid transparent;
  border-right:12px solid transparent;
  border-bottom:12px solid #00FF00;
}
div.usercontainer button.buttonadmin
{
  background-color:#000000;
  color:#FFFF00;
  border:1px solid #FFFF00;
}
div.usercontainer button.buttonadmin::before
{
  content:"";
  width:0;
  height:0;
  position:absolute;
  top:calc(-50% + 5px);
  left:15px;
  transform:translate(0,0%);
  border-top:0px solid transparent;
  border-left:12px solid transparent;
  border-right:12px solid transparent;
  border-bottom:12px solid #FFFF00;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function toggleMessage(x)
{
  a=_("feedback_"+x)
  if(a.style.display=="none")
  {
    a.style.display="block";
  }
  else
  {
    a.style.display="none";
  }
}
</script>
</head>
<body>
  <div class="bmainc">
  <div class="sidemenu">
    <div class="menutitle">Options</div>
    <div class="menuitem" onclick="window.location='../Home/index.php';">Home</div>
    <div class="menuitem" onclick="window.location='../Desktop/index.php';">Desktop</div>
    <div class="menutitle">Manage Users</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?Dashboard=true"; ?>';">Dashboard</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?option=SearchUser"; ?>';">Search User</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?option=AllUsers"; ?>';">All Users</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?option=BlockedUser"; ?>';">Blocked User</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?option=UnverifiedUser"; ?>';">Unverified Users</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?option=Feedbacks"; ?>';">Feedbacks</div>
    <div class="menuitem" onclick="window.location='<?php echo $_SERVER['PHP_SELF']."?option=ViewedFeedbacks"; ?>';">Viewed Feedbacks</div>
  </div>
  <div class="container">
    <?php
      if(isset($_GET['blockuser']) && !empty($_GET['blockuser']))
      {
        $sql="UPDATE `Login` SET `Block`='PBlock' WHERE ID='".$_GET['blockuser']."'";
        $conn->query($sql);
      }
      if(isset($_GET['allowuser']) && !empty($_GET['allowuser']))
      {
        $sql="UPDATE `Login` SET `Block`='Allowed' WHERE ID='".$_GET['allowuser']."'";
        $conn->query($sql);
      }
      if(isset($_GET['delete']) && !empty(($_GET['delete'])))
      {
        $sql="DELETE FROM `Feedback` WHERE ID='".$_GET['delete']."'";
        $conn->query($sql);
      }
      if(isset($_GET['markasread']) && !empty(($_GET['markasread'])))
      {
        $sql="UPDATE `Feedback` SET `Status`='true' WHERE ID='".$_GET['markasread']."'";
        $conn->query($sql);
      }
      if(isset($_POST['search']) && !empty($_POST['search']))
      {
        $result=$conn->query("SELECT * FROM Login WHERE EMail like '%".$_POST['search']."%' ");
        $n=mysqli_num_rows($result);
        if($n<=0)
        {
          echo "<div class='resultError'>No Users Found for Search ".$_POST['search']."</div>";
        }
        else
        {
          $i=0;
          while($row=mysqli_fetch_row($result))
          {
            showUser($row);
          }
        }
      }
      else if(isset($_GET['option']))
      {
        if($_GET['option']=="SearchUser")
        {
          ?>
            <form id="search" name="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          </form>
              <div class="searchcontainer"><input type="text" name="search" placeholder="Search User" form="search" id="s" spellcheck="false" autocomplete="off"><div class="searchbutton" onclick="if(_('s').value!=''){_('search').submit();}"></div></div>
          <?php
        }
        else if($_GET['option']=="AllUsers")
        {
          $result=$conn->query("SELECT * FROM Login");
          $n=mysqli_num_rows($result);
          if($n<=0)
          {
            echo "<div class='resultError'>No Users Registered</div>";
          }
          else
          {
            $i=0;
            while($row=mysqli_fetch_row($result))
            {
              showUser($row);
            }
          }
        }
        else if($_GET['option']=="BlockedUser")
        {
          $result=$conn->query("SELECT * FROM Login where Block='PBlock' ");
          $n=mysqli_num_rows($result);
          if($n<=0)
          {
            echo "<div class='resultError'>No Users Blocked</div>";
          }
          else
          {
            $i=0;
            while($row=mysqli_fetch_row($result))
            {
              showUser($row);
            }
          }
        }
        else if($_GET['option']=="UnverifiedUser")
        {
          $result=$conn->query("SELECT * FROM Login where Block='verify' ");
          $n=mysqli_num_rows($result);
          if($n<=0)
          {
            echo "<div class='resultError'>All Users are Verified.</div>";
          }
          else
          {
            $i=0;
            while($row=mysqli_fetch_row($result))
            {
              showUser($row);
            }
          }
        }
        else if($_GET['option']=="Feedbacks")
        {
          $result=$conn->query("SELECT * FROM Feedback where Status='false' ");
          $n=mysqli_num_rows($result);
          if($n<=0)
          {
            echo "<div class='resultError'>No Feedbacks</div>";
          }
          else
          {
            $i=0;
            while($row=mysqli_fetch_row($result))
            {
              showFeedback($row);
            }
          }
        }
        else if($_GET['option']=="ViewedFeedbacks")
        {
          $result=$conn->query("SELECT * FROM Feedback where Status='true' ");
          $n=mysqli_num_rows($result);
          if($n<=0)
          {
            echo "<div class='resultError'>No Viewed Feedbacks</div>";
          }
          else
          {
            $i=0;
            while($row=mysqli_fetch_row($result))
            {
              showFeedback($row);
            }
          }
        }
      }
      else//Dashboard
      {
        $result=$conn->query("SELECT count(ID) FROM `Login`");
        $x=mysqli_fetch_row($result);
        $user=$x[0];

        $result=$conn->query("SELECT count(ID) FROM `Apps` where `Status`='Approved'");
        $x=mysqli_fetch_row($result);
        $app=$x[0];

        $result=$conn->query("SELECT count(ID) FROM `Apps`");
        $x=mysqli_fetch_row($result);
        $uapp=$x[0];

        ?>
        <div class="cube" id="cube">
          <div class="face back"></div>
          <div class="face top"></div>
          <div class="face bottom"></div>
          <div class="face left"></div>
          <div class="face right"></div>
          <div class="face front"></div>
        </div>

        <div class="dashboard">
          <div class="d1 noofusers">
            <div class="icon"></div>
            <div class="title"><?php echo $user; ?><br>Registered Users</div>
          </div>
          <div class="d1 noofapps">
            <div class="icon"></div>
            <div class="title"><?php echo $app; ?><br>Approved Apps</div>
          </div>
          <div class="d1 noofotherapps">
            <div class="icon"></div>
            <div class="title"><?php echo $uapp; ?><br>Total Apps</div>
          </div>
        </div>
        <?php
      }
    ?>
  </div>
</div>
</body>
</html>
