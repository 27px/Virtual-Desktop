<html>
<head>
<?php
  for($i=1;$i<6;$i++)
  echo "<link rel=\"preload\" type=\"image/jpg\" as=\"image\" href=\"".$i.".jpg\">";
?>
<style>
*
{
  padding:0;
  margin:0;
}
body
{
  overflow:hidden;
  user-select:none !important;
}
div.container
{
  display:flex;
  flex-direction:column;
  width:100%;
  height:100%;
}
div.top
{
  width:100%;
  height:calc(100% - 320px);
  background:url("1.jpg") rgba(255,255,255,0.5);
  background-size:cover;
  background-position:center;
  z-index:2;
  display:flex;
  background-blend-mode:luminosity;
  box-shadow:0px 0px 10px 1px #000000 inset,
             0px 0px 25px 1px #000000 inset,
             0px 0px 50px 1px #000000 inset;
}
div.left
{
  width:50%;
  height:100%;
}
div.right
{
  background-color:rgba(0,0,0,0.5);
  width:40%;
  height:80%;
  margin:22px;
  overflow:hidden;
  box-shadow:0px 0px 10px 1px #000000,
             0px 0px 25px 1px #000000,
             0px 0px 50px 1px #000000;
}
div.left div,div.right div
{
  color:#FFFFFF;
  text-shadow:0px 0px 5px #000000,0px 0px 10px #000000;
  text-align:center;
  height:50%;
  width:100%;
}
div.day
{
  font-size:100px;
  font-family:arial black,arial,sans-serif,serif;
  height:auto !important;
}
div.week
{
  margin-top:-25px;
  font-size:35px;
  font-family:arial,sans-serif,serif;
}
div.month
{
  padding-top:37.5px;
  font-size:30px;
  font-family:arial,sans-serif,serif;
  height:auto !important;
}
div.year
{
  font-size:30px;
  font-family:arial black,arial,sans-serif,serif;
}
div.bottom
{
  width:100%;
  height:320px;
  border-top:1px solid #000000;
  box-shadow:0px 0px 10px 1px #000000;
  z-index:1;
}
div.bottom table
{
  width:100%;
  height:100%;
  border-collapse:collapse;
  background-color:#FFFFFF;
}
div.bottom table *
{
  user-select:none;
}
div.bottom table tr:first-child
{
  background-color:#61b4f6;
  border-bottom:1.5px solid #000000;
  box-shadow:0px 0px 10px 1px #000000;
}
div.bottom table tr.odd
{
  background-color:#4cebff;
}
div.bottom table tr.even
{
  background-color:#7ff0ff;
}
div.bottom table tr th,div.bottom table tr td
{
  width:calc(100% / 7);
  height:calc(100% / 6);
}
div.bottom table tr th
{
  font-size:18px;
  font-family:arial black,arial,sans-serif,serif;
  letter-spacing:1.5px;
  color:#000080;
}
div.bottom table tr th:first-child
{
  color:#C00000;
}
div.bottom table tr td
{
  text-align:center;
  font-size:16px;
  font-family:arial black,arial,sans-serif,serif;
  letter-spacing:0.5px;
}
div.bottom table tr td:first-child
{
  color:#FF0000;
}
td.today
{
  background-color:rgba(0,0,0,0.35);
}
td.today:hover
{
  background-color:#61b4f6;
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function change()
{
  var i=parseInt(_("current").value);
  i++;
  if(i>5 || i<1)
  {
    i=1;
  }
  _("top").style.backgroundImage="url('"+i+".jpg')";
  _("current").value=i;
}
</script>
</head>
<body>
<?php
  date_default_timezone_set("Asia/Kolkata");
  $time=time();
  $day=date("j",$time);
  $week=date("l",$time);
  $month=date("F",$time);
  $year=date("Y",$time);
  $mlimit=date("t",$time);
  $where=date("w",$time);
?>
<input type="hidden" id="current" value="1">
<div class="container">
  <div class="top" id="top">
    <div class="left">
      <div class="day"><?php echo $day; ?></div>
      <div class="week"><?php echo $week; ?></div>
    </div>
    <div class="right">
      <div class="month"><?php echo $month; ?></div>
      <div class="year"><?php echo $year; ?></div>
    </div>
  </div>
  <div class="bottom">
    <table border=1>
      <tr>
        <th>Sun</th>
        <th>Mon</th>
        <th>Tue</th>
        <th>Wed</th>
        <th>Thu</th>
        <th>Fri</th>
        <th>Sat</th>
      </tr>
      <?php
        $wh=array(1,0,-1,-2,-3,-4,-5);
        for($xr=$day;$xr>7;$xr-=7);
        for($i=$xr,$j=$where;$i>0;$i--)
        {
          $j=($j>0)?($j-1):6;
        }
        $j++;
        $j=($j>6)?0:$j;
        $x=$wh[$j];
        $z=1;
        for($i=1;$i<6;$i++)
        {
          $c=(($i%2)==0)?"even":"odd";
          echo "<tr class=\"".$c."\">";
          for($j=1;$j<8;$j++,$x++,$z++)
          {
            $cx=($x>0 && $x<=$mlimit)?$x:"";
            $ccx=($cx==$day)?"today":"tcell";
            echo "<td class=\"".$ccx."\" id=\"xid_".$z."\">".$cx."</td>";
          }
          echo "</tr>";
        }
        echo "<script>";
        for($i=1;$x<=$mlimit;$i++,$x++)
        {
          echo "_('xid_".$i."').innerHTML=".$x.";";
        }
        echo "</script>";
      ?>
    </table>
  </div>
</div>
<script>
  setInterval(change,1500);
</script>
</body>
</html>
