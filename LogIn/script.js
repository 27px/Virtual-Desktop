function _(id)
{
  return document.getElementById(id);
}
function togglePassword(a)
{
  if(_("password").getAttribute('type')=="password")
  {
    a.style.backgroundImage="url('Images/unlocked.svg')";
    _("password").setAttribute("type","text");
  }
  else
  {
    a.style.backgroundImage="url('Images/locked.svg')";
    _("password").setAttribute("type","password");
  }
}
function changeImage(x)
{
  if(x=="")
  {
    return;
  }
  var a='../User/Profile/'+x+'.jpg';
  _("image").style.backgroundImage="url('"+a+"')";
}
function setMessage(m,bx,color)
{
  var a=_(bx).getBoundingClientRect();
  var e=_("err");
  e.style.top=a.top;
  e.style.left=a.left;
  e.innerHTML=m+"<span class='x' onclick='this.parentNode.style.display=\"none\";'>&#10006;</span>";
  e.classList.remove("red","yellow","green");
  e.classList.add(color);
  e.style.display="inline-block";
}
function viewHoverOn()
{
  if(_('password').type=="password")
  {
    setMessage("Show Password",'viewable','green');
  }
  else
  {
    setMessage("Hide Password",'viewable','green');
  }
}
function viewHoverOff()
{
  _('err').style.display="none";
}
function login()
{
  var u=_("user");
  var p=_("password");
  if(u.value=="")
  {
    setMessage("Enter User Id !","user","red");
    u.focus();
    return;
  }
  else if(p.value=="")
  {
    setMessage("Enter Password !","password","red");
    p.focus();
    return;
  }
  else
  {
    if(_("chb").getAttribute("checked")=="yes")
    {
      _("remember").value="yes";
    }
    _('lg').value="login";
    _('f1').submit();
  }
}
function checkb(a)
{
  if(a.getAttribute('checked')=="no")
  {
    a.innerHTML="&#10004;";
    a.setAttribute("checked","yes");
    setMessage("Cookies will be used.",a.getAttribute('id'),"yellow");
  }
  else
  {
    a.innerHTML="";
    a.setAttribute("checked","no");
    _('err').style.display="none";
  }
}
function checkkey(a,e)
{
  if(e.keyCode==13 || e.keyCode==32)
  {
    checkb(a);
  }
}
function checksub(e)
{
  if(e.keyCode==13 || e.keyCode==32)
  {
    login();
  }
}
function checkhome(e)
{
  if(e.keyCode==13 || e.keyCode==32)
  {
    window.location="../Home/index.php";
  }
}
function checkfp(e,url)
{
  if(e.keyCode==13 || e.keyCode==32)
  {
    window.location=url;
  }
}
function checkreset(e)
{
  if(e.keyCode==13 || e.keyCode==32)
  {
    reset();
  }
}
function reset()
{
  _('user').value="";
  _('password').value="";
  _('chb').innerHTML="";
  _('chb').setAttribute("checked","no");
  _('password').setAttribute("type","password");
  _('viewable').style.backgroundImage="url('Images/locked.svg')";
}
