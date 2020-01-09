<?php
  function ajaxMessage($type,$message,$a,$x)
  {
    echo "{\"type\":\"".$type."\",\"message\":\"".$message."\",\"a\":\"".$a."\",\"x\":\"".$x."\"}";
  }
  if(isset($_GET['appregname']) && !empty($_GET['appregname']))
  {
    $xsname=htmlspecialchars($_GET['appregname']);
    require_once("../config/connect_db.php");
    if(!isset($_SESSION['Logged']))
    {
      session_start();
    }
    $available=1;
    $result=$conn->query("SELECT * FROM Apps WHERE Name='".$xsname."' LIMIT 1");
    $n=mysqli_num_rows($result);
    if($n>0)
    {
      $available=0;
      $result=$conn->query("SELECT * FROM Apps WHERE Name='".$xsname."' AND User='".$_SESSION['Logged']."' LIMIT 1");
      $n=mysqli_num_rows($result);
      if($n<=0)
      {
        ajaxMessage("error","App Name Exists.","<span style='color:#D00000;'>".$xsname." already Exists</span>","");
        $available=0;
        die();
      }
      else
      {
        $available=2;
      }
    }
    if($available==1 || $available==2)
    {
      $ajaxtype="";
      $ajaxmessage="";
      $ajaxa="";
      $ajaxx="";
      if($available==1)
      {
        if($conn->query("INSERT INTO `Apps`(`Name`,`User`) VALUES('".$xsname."','".$_SESSION['Logged']."')")==true)
        {

          $ajaxtype="success";
          $ajaxmessage="App Name Reserved.";
          $ajaxa=$xsname." Available";
        }
        else
        {
          ajaxMessage("error","App Name Reservation Error.",$xsname." Available","");
          die();
        }
      }
      else if($available==2)
      {
        $ajaxtype="warning";
        $ajaxmessage="Your App is Opened for Editing.";
        $ajaxa="Your App.";
      }
      $description="";
      $keyword1="";
      $keyword2="";
      $keyword3="";
      $category="";
      $width="";
      $height="";
      $minwidth="";
      $minheight="";
      $maxwidth="";
      $maxheight="";
      if($available==2)
      {
        $row=mysqli_fetch_row($result);
        $description=$row[5];
        $keyword1=$row[6];
        $keyword2=$row[7];
        $keyword3=$row[8];
        $category=$row[10];
        $width=$row[11];
        $height=$row[12];
        $minwidth=$row[13];
        $minheight=$row[14];
        $maxwidth=$row[15];
        $maxheight=$row[16];
      }
      $ajaxx="<div class=\\\"popupbg\\\" id=\\\"uploadpopupbg\\\"></div><div class=\\\"popup\\\" id=\\\"uploadpopup\\\"><div class=\\\"topbox\\\"><span class=\\\"title\\\">Build App</span><button type=\\\"button\\\" class=\\\"close closebutton\\\" onclick=\\\"closeUploadPopup();\\\"></button></div><div class=\\\"content\\\" id=\\\"xspopcontent\\\"><form id=\\\"build\\\" class=\\\"build\\\" method=\\\"POST\\\" enctype=\\\"multipart/form-data\\\" action=\\\"\\\"><table class=\\\"full\\\"><tr><td>App Name</td><td> : </td><td><input type=\\\"name\\\" name=\\\"appname\\\" id=\\\"appname\\\" required placeholder=\\\"App Name\\\" value=\\\"".$xsname."\\\" readonly></td></tr><tr><td>Source Files</td><td> : </td><td><input type=\\\"file\\\" name=\\\"source[]\\\" id=\\\"source\\\" accept=\\\"text/*,image/*,inode/*\\\" required multiple></td></tr><tr><td>Description</td><td> : </td><td><textarea name=\\\"description\\\" id=\\\"description\\\" placeholder=\\\"Description\\\">".$description."</textarea></td></tr><tr><td>Keyword 1</td><td> : </td><td><input type=\\\"name\\\" name=\\\"keyword1\\\" id=\\\"keyword1\\\" placeholder=\\\"Keyword 1\\\" value=\\\"".$keyword1."\\\"></td></tr><tr><td>Keyword 2</td><td> : </td><td><input type=\\\"name\\\" name=\\\"keyword2\\\" id=\\\"keyword2\\\" placeholder=\\\"Keyword 2\\\" value=\\\"".$keyword2."\\\"></td></tr><tr><td>Keyword 3</td><td> : </td><td><input type=\\\"name\\\" name=\\\"keyword3\\\" id=\\\"keyword3\\\" placeholder=\\\"Keyword 3\\\" value=\\\"".$keyword3."\\\"></td></tr><tr><td>Icon</td><td> : </td><td><input type=\\\"file\\\" name=\\\"icon\\\" id=\\\"icon\\\" accept=\\\"image/*\\\"></td></tr><tr><td>Category</td><td> : </td><td><input type=\\\"name\\\" name=\\\"category\\\" id=\\\"category\\\" placeholder=\\\"Category\\\" value=\\\"".$category."\\\"></td></tr><tr><td>Width</td><td> : </td><td><input type=\\\"number\\\" name=\\\"width\\\" id=\\\"width\\\" placeholder=\\\"Width\\\" value=\\\"".$width."\\\"></td></tr><tr><td>Height</td><td> : </td><td><input type=\\\"number\\\" name=\\\"height\\\" id=\\\"height\\\" placeholder=\\\"Height\\\" value=\\\"".$height."\\\"></td></tr><tr><td>Minimum Width</td><td> : </td><td><input type=\\\"number\\\" name=\\\"minwidth\\\" id=\\\"minwidth\\\" placeholder=\\\"Minimum Width\\\" value=\\\"".$minwidth."\\\"></td></tr><tr><td>Minimum Height</td><td> : </td><td><input type=\\\"number\\\" name=\\\"minheight\\\" id=\\\"minheight\\\" placeholder=\\\"Minimum Height\\\" value=\\\"".$minheight."\\\"></td></tr><tr><td>Maximum Width</td><td> : </td><td><input type=\\\"number\\\" name=\\\"maxwidth\\\" id=\\\"maxwidth\\\" placeholder=\\\"Maximum Width\\\" value=\\\"".$maxwidth."\\\"></td></tr><tr><td>Maximum Height</td><td> : </td><td><input type=\\\"number\\\" name=\\\"maxheight\\\" id=\\\"maxheight\\\" placeholder=\\\"Maximum Height\\\" value=\\\"".$maxheight."\\\"></td></tr></table></form></div><div class=\\\"bottombox\\\" id=\\\"popupbottombox\\\"><center><button type=\\\"button\\\" class=\\\"redButton\\\" onclick=\\\"window.location='index.php';\\\">Cancel</button><button type=\\\"reset\\\" class=\\\"yellowButton\\\" id=\\\"resetAppBuild\\\" form=\\\"build\\\">Reset</button><button type=\\\"button\\\" class=\\\"greenButton\\\" form=\\\"build\\\" onclick=\\\"validateNewApp();\\\">".(($available==2)?"Save":"Create")."</button></center></div>";
      ajaxMessage($ajaxtype,$ajaxmessage,$ajaxa,$ajaxx);
    }
  }
?>
