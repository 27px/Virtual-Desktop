<?php
  ob_start();
  session_start();
  if(!(isset($_SESSION['Logged'])))
  {
    header("Location:../../../Login/index.php");
  }
  else
  {
    $dir=$_SESSION['Logged'];
  }
  require_once("../../../config/root.php");
  if(!@is_dir($dir))
  {
    $dir=realpath(getcwd()."/../../../User/Desktop/".$_SESSION['Logged']);
  }
  require_once("../../../config/connect_db.php");
  $num="";
  $decrypted="";
  $encrypted="";
  $content="";
  require_once("functions/functions.php");
?>
<html>
  <head>
    <title>Editor</title>
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no">
    <meta name="theme-color" content="#212127">
    <link rel="stylesheet" type="text/css" href="style/style.min.css">
    <script src="script/script.min.js"></script>
  </head>
  <body onload="adjustUI();loadDirectoryStructure();adjustNumberLine();" shift-key="off" ctrl-key="off" alt-key="off" onmouseleave="outOfRange();" onmouseenter="inRange();">
    <div class="container">
      <div class="actionbar">
        <div class="menu" tabindex="0" id="menuFile">
          <div class="title">File</div>
          <div class="list">
            <table>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_file_new.svg');"></div>
                </td>
                <td>
                  <div class="name">New</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + N</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();expandDirectoryViewer();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_file_open.svg');"></div>
                </td>
                <td>
                  <div class="name">Open</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + O</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_file_save.svg');"></div>
                </td>
                <td>
                  <div class="name">Save</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + S</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_file_save.svg');"></div>
                </td>
                <td>
                  <div class="name">Save As</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Shift + N</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_file_upload.svg');"></div>
                </td>
                <td>
                  <div class="name">Upload File</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Shift + U</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_file_download.svg');"></div>
                </td>
                <td>
                  <div class="name">Download File</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Shift + D</div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="menu" tabindex="0" id="menuEdit">
          <div class="title">Edit</div>
          <div class="list">
            <table>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_edit_undo.svg');"></div>
                </td>
                <td>
                  <div class="name">Undo</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Z</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_edit_redo.svg');"></div>
                </td>
                <td>
                  <div class="name">Redo</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Shift + Z</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_edit_selectall.svg');"></div>
                </td>
                <td>
                  <div class="name">Select All</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + A</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_edit_cut.svg');"></div>
                </td>
                <td>
                  <div class="name">Cut</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + X</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_edit_copy.svg');"></div>
                </td>
                <td>
                  <div class="name">Copy</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + C</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_edit_paste.svg');"></div>
                </td>
                <td>
                  <div class="name">Paste</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + P</div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="menu" tabindex="0" id="menuView">
          <div class="title">View</div>
          <div class="list">
            <table>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_view_settings.svg');"></div>
                </td>
                <td>
                  <div class="name">Editor Settings</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Alt + S</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_view_increase.svg');"></div>
                </td>
                <td>
                  <div class="name">Increase Font Size</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Shift + +</div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_view_decrease.svg');"></div>
                </td>
                <td>
                  <div class="name">Decrease Font Size</div>
                </td>
                <td>
                  <div class="shortcut">Ctrl + Shift + +</div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="menu" tabindex="0" id="menuInsert">
          <div class="title">Insert</div>
          <div class="list">
            <table>
              <tr tabindex="0" onmousedown="event.preventDefault();_('colorpicker').click();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_color.svg');"></div>
                </td>
                <td>
                  <div class="name">Color</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertImageTag();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_image.svg');"></div>
                </td>
                <td>
                  <div class="name">Image Tag</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertInput();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_input.svg');"></div>
                </td>
                <td>
                  <div class="name">Input</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertTextarea();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_textarea.svg');"></div>
                </td>
                <td>
                  <div class="name">Textarea</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertButton();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_button.svg');"></div>
                </td>
                <td>
                  <div class="name">Button</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertDivTag();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_div.svg');"></div>
                </td>
                <td>
                  <div class="name">Division Tag</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertSpanTag();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_span.svg');"></div>
                </td>
                <td>
                  <div class="name">Span Tag</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertPTag();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_paragraph.svg');"></div>
                </td>
                <td>
                  <div class="name">Paragraph Tag</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
              <tr tabindex="0" onmousedown="event.preventDefault();insertAnchorTag();">
                <td>
                  <div class="icon" style="background-image:url('Images/Icon/icon_insert_anchor.svg');"></div>
                </td>
                <td>
                  <div class="name">Anchor Tag</div>
                </td>
                <td>
                  <div class="shortcut"></div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="subcontainer">
        <div class="dircontainer" id="dircontainer">
          <div class="wrapper">
            <div class="hoverline"></div>
            <div class="dirwrapper" id="dirstructure">
              <!-- Directory Structure will be loaded using AJAX -->
            </div>
          </div>
          <div class="toggler" onclick="toggleDirectoryViewer();"></div>
        </div>
        <div class="editwrapper">
          <textarea class="numberline" id="num" readonly onfocus="_('txt').focus();" tabindex="-1">1
</textarea>
          <textarea class="editor" id="txt" autofocus autocorrect='off' autocapitalize='off' spellcheck='false' oninput='keychanged(0)' onkeyup='release(event)' onscroll='scrollAll(this,event)' onkeydown='keypressed(event,this)' openedFileType="" openedFilePath=""></textarea>
        </div>
        <div class="preview" id="previewcontainer">
          <div class="wrapper">
            <div class="hoverline"></div>
            <div class="previewwrapper">
              <iframe src='data:text/html;charset=utf-8,<html><body style="color:rgb(255,255,255);font-size:20px;letter-spacing:2.5px;text-align:center;"><p style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);margin:0;opacity:0.5;">No file opened.</p></body></html>' class="pre" id="pre" sandbox onmouseenter="inRange();"></iframe>
              <div class="properties">
                <table border="1">
                  <tr>
                    <td>File</td>
                    <td>:</td>
                    <td id="fname">Not opened</td>
                  <tr>
                  <tr>
                    <td>Path</td>
                    <td>:</td>
                    <td id="fpath">-</td>
                  <tr>
                  <tr>
                    <td>Type</td>
                    <td>:</td>
                    <td id="ftype">-</td>
                  <tr>
                  <tr>
                    <td>Size</td>
                    <td>:</td>
                    <td id="fsize">0 B</td>
                  <tr>
                  <tr>
                    <td>Size (B)</td>
                    <td>:</td>
                    <td id="fsizeb">0 B</td>
                  <tr>
                  <tr>
                    <td>Extension</td>
                    <td>:</td>
                    <td id="fext">-</td>
                  <tr>
                  <tr>
                    <td>Last modified</td>
                    <td>:</td>
                    <td id="fmd">-</td>
                  <tr>
                  <tr>
                    <td>Read Access</td>
                    <td>:</td>
                    <td id="fpropertyread">-</td>
                  <tr>
                  <tr>
                    <td>Write Access</td>
                    <td>:</td>
                    <td id="fpropertywrite">-</td>
                  <tr>
                  <tr>
                    <td>Executable</td>
                    <td>:</td>
                    <td id="fpropertyexecute">-</td>
                  <tr>
                </table>
              </div>
            </div>
          </div>
          <div class="toggler" onclick="togglePreview();"></div>
        </div>
      </div>
    </div>
    <input type="color" id="colorpicker" value="#FF0000" class="hidden" style="display:none;" onchange="insertColor(this.value);" />
    <script>
      keychanged(0);
      if(window.history.replaceState)
      {
        window.history.replaceState(null,null,window.location.href);
      }
    </script>
  </body>
</html>
