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
      uninstallApp($_GET['id']);
    }
  }
  function uninstallApp($app)
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
    $result=$conn->query("SELECT * FROM Apps WHERE ID=".$app." LIMIT 1");
    if(mysqli_num_rows($result)>0)
    {
      if($row=mysqli_fetch_row($result))
      {
        $app=$row[1];
        if(@file_exists($dir.$app.".fcz"))
        {
          if(@unlink($dir.$app.".fcz"))
          {
            die("{\"type\":\"success\", \"message\":\"Uninstalled\"}");
          }
          else
          {
            die("{\"type\":\"error\", \"message\":\"Error Couldn't Uninstall\"}");
          }
        }
        else
        {
          die("{\"type\":\"error\", \"message\":\"App Not Found\"}");
        }
      }
    }
  }
?>
