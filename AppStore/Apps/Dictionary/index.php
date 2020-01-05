<?php
  require_once("../../../config/database.php");
  $database="Dictionary";
?>
<html>
<head>
<style>
*
{
  margin:0;
  padding:0;
}
body
{
  //width:800px;
  //height:600px;
  background:#000000 url("bg.jpg");
  background-size:cover;
  background-attachment:fixed;
  overflow:hidden;
}
body::-webkit-scrollbar
{
  display:none;
}
div.s
{
  width:calc(100% - 20px);
  height:65px;
  padding:10px;
  background-color:hsl(150deg,50%,50%);
}
div.result,div.resultError
{
  width:calc(100% - 20px);
  height:auto;
  min-height:100px;
  background-color:rgba(255,255,255,0.8);
  margin-top:5px;
  padding:10px;
  border-radius:5px;
}
div.resultError
{
  width:calc(100% - 21px);
  color:#FFFFFF;
  background-color:rgba(255,0,0,0.7);
  font-weight:900;
  font-size:30px;
  font-family:sans-serif;
  text-align:center;
  border:1px solid #FFFFFF;
}
div.result p.word,div.result p.type
{
  width:48%;
  height:auto;
  display:inline-block;
}
div.result p.type,div.result p.description
{
  font-size:20px;
}
div.result p.type
{
  text-align:right;
  float:right;
}
div.result p.word
{
  font-weight:900;
  font-size:30px;
}
div.result span.highlight
{
  color:#0000A0;
}
div.title
{
  color:#FFFFFF;
  font-family:sans-serif;
  font-weight:900;
  display:inline-block;
  float:left;
  font-size:20px;
  padding:20px;
  position:relative;
}
div.title::before,div.title::after
{
  color:rgba(255,255,255,0.9);
  font-family:serif;
  font-weight:normal;
  font-size:16px;
  display:block;
  margin-right:10px;
}
div.title::after
{
  content:"English";
  position:absolute;
  right:0;
  bottom:0;
}
div.title::before
{
  content:"Simple";
  position:absolute;
  left:0;
  top:0;
}
div.searchcontainer
{
  width:50%;
  float:left;
  display:flex;
  position:relative;
  flex-wrap:nowrap;
  padding:5px;
  box-shadow:0px 0px 15px 1px #507050 inset;
  border-radius:200px;
  box-sizing:border-box;
}
div.searchcontainer:hover
{
  border:1px solid #000000;
}
img.searchIcon
{
  height:50%;
  width:auto;
  position:absolute;
  top:50%;
  left:10;
  transform:translateY(-50%);
}
input[type="name"]
{
  font-size:20px;
  font-family:serif;
  height:50%;
  width:100%;
  background-color:transparent;
  border:none;
  padding-left:30px;
  padding-right:5px;
}
input[type="name"]::placeholder
{
  color:#D0D0D0;
}
input[type="name"]:focus
{
  outline:none;
}
div.subcontainer
{
  height:100%;
  padding-top:10px;
}
input[type="submit"]
{
  font-size:20px;
  font-family:serif;
  height:50%;
  width:100%;
  display:block;
  border:1px solid #000000;
  border-radius:5px;
  margin-top:5px;
  background-color:rgba(255,255,255,0.2);
}
select
{
  height:50%;
  width:100%;
  font-family:serif;
  font-size:20px;
  display:block;
  border-radius:5px;
  border:1px solid #000000;
  background-color:rgba(255,255,255,0.2);
}
select:hover
{
  background:linear-gradient(135deg,#00C000,#68F068,#00C000);
}
input[type="submit"]:hover
{
  background:linear-gradient(135deg,#00C000,#68F068,#00C000);
}
div.buttoncontainer
{
  height:100%;
  width:28%;
  position:relative;
  top:-13px;
  right:0px;
  float:right;
}
option
{
  background-color:rgba(0,255,128,0.7);
}
select:focus,option:focus
{
  outline:none;
}
div.resultantcontainer
{
  padding:5px;
  box-sizing:border-box;
  width:100%;
  height:calc(100% - 90px);
  overflow-x:hidden;
  overflow-y:auto;
}
div.resultantcontainer::-webkit-scrollbar
{
  width:10px;
  border-radius:50px;
  border:1px solid #C0C0C0;
  background-color:rgba(255,255,255,0.5);
}
div.resultantcontainer::-webkit-scrollbar-thumb
{
  border-radius:50px;
  background-color:rgba(0,0,0,0.5);
}
div.loader
{
  width:100vw;
  height:100vh;
  background-color:#FFFFFF;
  position:fixed;
  top:0;
  left:0;
  box-sizing:border-box;
  z-index:100;
  display:none;
  color:#003000;
  padding:10px;
  font-size:30px;
  background-color:hsl(150deg,50%,50%);
}
div.probox
{
  width:80vw;
  height:30px;
  border:1px solid #000000;
  border-radius:200px;
  overflow:hidden;
  position:absolute;
  top:50%;
  left:50%;
  display:block;
  transform:translate(-50%,-50%);
}
div.progress
{
  height:100%;
  width:100%;
  background:repeating-linear-gradient(45deg,#438600 0%,#8fd747 5%,#438600 10%);
}
div.xprogress
{
  height:100%;
  width:100%;
  white-space:nowrap;
  padding:5px;
  box-sizing:border-box;
  transform:translateY(-100%);
  color:#000000;
  font-size:16px;
}
</style>
<script>
function load()
{
  document.getElementById("loader").style.display="block";
}
function unload()
{
  document.getElementById("loader").style.display="none";
}
function setLoad(progress)
{
  progress=progress-100;
  document.getElementById("progress").style.transform="translateX("+progress+"%)";
}
</script>
</head>
<body>

  <?php
    $conn=new mysqli($servername,$username,$password);
    if(mysqli_connect_error())
    {
      echo "<p class='error'>Connection Error : ".mysqli_connect_error()."</p>";
      die();
    }
    if(empty(mysqli_fetch_array($conn->query("SHOW DATABASES LIKE 'Dictionary'"))))
    {
      echo "<p class='error'>Database not Found</p>";
    }
    if(!($conn->query("USE Dictionary")==true))
    {
      echo "<p class='error'>Couldn't change Database</p>";
      die();
    }
    if(empty(mysqli_fetch_array($conn->query("SHOW TABLES LIKE 'English'"))))
    {
      echo "<p class='error'>Table not Found</p>";
      die();
    }
  ?>

  <div class="loader" id="loader"><p class="title">Please Wait while fetching the data . . .</p>
  <div class="probox">
  <div class="progress" id="progress"></div>
  <div class="xprogress" id="xprogress"></div>
  </div>
  </div>
  <form acion="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

  <div class='s'>
    <div class='title'>Dictionary</div>
    <div class='subcontainer'>
      <div class='searchcontainer'>
        <img src='search.svg' class='searchIcon'>
        <input type='name' placeholder='Search Word' class='Key' id='Key' name='Key' autofocus autocomplete='off'>
      </div>
      <div class='buttoncontainer'>
        <select name='searchtype' id='searchtype'>
          <option>Auto</option>
          <option>Exact</option>
          <option>Starting with</option>
          <option>Ending in</option>
          <option>In between</option>
        </select>
        <input type='submit' value='Search' name='Search' id='Search'>
      </div>
    </div>
  </div>
</form>

  <div class='resultantcontainer'>



  <?php
    if(isset($_POST['Search']))
    {
      $k=$_POST['Key'];
      if($k=="")
      $searchkey="%";
      else if(isset($_POST['searchtype']))
      {
        if($_POST['searchtype']=="Exact")
        {
          $searchkey=$k;
        }
        else if($_POST['searchtype']=="Starting with")
        {
          $searchkey=$k."%";
        }
        else if($_POST['searchtype']=="Ending in")
        {
          $searchkey="%".$k;
        }
        else if($_POST['searchtype']=="In between")
        {
          $searchkey="%".$k."%";
        }
        else
        {
          $searchkey=$k;
        }
      }
      $result=$conn->query("SELECT * FROM English WHERE word like '".$searchkey."'");
      $n=mysqli_num_rows($result);
      if($n<=0 && $_POST['searchtype']=="Auto")
      {
        $result=$conn->query("SELECT * FROM English WHERE word like '%".$k."%'");
        $n=mysqli_num_rows($result);
      }
      if($n<=0)
      {
        //echo "<tr>";
        //echo "<td colspan='3'>Empty</td>";
        //echo "</tr>";
        echo "<div class='result'>Empty</div>";
      }
      else
      {
        echo "<script>load();</script>";
        $i=0;
        $p=0;
        while($row=mysqli_fetch_row($result))
        {
          $i++;
          $p=(($i*100)/$n);
          /*
          echo "<tr>";
          echo "<td>".$row[0]."</td>";
          echo "<td>".$row[1]."</td>";
          echo "<td class='description'>".$row[2]."</td>";
          echo "</tr>";
          */
          $re="<span class='highlight'>".strtoupper($k)."</span>";
          $word=str_ireplace($k,$re,strtoupper($row[0]));
          $wordsleft=$n-$i;
          echo "<div class='result'><p class='word'>".$word."</p><p class='type'>".$row[1]."</p><p class='description'>".$row[2]."</p></div>";
          echo "<script>setLoad(".$p.");</script>";
          echo "<script>document.getElementById('xprogress').innerHTML='".$i." words from ".$n.", Words left : ".$wordsleft." - '+parseInt(".$p.")+'%';</script>";
        }
        echo "<script>unload();</script>";
      }
    }
  ?>
  </div>
</body>
</html>
<?php
  $conn->close();
?>
