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
  background:#404040 url("bg.jpg");
  background-size:cover;
}
div.container
{
  width:80vmin;
  height:80vmin;
  background:hsl(150deg,50%,50%) url("grass.png");
  background-size:cover;
  position:fixed;
  bottom:0%;
  left:50%;
  transform:translate(-50%,0%);
  overflow:auto;
}
div.column
{
  width:5%;
  height:5%;
  //background-color:hsl(150deg,50%,50%);
  background-color:transparent;
  display:inline-block;
  float:left;
}
div.star
{
  background-color:#808000;
  -webkit-clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
  clip-path:polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
  animation:shine 0.8s linear infinite normal;
}
@keyframes shine
{
  0%
  {
    background:linear-gradient(135deg,#FFFF00,#D0D000,#D0D000);
  }
  50%
  {
    background:linear-gradient(135deg,#D0D000,#FFFF00,#D0D000);
  }
  100%
  {
    background:linear-gradient(135deg,#D0D000,#D0D000,#FFFF00);
  }
}
div.body
{
  background:#00FF00;
  background-size:cover;
}
div.head
{
  background-color:#008000;
  outline:1px solid #00FF00;
}
input[type="text"]
{
  width:80px;
  height:60px;
  text-align:center;
  font-size:30px;
  color:#FFFFFF;
  background-color:#000000;
}
input[type="text"]:focus
{
  outline:none;
}
div.nav
{
  width:100%;
  height:auto;
  //background:linear-gradient(0deg,#000000,#000000,#404040,#404040,#000000,#000000);
}
div.brickWall
{
  border:5px solid #700000;
}
div.noWall
{
  border:5px solid #00FFFF;
}
</style>
<script>
var snake=[];
var xs=-1;
var loop;
var mode="no-wall";// brick-wall , no-wall
function run()
{
  if(move(parseInt(document.getElementById("direction").value),0)==false)
    clearTimeout(loop);
}
function generateApple()
{
  var starx=parseInt(Math.random()*19);
  var stary=parseInt(Math.random()*19);
  app="a_"+starx+"_"+stary;

  var x=document.getElementsByClassName(app)[0];
  if(!(x.classList.contains("head")||x.classList.contains("body")))
  {
    document.getElementsByClassName(app)[0].classList.toggle("star");
    return false;//to exit the loop
  }
  else
  {
    return true;//retry
  }
}
function move(ev,from)
{
  var b=document.getElementsByClassName("head")[0];
  var code=b.classList.item(1);
  var id=code.split('_');
  var i=parseInt(id[1]),j=parseInt(id[2]),valid=0;
  var dir=document.getElementById("direction");
  var xsy=parseInt(document.getElementById("length").value);
  if(ev==dir.value && from==1)
  return true;
  if(ev==38)
  {
    //Up
    if(i>0)
    {
      i--;
      valid=1;
      dir.value=ev;
    }
    else if(mode=="no-wall")
    {
      i=19;
      valid=1;
      dir.value=ev;
    }
    else
    {
      alert("Got a hit at the wall");
      return false;//out
    }
  }
  else if(ev==40)
  {
    //Down
    if(i<19)
    {
      i++;
      valid=1;
      dir.value=ev;
    }
    else if(mode=="no-wall")
    {
      i=0;
      valid=1;
      dir.value=ev;
    }
    else
    {
      i=0;
      alert("Got a hit at the wall");
      return false;//out
    }
  }
  else if(ev==37)
  {
    //Left
    if(j>0)
    {
      j--;
      valid=1;
      dir.value=ev;
    }
    else if(mode=="no-wall")
    {
      j=19;
      valid=1;
      dir.value=ev;
    }
    else
    {
      alert("Got a hit at the wall");
      return false;//out
    }
  }
  else if(ev==39)
  {
    //Right
    if(j<19)
    {
      j++;
      valid=1;
      dir.value=ev;
    }
    else if(mode=="no-wall")
    {
      j=0;
      valid=1;
      dir.value=ev;
    }
    else
    {
      alert("Got a hit at the wall");
      return false;//out
    }
  }
  if(valid==1)
  {
    b.classList.toggle("head");
    b.classList.toggle("body");
    var s=b.classList.item(1);
    snake.push(s);
    if(snake.length>xsy)
    {
      //var de=snake[++xs];
      var de=snake.shift();
      //remove the first Element [0] dequeue

      //console.log("m : "+0+" : "+snake[0]);
      //console.log("m : "+(snake.length-1)+" : "+snake[snake.length-1]);
      document.getElementsByClassName(de)[0].classList.toggle("body");
    }
    var cd="a_"+i+"_"+j;
    var x=document.getElementsByClassName(cd)[0];
    if(x.classList.contains("body"))
    {
      //Out : Head Hit on body
      alert("Snake Got Hit itself !");
      return false;//out
    }
    else if(x.classList.contains("star"))
    {
      xsy++;
      document.getElementById("length").value=xsy;
      document.getElementById("score").value=(xsy-3);
      //console.log("Length : "+xsy);
      x.classList.toggle("star");
      while(generateApple());
    }
    x.classList.toggle("head");
    return true;//next
  }
}
function start()
{
  var cxd = document.getElementById("container").children;
  for(let i=0;i<cxd.length;i++)
  {
    cxd[i].classList.remove("head","body","star");
  }
  document.getElementsByClassName("a_5_6")[0].classList.toggle("head");
  document.getElementById("score").value=0;
  document.getElementById("length").value=3;
  document.getElementById("direction").value=39;
  if(mode=="brick-wall")
  {
    document.getElementById("container").classList.remove("noWall");
    document.getElementById("container").classList.add("brickWall");
  }
  else if(mode=="no-wall")
  {
    document.getElementById("container").classList.remove("brickWall");
    document.getElementById("container").classList.add("noWall");
  }
  while(generateApple());
  loop=setInterval(run,200);
}
</script>
</head>
<body onkeydown="move(event.keyCode,1);">
<div class="nav">

  <input type="text" id="score" value=0>
</div>
<input type="hidden" id="direction" value="">
<input type="hidden" id="length" value=3>
<div class="container" id="container">
<?php
for($i=0;$i<20;$i++)
{
  for($j=0,$c="";$j<20;$j++,$c="")
  {
    echo "<div class='column a_".$i."_".$j."'></div>\n";
  }
}
?>
</div>
<script>
start();
</script>
</body>
</html>
