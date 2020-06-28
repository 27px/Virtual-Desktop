

<?php
  if(isset($_POST['OpenFile']) && !empty($_POST['OpenFile']))
  {
    global $decrypted;
    $url=$_POST['OpenFile'];
    if(!authorised(urldecode($url),$_SESSION['Logged']))
    {
      die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;margin:40px;text-align:center;padding-bottom:20px;width:calc(100% - 100px);white-space:initial;\">You are not authorised to open this Folder.</div><div style=\"color:#FF5050;font-size:20px;padding-bottom:100px;text-align:center;\">You are Logged in as ".$_SESSION['Logged']."</div>");
    }
    if(!file_exists($url))
    {
      header("Location:".$_SERVER['PHP_SELF']);
    }
    $plaintext=@file_get_contents($url);
    $decrypted=$plaintext;
    if($_SESSION['Logged']!="administrator@gmail.com")
    {
      $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
      $method='aes-256-cbc';
      $password=@substr(hash('sha256', $password, true), 0, 32);
      $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
      $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
    }
  }
  else
  {
    $url="";
  }
  if(isset($_POST["Save"]))
  {
    if(!is_readable($url))
    {
      die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Read Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
    }
    if(!is_writable($url))
    {
      die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Write Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
    }
    if($url!="")
    {
      $content=$_POST["cont"];
      $plaintext=$content;
      $encrypted=$plaintext;
      if($_SESSION['Logged']!="administrator@gmail.com")
      {
        $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
        $method='aes-256-cbc';
        $password=substr(hash('sha256', $password, true), 0, 32);
        $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
        $encrypted=base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
      }
      if(!file_put_contents($url,$encrypted)==true)
      {
        echo "Error Saving File";
      }
    }
  }
  else if(isset($_POST["edit"]))
  {
    $content=$_POST["cont"];
  }
  else
  {
    if($url!="")
    {
      if(!is_readable($url))
      {
        die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Read Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
      }
      if(!is_writable($url))
      {
        die("<div style=\"color:#FF5050;font-size:40px;border-bottom:1px solid #FF0000;padding:20px;margin:100px;text-align:center;\">Error : Write Access Denied.</div><a style=\"display:inline-block;text-decoration:none;color:#108010;width:100%;text-align:center;\" href='".$_SERVER['PHP_SELF']."'>Click here to continue.</a>");
      }
      else if(!($content=file_get_contents($url)))
      {
        $content="";
      }
      else
      {
        $plaintext=$content;
        if($_SESSION['Logged']!="administrator@gmail.com")
        {
          $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
          $method='aes-256-cbc';
          $password=@substr(hash('sha256', $password, true), 0, 32);
          $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
          $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
          $content=$decrypted;
        }
      }
    }
  }
  if($url!="")
  {
    if(!($content=file_get_contents($url)))
    {
      $content="";
    }
    else
    {
      $plaintext=$content;
      if($_SESSION['Logged']!="administrator@gmail.com")
      {
        $password='altindexedoffsetifiedstringsinteger'.$_SESSION['Logged'];
        $method='aes-256-cbc';
        $password=@substr(hash('sha256', $password, true), 0, 32);
        $iv=chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0).chr(0x0);
        $decrypted=openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
        $content=$decrypted;
      }
    }
  }
  if($content!="")
  {
    $nc=substr_count($content,PHP_EOL);
    $fc=explode(PHP_EOL,$content);
    $i=1;
    $num="";
    while($i<$nc)
    {
      $num.=$i++."\n";
    }
  }
  else
  {
    $num="1\n";
  }
  if($url!="")
  {
    $FileName=explode("/",$url);
    $FileName=end($FileName);
    $FileExt=explode(".",$FileName);
    $FileExt=end($FileExt);
    $FileType=strtolower($FileExt);
    $FileExt=".".$FileExt;
    $fileModifiedDate=date("d/F/Y H:i:s", filemtime($url));
    $FileProperties="";
    if(is_readable($url))
    {
      $FileProperties.="-r ";
    }
    if(is_writable($url))
    {
      $FileProperties.="-w ";
    }
    if(is_executable($url))
    {
      $FileProperties.="-x ";
    }
  }
  else
  {
    $FileName="[ No File Opened ]";
    $FileExt="-";
    $FileType="-";
    $FileExt="-";
    $fileModifiedDate="-";
    $FileProperties="-";
  }
