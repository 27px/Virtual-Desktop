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
  ajax("getCategoryList.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function getAppsByCategory(cat)
{
  ajax("getCategory.php?getcategory="+cat,function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
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
      if(x.type=="success")
      {
        if(x.message=="Installed")
        {
          button.classList.remove("install");
          button.classList.remove("update");
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
}
function showInstalledApps()
{
  ajax("installedApps.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
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
  _("resultantcontainer").innerHTML="<form id='xbuild' class='build' method='POST' action=''><table class='full'><tr><td>App Name</td><td> : </td><td><input class='wp' type='name' name='appregname' id='appregname' required placeholder='App Name' autocomplete='off' value='' onkeypress='avail(event);'></td></tr><tr><td colspan='3'><div class='appNameStatus' id='appNameStatus'>Enter App Name to check availability.</div><button type='button' class='greenButton fr' onclick='checkAvailability();'>Check</button></td></tr></table></form>";
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
  registerApp();
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
  ajax("buildApp.php?appregname="+_("appregname").value,function(){
    if(this.readyState==4 && this.status==200)
    {
      var x=JSON.parse(this.responseText);
      setMessage(x.type,x.message);
      if(x.a!="")
      {
        _('appNameStatus').innerHTML=x.a;
      }
      if(x.x!="")
      {
        _('resultantcontainer').innerHTML=x.x.replace("\\\"","\"");
        openUploadPopup();
      }
    }
  });
}
function ownApps()
{
  ajax("myApps.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function checkforUpdates()
{
  ajax("showUpdates.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function search(key)
{
  if(key=="")
  {
    _('resultantcontainer').innerHTML="";
    return;
  }
  ajax("search.php?key="+key,function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function keysearch(e,key)
{
  if(e.keyCode=="13")
  {
    search(key);
  }
}
function requestedApps()
{
  ajax("requestedApp.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function approvedApps()
{
  ajax("approvedApps.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
function appsInDevelopment()
{
  ajax("appsInDevelopment.php",function(){
    if(this.readyState==4 && this.status==200)
    {
      _('resultantcontainer').innerHTML=this.responseText;
    }
  });
}
