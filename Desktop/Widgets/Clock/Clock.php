<?php
  if((isset($_GET['light']) && !empty($_GET['light'])) && (isset($_GET['light']) && !empty($_GET['light'])))
  {
    $light=$_GET['light'];
    $dark=$_GET['dark'];
  }
  else
  {
    $light="#00C0FF";
    $dark="#0080FF";
  }
  $needle="";
  if(!isset($_GET['needle']))
  {
    if(empty($_GET['needle']))
    {
      if(($dark=="#0000C0" || $light=="#0000C0") || ($dark=="#C00000" || $light=="#C00000") || ($light=="#000000" || $dark=="#000000") || ($dark=="#800080"))
      {
        $needle="#FFFFFF";
      }
      else
      {
        $needle="#000000";
      }
    }
    else
    {
      $needle=$_GET['needle'];
    }
  }
  else
  {
    $needle=$_GET['needle'];
  }
?>
<html>
<head>
<style>
body
{
  background:transparent;
}
svg
{
  display: block;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
</head>
<body>
<svg width="200" height="200">
    <filter id="innerShadow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur"/>
      <feOffset in="blur" dx="2.5" dy="2.5"/>
    </filter>
    <g>
      <circle id="shadow" style="fill:rgba(0,0,0,0.1)" cx="97" cy="100" r="87" filter="url(#innerShadow)"></circle>
      <circle id="circle" style="stroke:<?php echo $light; ?>; stroke-width: 12px; fill:<?php echo $dark; ?>" cx="100" cy="100" r="94"></circle>
    </g>
    <g>
    <line x1="100" y1="100" x2="100" y2="55" transform="rotate(80 100 100)" style="stroke-width: 3px; stroke:<?php echo $needle; ?>;" id="hourhand">
        <animatetransform attributeName="transform"
                  attributeType="XML"
                  type="rotate"
                  dur="43200s"
                  repeatCount="indefinite"/>
    </line>
    <line x1="100" y1="100" x2="100" y2="40" style="stroke-width: 4px; stroke:<?php echo $needle; ?>;" id="minutehand">
        <animatetransform attributeName="transform"
                  attributeType="XML"
                  type="rotate"
                  dur="3600s"
                  repeatCount="indefinite"/>
    </line>
    <line x1="100" y1="100" x2="100" y2="30" style="stroke-width: 2px; stroke:<?php echo $needle; ?>;" id="secondhand">
        <animatetransform attributeName="transform"
                  attributeType="XML"
                  type="rotate"
                  dur="60s"
                  repeatCount="indefinite"/>
    </line>
    </g>
    <circle id="center" style="fill:<?php echo $dark; ?>; stroke:<?php echo $needle; ?>; stroke-width: 4px;" cx="100" cy="100" r="6"></circle>
<script>
var hands = [];
hands.push(document.querySelector('#secondhand > *'));
hands.push(document.querySelector('#minutehand > *'));
hands.push(document.querySelector('#hourhand > *'));
var cx = 100;
var cy = 100;
function shifter(val)
{
  return [val, cx, cy].join(' ');
}
var date = new Date();
var hoursAngle = 360 * date.getHours() / 12 + date.getMinutes() / 2;
var minuteAngle = 360 * date.getMinutes() / 60;
var secAngle = 360 * date.getSeconds() / 60;
hands[0].setAttribute('from', shifter(secAngle));
hands[0].setAttribute('to', shifter(secAngle + 360));
hands[1].setAttribute('from', shifter(minuteAngle));
hands[1].setAttribute('to', shifter(minuteAngle + 360));
hands[2].setAttribute('from', shifter(hoursAngle));
hands[2].setAttribute('to', shifter(hoursAngle + 360));
for(var i = 1; i <= 12; i++)
{
  var el = document.createElementNS('http://www.w3.org/2000/svg', 'line');
  el.setAttribute('x1', '100');
  el.setAttribute('y1', '30');
  el.setAttribute('x2', '100');
  el.setAttribute('y2', '40');
  el.setAttribute('transform', 'rotate(' + (i*360/12) + ' 100 100)');
  el.setAttribute('style', 'stroke:<?php echo $needle; ?>;');
  document.querySelector('svg').appendChild(el);
}
</script>
</svg>
</body>
</html>