?>


<html>
<head>
  <title>Editor</title>
  <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no">
</head>
<body>
<form id="Editor" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="saveCursor();">
<input type="hidden" value ="<?php echo $url; ?>" id="OpenFile" name="OpenFile">
  <input type="hidden" id="Shift" value="OFF">
  <input type="hidden" id="Ctrl" value="OFF">
  <input type="hidden" id="Alt" value="OFF">
  <div class="MainContainer">
    <div class="menuset">
      <div class="menu" name="File" id="menuFile" tabindex="0">File<div class="list"><button type="button" onclick="menuFileOpen();">Open</button><button name="Save">Save<span class='r'>Ctrl + S</span></button></div></div>
      <div class="menu" name="Edit" id="menuEdit" tabindex="0">Edit<div class="list"><button onclick="exe('undo');" name="edit" type="button">Undo<span class='r'>Ctrl + Z</span></button><button onclick="exe('redo');" name="edit" type="button">Redo<span class='r'>Ctrl + Y</span></button><button type="button" onclick="document.getElementById('txt').select();">Select All<span class='r'>Ctrl + A</span></button><button onclick="exe('cut');" name="edit" type="button">Cut<span class='r'>Ctrl + X</span></button><button onclick="exe('copy');" name="edit" type="button">Copy<span class='r'>Ctrl + C</span></button></div></div>
      <div class="menu" name="View" id="menuView" tabindex="0">View<div class="list"><button type="button" onclick="openPopup()">Editor Settings</button></div></div>
      <div class="menu" name="Insert" id="menuInsert" tabindex="0">Insert<div class="list"><button type="button" onclick="_('colorselector').click();">Color</button><button type="button" onclick="insertImageTag();">Image Tag</button><button type="button" onclick="insertInput();">Input</button><button type="button" onclick="insertTextarea();">Textarea</button><button type="button" onclick="insertButton();">Button</button><button type="button" onclick="insertDivTag();">Division Tag</button><button type="button" onclick="insertSpanTag();">Span Tag</button><button type="button" onclick="insertPTag();">Paragraph Tag</button><button type="button" onclick="insertAnchorTag();">Anchor Tag</button></div></div>
    </div>
    <div class="FrameContainer">
      <div class="directorytree" id="directorytree">
        <div class="dtree">Directory</div>
        <!--Directory-->
        <div class="directory">
          <div class="xtcontainer" onclick="togglefolder(event,this);" tabindex="0">
            <div class="xtitle nb">
              <div class="st">&gt;</div>
              <span class="dtitle">Root Folder</span>
            </div>
            <div class="dcontents nb">
              <?php
                getContentsFromDirectory($dir);
              ?>
            </div>
            <hr class="bb" />
          </div>
        </div>
        <!--Directory-->
      </div>
      <textarea class="edtext num" name="Num" id="num" cols=1 readonly onscroll='scrollN()' onfocus="this.blur;"><?php echo $num; ?></textarea>
      <div class="SubContainer">
        <textarea class="edtext txt" name='cont' id="txt" autofocus onscroll='scrollAll(this,event)' onkeydown='keypressed(event,this)' oninput='keychanged(0)' onkeyup='release(event)' autocorrect='off' autocapitalize='off' spellcheck='false'><?php echo $content; ?></textarea>
        <div class="ResizeButtonContainer" id="hbuttonContainer" onmouseover="showPbutton();" onmouseout="hidePbutton();">
          <input type="button" class="hidePreview" value="<" id="hidePreviewButton" onmouseover="showPbutton();" onmouseout="hidePbutton();" onclick="changePreview(this)">
        </div>
        <div class="PreContainer" onmouseover="showPbutton();" onmouseout="hidePbutton();" id="preCont">
          <iframe class="Preview" id="pre" sandbox src="data:text/html;charset=utf-8,<html><body style='color:rgb(255,100,100);font-size:30px;text-align:center;padding-top:100px;'>No File Opened or No Preview Available.<hr style='border-color:rgb(255,100,100);'></body></html>"></iframe>
          <div class="status">
            <table class="tstatus">
              <tr>
                <td>File Name</td>
                <td>:</td>
                <td id="fname">Untitled.html</td>
              </tr>
              <tr>
                <td>File Path</td>
                <td>:</td>
                <td id="fpath">/Untitled.html</td>
              </tr>
              <tr>
                <td>File Size</td>
                <td>:</td>
                <td id="fsize">0 B</td>
              </tr>
              <tr>
                <td>File Size (Bytes)</td>
                <td>:</td>
                <td id="fsizeb">0 B</td>
              </tr>
              <tr>
                <td>File Type</td>
                <td>:</td>
                <td id="ftype">HTML</td>
              </tr>
              <tr>
                <td>File Extension</td>
                <td>:</td>
                <td id="fext">.html</td>
              </tr>
              <tr>
                <td>Last Modified</td>
                <td>:</td>
                <td id="fmd"></td>
              </tr>
              <tr>
                <td>File Properties</td>
                <td>:</td>
                <td id="fproperties">-</td>
              </tr>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</form>
