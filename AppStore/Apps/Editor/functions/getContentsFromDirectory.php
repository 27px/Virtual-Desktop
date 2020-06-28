<?php
  require_once("functions.php");
  require_once("../../../../config/root.php");
  if(!isset($_SESSION['Logged']) || empty($_SESSION['Logged']))
  {
    http_response_code(403);
    die();
  }
  ?>
  <div class="directory showdircontents">
    <div class="dir">
      <div class="titlecontainer" onclick="togglefolder(event,this);" tabindex="0">
        <div class="ext"></div>
        <div class="title">Root Folder</div>
        <div class="refresh" tabindex="0" onclick="event.preventDefault();event.stopPropagation();loadDirectoryStructure();"></div>
      </div>
      <div class="contents">
      <?php
        getContentsFromDirectory($_SERVER["DOCUMENT_ROOT"]."/".$root."User/Desktop/".$_SESSION['Logged']);
      ?>
      <div class="divider"></div>
      </div>
    </div>
  </div>
<?php
  function getContentsFromDirectory($d)
  {
    $f=getDirData($d);
    if(!($f=='' || empty($f) || !is_array($f)))
    {
      $n=count($f);
      for($i=0;$i<$n;$i++)
      {
        $cx=$f[$i];
        $cx=str_replace("\\","/",$cx);
        $file=explode("/",$cx);
        $file=end($file);
        if(is_dir($d."/".$file))
        {
          ?>
            <div class="directory">
              <div class="dir">
                <div class="titlecontainer" onclick="togglefolder(event,this);" tabindex="0">
                  <div class="ext"></div>
                  <div class="title"><?php echo $file; ?></div>
                </div>
                <div class="contents">
                  <?php
                    getContentsFromDirectory($d."/".$file."/");
                  ?>
                  <div class="divider"></div>
                </div>
              </div>
            </div>
          <?php
        }
        else
        {
          //File
          $ext=explode(".",$file);
          $ext=end($ext);
          ?>
            <div class="file" ondblclick="openfile('<?php echo $d."/".$file."','".$ext; ?>');" tabindex="0">
              <div class="ext"><?php $temp=explode(".",$file); $temp=end($temp); echo strtoupper($temp); ?></div>
              <div class="title"><?php echo $file; ?></div>
            </div>
          <?php
        }
      }
    }
  }
?>
