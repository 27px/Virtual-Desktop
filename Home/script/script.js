function _(id)
{
  return document.getElementById(id);
}
function pf(f)
{
  return parseFloat(f);
}
function gotoURL(code)
{
  var url="";
  if(code=="Log Out")
  {
    var f=document.createElement("FORM");
    f.setAttribute("method","POST");
    f.setAttribute("action","<?php echo $_SERVER['PHP_SELF']; ?>");
    document.body.appendChild(f);
    var x=document.createElement("INPUT");
    x.setAttribute("type","hidden");
    x.setAttribute("name","LogOut");
    x.setAttribute("value","true");
    f.appendChild(x);
    f.submit();
    return;
  }
  else if(code=="Log In")
  {
    url="../LogIn/index.php";
  }
  else if(code=="Sign Up")
  {
    url="../New/index.php";
  }
  else if(code=="About Us")
  {
    url="../AboutUs/index.php";
  }
  else if(code=="Cloud Storage")
  {
    url="../Desktop/index.php";
  }
  if(url!="")
  {
    window.location=url;
  }
}
function sendMessage(x)
{
  x.classList.add('activatedbutton');
  if(_("name").value=="")
  {
    _("name").value=prompt("Enter your Name.");
    if(_("name").value==null || _("name").value=="")
    {
      x.classList.remove('activatedbutton');
      return;
    }
  }
  if(_("email").value=="")
  {
    _("email").value=prompt("Enter your Email ID.");
    if(_("email").value==null || _("email").value=="")
    {
      x.classList.remove('activatedbutton');
      return;
    }
  }
  if(_("message").value=="")
  {
    _("message").value=prompt("Enter the Message.");
    if(_("message").value==null || _("message").value=="")
    {
      x.classList.remove('activatedbutton');
      return;
    }
  }
  _("messageForm").submit();
}
function parallax()
{
  var b=document.body;
  var s=b.scrollTop;
  s=(s<=0)?0:s;
  var pc=_("parallaxcontainer").getBoundingClientRect()["height"];
  if(s<=pc)
  {
    var xp=s/pc;
    xp=1-(xp.toFixed(2));
    xp=(xp<0.75)?xp-0.3:xp;
    xp=(xp<=0.1)?0:xp;
    var p=document.getElementsByClassName("parallax"),i=0,n=p.length,x;
    for(i=0,x=0.1;i<n;i++,x+=0.2)
    {
      if(i==(n-1))
      {
        x*=0.9;
      }
      p[i].style.transform="translateY(-"+(s.toFixed(2)*x)+"px)";
    }
    _("main").style.transform="translateY("+(s.toFixed(2)/(x*1.5))+"px)";
    _("main").style.opacity=xp;
  }
}
function autoScroll()
{
  var pc=_("parallaxcontainer").getBoundingClientRect()["height"];
  var x=setInterval(function(){
    if(document.body.scrollTop<=pc)
    document.body.scrollTop=pf(document.body.scrollTop)+1;
    else
    {
      clearTimeout(x);
    }
  },1);
}
function toggleMenu(list)
{
  if(list.contains("collapse"))
  {
    list.remove("collapse");
    list.add("expand");
    document.body.scrollTop=0;
  }
  else
  {
    list.remove("expand");
    list.add("collapse");
  }
}
