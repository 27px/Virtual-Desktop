<?php
$code="";
function color($str,$actual)
{
  $s=strlen($str);
  $a=strlen($actual);
  $l=$s<$a?$s:$a;
  $result="";
  for($i=0;$i<$l;$i++)
  {
    if($str[$i]==$actual[$i])
    {
      $result.="<span class=\"green\">".$str[$i]."</span>";
    }
    else
    {
      $result.="<span class=\"red\">".$str[$i]."</span>";
    }
  }
  return $result;
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
  background-color:#FF5050;
}
h1
{
  color:#FFFFFF;
  font-family:arial black,sans-serif,serif;
  font-weight:900;
  font-size:50px;
  -webkit-text-stroke:1px #FFFFFF;
  color:transparent;
  letter-spacing:3.2px;
  display:inline-block;
  padding:10px;
  transform:translate(-20%,0%);
  user-select:none;
  cursor:default;
}
h1:before,h1:after
{
  font-size:16px;
  -webkit-text-stroke:0px transparent;
  color:#FFFFFF;
  position:relative;
  user-select:none;
  cursor:default;
}
h1:before
{
  content:"Increase";
  top:-45px;
  left:85px;
}
h1:after
{
  content:"Power";
  top:25px;
  right:50px;
}
form
{
  background-color:rgba(255,255,255,0.2);
  width:500px;
  height:auto;
  max-height:75vmin;
  border-radius:5px;
  box-shadow:0px 0px 10px 1px #000000;
  position:absolute;
  left:50%;
  top:50%;
  transform:translate(-50%,-50%);
  overflow:hidden;
  box-sizing:border-box;
  padding:20px;
}
table
{
  width:calc(100% - 40px);
  margin-top:20px;
  margin-left:20px;
  margin-right:20px;
}
select,input[type="number"],input[type="submit"],input[type="text"]
{
  width:100%;
  border:none;
  outline:none;
  background-color:rgba(255,255,255,0.5);
  border-radius:100px;
  padding:8px;
}
select:hover,input[type="number"]:hover
{
  background-color:rgba(255,255,255,0.8);
}
input[type="submit"]
{
  border:1px groove #800000;
}
input[type="submit"]:hover
{
  background-color:#50FFFF;
}
input[type="number"]::-webkit-inner-spin-button
{
  display:none;
}
tr.break
{
  height:10px;
}
td
{
  min-width:100px;
  text-align:center;
  font-weight:900;
}
div.code
{
  width:100%;
  height:auto;
  min-height:50px;
  font-size:40px;
  color:#FFFFFF;
  font-family:arial black,arial,sans-serif,serif;
  -webkit-text-stroke:1px #000000;
  user-select:none;
  text-align:center;
}
input[name="Go"]
{
  width:calc(100% - 30px);
  margin:15px;
}
input[name="Go"]:hover
{
  background-color:#50FF50;
}
input[name="back"]:hover
{
  background-color:#FF5050;
}
div.green,span.green
{
  color:#50FF50;
  -webkit-text-stroke:1px #008000;
}
div.red,span.red
{
  color:#FF0000;
  -webkit-text-stroke:0.5px #FFFFFF;
}
span.unformatted
{
  color:#FFFFFF;
  -webkit-text-stroke:0.5px #000000;
}
</style>
<script>

</script>
</head>
<body>
<h1>Memory</h1>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <table>
    <?php
      if(!isset($_POST['Go']) && !isset($_POST['validate']))
      {
    ?>
    <tr>
      <th>Type</th>
      <td> : </td>
      <td>
        <select name="type">
          <option <?php if(isset($_POST['generate']))if($_POST['type']=='Binary')echo "Selected" ?>>Binary</option>
          <option <?php if(isset($_POST['generate']))if($_POST['type']=='Numbers')echo "Selected" ?>>Numbers</option>
          <option <?php if(isset($_POST['generate']))if($_POST['type']=='Hexadecimal')echo "Selected" ?>>Hexadecimal</option>
          <option <?php if(isset($_POST['generate']))if($_POST['type']=='Alphabets')echo "Selected" ?>>Alphabets</option>
        </select>
      </td>
    </tr>
    <tr class="break">
    </tr>
    <tr>
      <th>Limit</th>
      <td> : </td>
      <td>
        <input autocomplete="off" type="number" name="limit" min="3" max="15" <?php if(isset($_POST['generate']))echo "value=\"".$_POST['limit']."\""; ?> placeholder="Number of Characters">
      </td>
    </tr>
    <tr class="break">
    </tr>
    <tr>
      <th colspan="3">
        <input name="generate" type="submit" value="Generate Code">
      </th>
    </tr>
    </tr>
    <tr class="break">
    </tr>
    <?php
      }
      else if(!isset($_POST['validate']))
      {
    ?>
        <tr>
          <td colspan="3">
            <input autocomplete="off" name="value" type="text" required placeholder="Enter the code">
          </td>
        </tr>
        <tr class="break">
        </tr>
        <tr>
          <td>
            <input type="submit" name="back" value="Back">
          </td>
          <td colspan="2">
            <input type="submit" name="validate" value="Check">
          </td>
        </tr>
        <tr class="break">
        </tr>
    <?php
        echo "<input type=\"hidden\" name=\"str\" value=\"".$_POST['str']."\">";
      }
      if(isset($_POST['back']) && isset($_POST['str']))
      {
        echo "<tr><td colspan=\"3\"><div class=\"code\">".$_POST['str']."</div></td></tr>";
      }
      if(isset($_POST['validate']))
      {
        ?>
        <tr>
          <td colspan="3">
            <?php if($_POST['value']==$_POST['str'])echo "<div class=\"code green\">Correct</div>"; else echo "<div class=\"code red\">Incorrect<br><span class=\"unformatted\">Actual is<br>".$_POST['str']."</span></div>"; ?>
          </td>
        </tr>
        <tr class="break">
        </tr>
        <tr>
          <td colspan="3">
            <div class="code"><?php echo color($_POST['value'],$_POST['str']); ?></div>
          </td>
        </tr>
        <tr class="break">
        </tr>
        </tr>
        <tr class="break">
        </tr>
        </tr>
        <tr class="break">
        </tr>
        <tr>
          <td colspan="3">
            <input type="submit" name="back" value="Back">
          </td>
        </tr>
        <tr class="break">
        </tr>
        <?php
      }
    ?>
  </table>
    <?php
      if(isset($_POST['generate']))
      {
        $r=$_POST['limit'];
        if(($r<2 && $r>16) || empty($r))
        $r=3;
        echo "<div class=\"code\">";
        $str="";
        if($_POST['type']=="Binary")
        {
          $str=substr(str_shuffle("0101010101010101"),16-$r);
        }
        else if($_POST['type']=="Numbers")
        {
          $str=substr(str_shuffle("12345678901234567890"),20-$r);
        }
        else if($_POST['type']=="Hexadecimal")
        {
          $str=substr(str_shuffle("0123456789ABCDEF"),16-$r);
        }
        else if($_POST['type']=="Alphabets")
        {
          $str=substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),26-$r);
        }
        echo $str."</div>";
        echo "<input name=\"Go\" type=\"submit\" value=\"Start\">";
        echo "<input type=\"hidden\" name=\"str\" value=\"".$str."\">";
      }
      if(isset($_POST['back']) && isset($_POST['str']))
      {
        echo "<input name=\"Go\" type=\"submit\" value=\"Start\">";
        echo "<input type=\"hidden\" name=\"str\" value=\"".$_POST['str']."\">";
      }
    ?>
</form>
</body>
</html>
