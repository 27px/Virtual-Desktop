function _(id)
{
  return document.getElementById(id);
}
function setMessage(type,message)
{
  var x=document.createElement("P");
  x.setAttribute("class",type);
  x.innerHTML=message;
  var b=document.body;
  b.insertBefore(x,b.firstChild);
}
function parseLatestApps()
{
  ajax("latestApps.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function parseCategories()
{
  _("getcategory").value="true";
  _("menuForm").submit();
}
function getAppsByCategory(cat)
{
  _("getcategory").value=cat;
  _("menuForm").submit();
}
function ajax(url,callback)
{
  var x;
	if(window.XMLHttpRequest)
	{
		x=new XMLHttpRequest();
	}
	else
	{
	  x=new ActiveXObject("Microsoft.XMLHTTP");
	}
	x.onreadystatechange=callback;
	x.open("POST",url,true);
	x.send();
}
function installApp(app,button)
{
  ajax("installApp.php?id="+app,function(){
    if(this.readyState==4 && this.status==200)
    {
      var x=JSON.parse(this.responseText);
      console.log(x);
      if(x.type=="success")
      {
        if(x.message=="Installed")
        {
          button.classList.remove("install");
          button.classList.add("uninstall");
          button.innerHTML="Uninstall";
          button.setAttribute("onclick","uninstallApp("+app+",this);");
        }
        else if(x.message=="Updated")
        {
          button.parentNode.removeChild(button);
        }
        setMessage(x.type,x.message);
      }
    }
  });
}
function uninstallApp(app,button)
{
  ajax("uninstallApp.php?id="+app,function(){
    if(this.readyState==4 && this.status==200)
    {
      var x=JSON.parse(this.responseText);
      if(x.type=="success")
      {
        button.classList.remove("uninstall");
        button.classList.add("install");
        button.innerHTML="Install";
        button.setAttribute("onclick","installApp("+app+",this);");
        setMessage(x.type,x.message);
      }
    }
  });
  // _("uninstall").value=app;
  // _("menuForm").submit();
}
function showInstalledApps()
{
  _("myApps").value="true";
  _("menuForm").submit();
}
function byte_unit_convert(b)
{
  var Size="";
  var byteA=Array(" B"," KB"," MB"," GB"," TB"," PB"," EB"," ZB"," YB");
  var bi=0;
  if(b<1024)
  Size=b;
  while(b>=1024 && bi<8)
  {
    bi++;
    b/=1024;
    Size=b;
  }
  Size=Math.floor(Size*100)/100;
  if(bi<9)
  {
    Size+=byteA[bi];
  }
  return Size;
}
function registerApp()
{
  _("regApp").value="true";
  _("menuForm").submit();
}
function openUploadPopup()
{
  _("uploadpopup").style.display="block";
  _("uploadpopupbg").style.display="block";
}
function closeUploadPopup()
{
  _("uploadpopup").style.display="none";
  _("uploadpopupbg").style.display="none";
  _("resetAppBuild").click();
}
function validateNewApp()
{
  var newName=_("appname").value;
  if(newName=="")
  {
    setMessage("error","Enter the App Name. . .");
    return;
  }
  var invalidChars=['\\','/',':','*','?','\"','<','>','|'];
  var n=invalidChars.length,i=0;
  while(i<n)
  {
    if(newName.indexOf(invalidChars[i])>-1)
    {
      setMessage("error","Invalid Character Found in the App Name. . .");
      return;
    }
    ++i;
  }
  if((_("description").value=="") || (_("keyword1").value=="") || (_("keyword2").value=="") || (_("keyword3").value==""))
  {
    setMessage("warning","Adding Description & Keyword helps users find your App easily.");
    setTimeout(function(){if(confirm("Are you Sure you want to leave these Fields Empty ?"))_("build").submit();else return;},3000);
  }
  else
  {
    _("build").submit();
  }
}
function avail(event)
{
  if(event.keyCode=="13")//Enter Submit Validation
  {
    event.preventDefault();
    checkAvailability();
  }
}
function checkAvailability()
{
  var z=_("appregname").value;
  if(z=="")
  {
    setMessage("error","Enter the App Name.");
    return;
  }
  var invalidChars=['\\','/',':','*','?','\"','<','>','|'];
  var n=invalidChars.length,i=0;
  while(i<n)
  {
    if(z.indexOf(invalidChars[i])>-1)
    {
      setMessage("error","Invalid Character Found in the App Name. . .");
      return;
    }
    ++i;
  }
  _("xbuild").submit();
}
function ownApps()
{
  _("getOwnApps").value="true";
  _("menuForm").submit();
}
function checkforUpdates()
{
  _("getUpdates").value="true";
  _("menuForm").submit();
}
function requestedApps()
{
  _("requestedApp").value="true";
  _("managerForm").submit();
}
function approvedApps()
{
  _("approvedApps").value="true";
  _("managerForm").submit();
}
function appsInDevelopment()
{
  _("appsInDevelopment").value="true";
  _("managerForm").submit();
}
