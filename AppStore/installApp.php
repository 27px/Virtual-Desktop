<?php
  ob_start();
  session_start();
  if(!(isset($_SESSION['Logged'])))
  {
    die("{\"type\":\"error\", \"message\":\"Not Logged in\"}");
  }
  else
  {
    $dir=$_SESSION['Logged'];
    if(isset($_GET['id']) && !empty($_GET['id']))
    {
      installApp($_GET['id']);
    }
  }
  function installApp($id)
  {
    global $dir;
    require_once("../config/root.php");
    if(!@is_dir($dir))
    {
      $dir=$_SERVER['DOCUMENT_ROOT']."/".$root."User/Desktop/".$_SESSION['Logged'];
    }
    require_once("../config/database.php");
    $conn=new mysqli($servername,$username,$password,$database);
    if(mysqli_connect_error())
    {
      die("{\"type\":\"error\", \"message\":\"Database error\"}");
    }
    $result=$conn->query("SELECT * FROM Apps WHERE ID=".$id." LIMIT 1");
    if(mysqli_num_rows($result)>0)
    {
      if($row=mysqli_fetch_row($result))
      {
        if($row[4]!="Approved" && ($row[3]!=$_SESSION['Logged'] && $_SESSION['User']!="Manager"))
        {
          die("{\"type\":\"error\", \"message\":\"Unapproved App\"}");
        }
        $t="Installed";
        if(@file_exists($dir.$row[1].".fcz"))
        {
          $t="Updated";
        }
        $f=@fopen($dir.$row[1].".fcz","w");
        $c="[\"type\"=\"Application\"]".PHP_EOL."[\"id\"=\"".$row[0]."\"]".PHP_EOL."[\"version\"=\"".$row[17]."\"]".PHP_EOL."[\"icon\"=\"".$row[9]."\"]".PHP_EOL."[\"title\"=\"".$row[1]."\"]".PHP_EOL."[\"width\"=\"".$row[11]."\"]".PHP_EOL."[\"height\"=\"".$row[12]."\"]".PHP_EOL."[\"min-width\"=\"".$row[13]."\"]".PHP_EOL."[\"min-height\"=\"".$row[14]."\"]".PHP_EOL."[\"max-width\"=\"".$row[15]."\"]".PHP_EOL."[\"max-height\"=\"".$row[16]."\"]";
        if(fwrite($f,$c))
        {
          echo "{\"type\":\"success\", \"message\":\"".$t."\"}";
        }
        @fclose($f);
      }
    }
  }
?>
