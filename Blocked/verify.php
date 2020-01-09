<?php
ob_start();
session_start();
if(!(isset($_SESSION['bstatus'])))
{
  $_SESSION=array();//Clear all SESSION Variables
  session_destroy();
  header("Location:../Login/index.php");
}
require_once("../config/database.php");
$table_name="Verification";
$useDB="USE $db_name";
$selectAllFromTable="SELECT * FROM $table_name";
$conn=new mysqli($servername, $username, $password);
if(mysqli_connect_error())
{
  echo("<p class='error'>Database Connection Failed : ".mysqli_connect_error()."</p>");
  die();
}
if(isset($_POST['Verify']) && !empty($_POST['Verify']))
{
  if(isset($_POST['email']) && !empty($_POST['email']))
  {
    if(isset($_POST['otp']) && !empty($_POST['otp']))
    {
      if(!(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE '".$db_name."' ")))))
      {
        if($conn->query($useDB)===TRUE)
        {
          if(!(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE '".$table_name."' ")))))
          {
            $sql="SELECT * FROM `".$table_name."` WHERE User='".$_POST['email']."' AND Type='verify' LIMIT 1;";
            $result=$conn->query($sql);
            if(mysqli_num_rows($result)<1)
            {
              echo "<div style='color:#FFC0C0;padding:50px;font-size:30px;text-shadow:0px 0px 10px #000000;'>Error Try Again . . . </div>";
            }
            else
            {
              $row=mysqli_fetch_row($result);
              if($row[2]==$_POST['otp'])
              {
                echo "<div style='color:#C0FFC0;padding:50px;font-size:30px;text-shadow:0px 0px 10px #000000;'>OTP Matched</div>";
                $id=$row[0];
                $sql="UPDATE `login` SET `Block`='Allowed' WHERE `EMail`='".$_POST['email']."';";
                if($conn->query($sql)==TRUE)
                {
                  $sql="DELETE FROM `".$table_name."` WHERE ID='".$id."'";
                  if($conn->query($sql)==TRUE)
                  {
                    $_SESSION['error']="3";
                    header("Location:../LogIn/index.php");
                  }
                  else
                  {
                    echo "<div style='color:#FFC0C0;padding:50px;font-size:30px;text-shadow:0px 0px 10px #000000;'>Error Try Again.</div>";
                  }
                }
                else
                {
                  echo "<div style='color:#FFC0C0;padding:50px;font-size:30px;text-shadow:0px 0px 10px #000000;'>Error Try Again.</div>";
                }
              }
              else
              {
                echo "<div style='color:#FFC0C0;padding:50px;font-size:30px;text-shadow:0px 0px 10px #000000;'>OTP Incorrect</div>";
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
    }
  }
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
  background:linear-gradient(180deg,#8a6a79,#e9dbb6);
  user-select:none;
}
div.tcontainer
{
  display:inline-block;
  text-align:center;
  position:absolute;
  left:50px;
  bottom:50px;
  color:#180f07;
}
div.msgtitle
{
  font-family:arial black,arial,sans-serif,serif;
  font-weight:900;
  font-size:35px;
}
div.msgsubtitle
{
  font-family:arial,sans-serif,serif;
  font-weight:400;
  font-size:20px;
  padding-top:15px;
}
div.container
{
  width:45%;
  height:100%;
  position:absolute;
  top:0;
  right:0;
  background-color:#5d4660;

  box-shadow:0px 0px 10px 5px #000000;
}
div.icon
{
  width:300px;
  height:50px;
  position:relative;
  top:135px;
  left:35%;
  transform:translate(-25%,-50%);
  background-color:#8a6a79;
  box-shadow:0px 0px 5px 1px #e9dbb6;
  border-radius:50px;
  font-family:arial,sans-serif,serif;
  font-weight:900;
  font-size:30px;
  padding:1.5px 0px;
  box-sizing:border-box;
  color:#180f07;
  text-align:center;
}
div.icon::before
{
  content:"";
  width:100px;
  height:100px;
  border:2.5px dotted #FFFFFF;
  border-radius:50%;
  position:absolute;
  top:0%;
  right:0;
  transform:translate(50%,-29%);
  z-index:100;
  background:#5d4660 url("../Desktop/icon/icon_lock.svg");
  background-size:cover;
  background-blend-mode:exclusion;
}
div.subcontainer
{
  width:100%;
  height:60%;
  position:absolute;
  bottom:0;
  right:0;
  background:linear-gradient(90deg,#8a6a79,#e9dbb6);
  text-align:center;
  padding-top:30px;
  box-sizing:border-box;
}
div.subcontainer input[type="text"]
{
  width:70%;
  padding:10px;
  font-size:20px;
  font-family:serif;
  text-align:center;
  background-color:#8a6a7940;
  border:1px solid #00000080;
  margin-top:40px;
}
div.subcontainer input[type="submit"]
{
  width:70%;
  padding:10px;
  font-size:20px;
  font-family:serif;
  text-align:center;
  background-color:#8a6a79FF;
  border:1px solid #00000080;
  margin-top:40px;
}
div.subcontainer input[type="text"]:hover,div.subcontainer input[type="submit"]:hover
{
  cursor:pointer;
}
div.subcontainer input[type="text"]:focus,div.subcontainer input[type="submit"]:focus
{
  outline:none;
  border:1px solid #000000;
}
div.subcontainer input[type="text"]::selection
{
  background-color:#00000050;
  color:#FFFFFFFF;
}
div.subcontainer input[type="text"]::placeholder
{
  color:#00000080;
}
div.subcontainer input[type="submit"]:focus
{
  background:repeating-linear-gradient(45deg,#8a6a79 0px,#8a6a79 5px,#967981 10px,#967981 15px);
  animation:animate 0.35s linear infinite normal;
}
@keyframes animate
{
  0%
  {
    background:repeating-linear-gradient(45deg,#8a6a79 0px,#8a6a79 5px,#967981 10px,#967981 15px);
  }
  100%
  {
    background:repeating-linear-gradient(45deg,#967981 0px,#967981 5px,#8a6a79 10px,#8a6a79 15px);
  }
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
<?php
if(!empty($_SESSION['bstatus']))
{
  if($_SESSION['bstatus']=="Allowed")
  {
    header("Location:../Login/index.php");
  }
  else if(isset($_SESSION['BUser']) && !empty($_SESSION['BUser']))
  {
    if($_SESSION['bstatus']=="verify")
    {
      ?>
        <div class="tcontainer">
          <div class="msgtitle">Verify Account</div>
          <div class="msgsubtitle">Enter OTP from E-Mail</div>
        </div>
        <div class="container">
          <div class="icon">Locked</div>
          <div class="subcontainer">
            <form id="xform" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <input type="text" id="email" name="email" value="<?php echo $_SESSION['BUser']; ?>" readonly>
              <input type="text" id="otp" value="" name="otp" placeholder="OTP" maxlength="6" autocomplete="off" spellcheck="false">
              <input type="submit" name="Verify" value="Verify" onclick="if(_('otp').value==''){_('otp').value=prompt('Error : Enter OTP');}_('xform').submit();">
            </form>
          </div>
        </div>
      <?php
    }
  }
}
?>
</body>
</html>