<div class="popupbg" id="settingspopupbg"></div>
<div class="popup" id="settingspopup">
  <div class="topbox">
    <span class="title">Settings</span>
    <button type="button" class="close" onclick="closePopup();"></button>
  </div>
<div class="content">
  <form id="Settings" name="Settings" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="saveCursor();">
    <br>
    <fieldset>
      <legend>&nbsp;&nbsp;Font&nbsp;&nbsp;</legend>
      <table border=0>
        <tr>
          <td>Menu Bar</td>
          <td>:</td>
          <td><select name="MenuBar"><option>Arial</option><option>Arial Black</option><option>Serif</option><option>Sans-Serif</option><option>Monospace</option><option>Cursive</option><option>Helvetica</option><option>Times New Roman</option><option>Times</option><option>Courier New</option><option>Courier</option><option>Verdana</option><option>Georgia</option><option>Palatino</option><option>Garamond</option><option>Bookman</option><option>Comic Sans MS</option><option>Trebuchet MS</option><option>Impact</option></select></td>
        </tr>
        <tr>
          <td>Editor</td>
          <td>:</td>
          <td><select name="Editor"><option>Arial</option><option>Arial Black</option><option>Serif</option><option>Sans-Serif</option><option>Monospace</option><option>Cursive</option><option>Helvetica</option><option>Times New Roman</option><option>Times</option><option>Courier New</option><option>Courier</option><option>Verdana</option><option>Georgia</option><option>Palatino</option><option>Garamond</option><option>Bookman</option><option>Comic Sans MS</option><option>Trebuchet MS</option><option>Impact</option></select></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend>&nbsp;&nbsp;Theme&nbsp;&nbsp;</legend>
      <table>
        <tr>
          <td>Select Accent Color</td>
          <td>:</td>
          <td><select name="AccentColor"><option selected>Cyan</option><option>Magenta</option><option>Yellow</option><option>Red</option><option>Green</option><option>Blue</option></select></td>
        </tr>
          <tr>
            <td>Select Theme</td>
            <td>:</td>
            <td><select name="Theme"><option selected>Dark</option><option>Light</option></td>
          </tr>
      </table>
    </fieldset>
  </form>
</div>
<div class="bottombox"><center><button class="redbutton" onclick="closePopup();" form="">Cancel</button><button form="Settings" class="yellowbutton" type="reset">Reset</button><button form="Settings" class="greenbutton" type="submit" name="savesettings">Save</button></center></div>
</div>

<script>
  document.getElementsByTagName('title')[0].innerHTML='Editor : <?php echo $FileName; ?>';
  if(window.history.replaceState)
  {
    window.history.replaceState(null,null,window.location.href);
  }
  <?php
    if(isset($_POST['Cursor']))
    {
      echo "setCursor(".$_POST['Cursor'].");";
    }
  ?>
	keychanged(0);
  changePreview(document.getElementById("hidePreviewButton"));
  if(window.history.replaceState)
  {
    window.history.replaceState(null,null,window.location.href);
  }
</script>
</body>
</html>
