<html>
<head>
<style>
body
{
  background-color:transparent;
}
canvas,.in
{
  position:absolute;
  top:50%;
  transform:translate(10%,-50%);
  border:1px solid rgba(255,255,255,0.3);
  border-radius:50%;
}
canvas
{
  width:80vmin;
  height:80vmin;
  left:0%;
}
.in
{
  width:calc(80vmin - 20px);
  height:calc(80vmin - 20px);
  left:12px;
}
</style>
</head>
<body oncontextmenu="return false;">
<canvas id="my_canvas" width="500" height="500"></canvas>
<script>
var ctx = document.getElementById('my_canvas').getContext('2d');
var al = 0;
var start = 4.72;
var cw = ctx.canvas.width;
var ch = ctx.canvas.height;
var diff;
function progressSim(x)
{
	diff = ((al / 100) * Math.PI*2*10).toFixed(2);
	ctx.clearRect(0, 0, cw, ch);
	ctx.lineWidth = 20;
  ctx.fillStyle='#FFFFFF';
  ctx.strokeStyle="#FFFF00";
  ctx.font="60px digital-7";
	ctx.textAlign = 'center';
	ctx.fillText(parseInt(al)+' %', cw*.5, ch*.45+2, cw);
	ctx.fillText('Used', cw*.5, ch*.6+2, cw);
	ctx.beginPath();
	ctx.arc(250, 250, 250, start, diff/10+start, false);
	ctx.stroke();
	if(al>=x)
  {
		clearTimeout(sim);
	}
	al+=0.1;
  al=parseInt(al*100)/100;
}
var sim="";
function setProgress(progress,delay)
{
  sim=setInterval(progressSim,delay,progress);
}
//setProgress(int:progress-in-percentage,int:delay);
<?php
  if(isset($_GET['progress']) && !empty($_GET['progress']))
  {
    echo "setProgress(".$_GET['progress'].",5);";
  }
?>
</script>
<div class="in"></div>
</body>
</html>
