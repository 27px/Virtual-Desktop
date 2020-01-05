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
  background-color:#000000;
}
div.loader
{
  background-color:#000000;
  width:100vw;
  height:100vh;
  overflow:hidden;
  position:fixed;
  top:0;
  left:0;
}
div.circle
{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  border-radius:50%;
}
div.c1
{
  width:80vmin;
  height:80vmin;
  border-left:5px solid #FFFF00;
  border-top:5px solid #FFFF00;
  border-right:5px solid transparent;
  border-bottom:5px solid transparent;
  animation:load 3.5s linear infinite normal;
}
div.c2
{
  width:60vmin;
  height:60vmin;
  border-right:5px solid #00FFFF;
  border-top:5px solid #00FFFF;
  border-left:5px solid transparent;
  border-bottom:5px solid transparent;
  animation:load 2.5s linear infinite reverse;
}
div.c3
{
  width:40vmin;
  height:40vmin;
  border-bottom:5px solid #00FF00;
  border-right:5px solid #00FF00;
  border-top:5px solid transparent;
  border-left:5px solid transparent;
  animation:load 5s linear infinite normal;
}
div.for-c1,div.for-c2,div.for-c3
{
  width:100%;
  height:0;
  position:absolute;
  top:50%;
  left:50%;
}
div.for-c1
{
  transform:translate(-50%,-50%) rotate(135deg);
}
div.for-c2
{
  transform:translate(-50%,-50%) rotate(45deg);
}
div.for-c3
{
  transform:translate(-50%,-50%) rotate(-45deg);
}
div.path::after
{
  content:"";
  width:25px;
  height:25px;
  position:absolute;
  top:50%;
  left:0;
  border-radius:50%;
  transform:translate(-55%,-50%);
}
div.for-c1::after
{
  background-color:#FFFF00;
  box-shadow:0px 0px 10px 0px #FFFF00,
             0px 0px 20px 0px #FFFF00,
             0px 0px 30px 0px #FFFF00,
             0px 0px 40px 0px #FFFF00,
             0px 0px 50px 0px #FFFF00;
}
div.for-c2::after
{
  background-color:#00FFFF;
  box-shadow:0px 0px 10px 0px #00FFFF,
             0px 0px 20px 0px #00FFFF,
             0px 0px 30px 0px #00FFFF,
             0px 0px 40px 0px #00FFFF,
             0px 0px 50px 0px #00FFFF;
}
div.for-c3::after
{
  background-color:#00FF00;
  box-shadow:0px 0px 10px 0px #00FF00,
             0px 0px 20px 0px #00FF00,
             0px 0px 30px 0px #00FF00,
             0px 0px 40px 0px #00FF00,
             0px 0px 50px 0px #00FF00;
}
@keyframes load
{
  0%
  {
    transform:translate(-50%,-50%) rotate(0deg);
  }
  100%
  {
    transform:translate(-50%,-50%) rotate(360deg);
  }
}
div.sbox
{
  position:absolute;
  top:50%;
  left:50%;
  width:10vmin;
  height:10vmin;
  transform:translate(-50%,-50%) rotate(0deg);
  animation:rotate 6.5s linear infinite normal;
}
div.sub
{
  display:inline-block;
  width:40%;
  height:40%;
  margin:5%;
  border-radius:20%;
  float:left;
}
div.sub1
{
  background-color:#FFFF90;
  box-shadow:0px 0px 10px 0px #FFFF00,
             0px 0px 20px 0px #FFFF00,
             0px 0px 30px 0px #FFFF00,
             0px 0px 40px 0px #FFFF00;
  animation:s1 1.5s ease-in-out infinite normal;
}
div.sub2
{
  background-color:#90FF90;
  box-shadow:0px 0px 10px 0px #00FF00,
             0px 0px 20px 0px #00FF00,
             0px 0px 30px 0px #00FF00,
             0px 0px 40px 0px #00FF00;
  animation:s2 1.5s ease-in-out infinite normal;
}
div.sub3
{
  background-color:#90FFFF;
  box-shadow:0px 0px 10px 0px #00FFFF,
             0px 0px 20px 0px #00FFFF,
             0px 0px 30px 0px #00FFFF,
             0px 0px 40px 0px #00FFFF;
  animation:s3 1.5s ease-in-out infinite normal;
}
div.sub4
{
  background-color:#FF90FF;
  box-shadow:0px 0px 10px 0px #FF00FF,
             0px 0px 20px 0px #FF00FF,
             0px 0px 30px 0px #FF00FF,
             0px 0px 40px 0px #FF00FF;
  animation:s4 1.5s ease-in-out infinite normal;
}
@keyframes s1
{
  0%
  {
    transform:translate(0%,0%) rotate(0deg);
  }
  50%
  {
    transform:translate(-50%,-50%) rotate(90deg);
  }
  100%
  {
    transform:translate(0%,0%) rotate(180deg);
  }
}
@keyframes s2
{
  0%
  {
    transform:translate(0%,0%) rotate(180deg);
  }
  50%
  {
    transform:translate(50%,-50%) rotate(90deg);
  }
  100%
  {
    transform:translate(0%,0%) rotate(0deg);
  }
}
@keyframes s3
{
  0%
  {
    transform:translate(0%,0%) rotate(180deg);
  }
  50%
  {
    transform:translate(-50%,50%) rotate(90deg);
  }
  100%
  {
    transform:translate(0%,0%) rotate(0deg);
  }
}
@keyframes s4
{
  0%
  {
    transform:translate(0%,0%) rotate(0deg);
  }
  50%
  {
    transform:translate(50%,50%) rotate(90deg);
  }
  100%
  {
    transform:translate(0%,0%) rotate(180deg);
  }
}
@keyframes rotate
{
  0%
  {
    transform:translate(-50%,-50%) rotate(360deg);
  }
  100%
  {
    transform:translate(-50%,-50%) rotate(0deg);
  }
}
</style>
<script>
function _(id)
{
  return document.getElementById(id);
}
function stopLoad()
{
  _("loader").style.display="none";
}
</script>
</head>
<body>
<div class="loader" id="loader">
  <div class="circle c1">
    <div class="path for-c1"></div>
  </div>
  <div class="circle c2">
    <div class="path for-c2"></div>
  </div>
  <div class="circle c3">
    <div class="path for-c3"></div>
  </div>
  <div class="sbox">
    <div class="sub sub1"></div>
    <div class="sub sub2"></div>
    <div class="sub sub3"></div>
    <div class="sub sub4"></div>
  </div>
</div>
</body>
<script>
 setTimeout(function(){
   window.location="Home/index.php";
 },4000);
</script>
</html>
