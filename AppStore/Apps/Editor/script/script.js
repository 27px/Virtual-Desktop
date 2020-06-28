var ignoreScrollEvents=false;
var tabCharacter="  ";//Spaces or \t tab
// sessionStorage.setItem("tabCharacter",2);
if(typeof(Storage)!="Undefined")
{
  var tCn=pi(sessionStorage.getItem("tabCharacter"));
  if(tCn==0)
  {
    tabCharacter="\t";
  }
  else
  {
    if(tCn>0)
    {
      tabCharacter="";
    }
    for(var i=0;i<tCn;i++)
    {
      tabCharacter+=" ";//Single Space
    }
  }
}
var UnsupportedExt=["jpg","jpeg","png","gif","bmp","svgz","tiff","exif","ico","zip","rar","docx","doc","xls","xlsx","ppt","pptx","pdf","psd","ai"];
function _(id)
{
  return document.getElementById(id);
}
function pi(i)
{
  return parseInt(i);
}
function pf(f)
{
  return parseFloat(f);
}
function expandDirectoryViewer()
{
  var d=_("dircontainer").classList;
  d.add("expanddir");
  if(typeof(Storage)!="Undefined")
  {
    sessionStorage.setItem("dircontainer","expanddir");
  }
}
function collapseDirectoryViewer()
{
  var d=_("dircontainer").classList;
  d.remove("expanddir");
  if(typeof(Storage)!="Undefined")
  {
    sessionStorage.setItem("dircontainer","collapsedir");
  }
}
function toggleDirectoryViewer()
{
  if(_("dircontainer").classList.contains("expanddir"))
  {
    collapseDirectoryViewer();
  }
  else
  {
    expandDirectoryViewer();
  }
}
function togglePreview()
{
  if(_("previewcontainer").classList.contains("expandpreview"))
  {
    collapsePreview();
  }
  else
  {
    expandPreview();
  }
}
function collapsePreview()
{
  d=_("previewcontainer").classList;
  d.remove("expandpreview");
  if(typeof(Storage)!="Undefined")
  {
    sessionStorage.setItem("previewcontainer","collapsepreview");
  }
}
function expandPreview()
{
  var d=_("previewcontainer").classList;
  d.add("expandpreview");
  if(typeof(Storage)!="Undefined")
  {
    sessionStorage.setItem("previewcontainer","expandpreview");
  }
}
function adjustUI()
{
  if(typeof(Storage)!="Undefined")
  {
    var x=sessionStorage.getItem("dircontainer");
    if(x=="expanddir")
    {
      expandDirectoryViewer();
    }
    else
    {
      collapseDirectoryViewer();
    }
    var y=sessionStorage.getItem("previewcontainer");
    if(y=="expandpreview")
    {
      expandPreview();
    }
    else
    {
      collapsePreview();
    }
  }
}
function loadDirectoryStructure()
{
  var ds=_("dirstructure");
  ds.innerHTML="<div class='loading'></div>";
  var x;
  if(window.XMLHttpRequest)
  {
    x=new XMLHttpRequest();
  }
  else
  {
    x=new ActiveXObject("Microsoft.XMLHTTP");
  }
  x.onreadystatechange=function(){
    if(this.readyState==4)
    {
      if(this.status==200)
      {
        _('dirstructure').innerHTML=this.responseText;
      }
      else if(this.status==403)
      {
        ds.innerHTML="<div class='accessdenied'></div>";
      }
      else
      {
        ds.innerHTML="<div class='errorloading'></div>";
      }
    }
  };
  x.open("POST","functions/getContentsFromDirectory.php");
  x.send();
}
function togglefolder(event,x)
{
  event.stopPropagation();
  x.parentNode.parentNode.classList.toggle("showdircontents");
}
function keypressed(event,txt)
{
	if(event.keyCode==16 || event.which==16)
	{
    setKey("shift","on");
	}
	if(event.keyCode==17 || event.which==17)
	{
    setKey("ctrl","on");
	}
	if(event.keyCode==18 || event.which==18)
	{
    event.preventDefault();
    setKey("alt","on");
	}
	if(event.keyCode==83 || event.which==83)
	{
		if(getKey("ctrl")=="on")
		{
      event.preventDefault();
      var x;
      if(window.XMLHttpRequest)
      {
        x=new XMLHttpRequest();
      }
      else
      {
        x=new ActiveXObject("Microsoft.XMLHTTP");
      }
      x.onreadystatechange=function(){
        if(this.readyState==4)
        {
          if(this.status==200)
          {
            var r=this.responseText;
            r=JSON.parse(r);
            if(r.type=="success")
            {
              setMessage(r.type,r.message)
            }
            else
            {
              setMessage(r.type,r.message)
            }
            keychanged(0);
          }
          else
          {
            setMessage("error","Unknown error")
          }
        }
      };
      var f=new FormData();
      f.append("content",_("txt").value);
      x.open("POST","functions/saveFile.php?url="+_("txt").getAttribute("openedFilePath"));
      x.send(f);
		}
	}
	if((event.keyCode==219 || event.which==219) && (getKey("shift")=="on"))
	{
		event.preventDefault();
		var txt=_("txt");
		var to=0,start=txt.selectionStart,end=txt.selectionEnd,p="";
		if(txt.value.substring(start-1,start)!="\n")
		{
			p="\n{\n"+tabCharacter+"\n}";
			to=start+3+(tabCharacter.length);
		}
		else
		{
			p="{\n"+tabCharacter+"\n}";
			to=start+2+(tabCharacter.length);
		}
		// if(txt.value.substring(end,end+1)!="\n")
		// {
		// 	p+="\n";//Add new line after autocomplete
		// }
		txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
		txt.selectionStart=to;
		txt.selectionEnd=to;
	}
	if(event.keyCode==9 || event.which==9)
	{
		event.preventDefault();
		cursor=txt.selectionStart;
		if(getKey("shift")=="off")
		{
			txt.value=txt.value.substring(0,txt.selectionStart)+"  "/* space instead of tab */+txt.value.substring(txt.selectionEnd,txt.value.length);//2 Space instead of Tab
			txt.selectionEnd=cursor+2/* no. of space */;
		}
		else if(getKey("shift")=="on")
		{
			if(txt.value.substring(txt.selectionStart-1,txt.selectionStart)=="\t")//actual tab
			{
				txt.value=txt.value.substring(0,txt.selectionStart-1)+txt.value.substring(txt.selectionEnd,txt.value.length);
				txt.selectionEnd=cursor-1;
			}
      else if(txt.value.substring(txt.selectionStart-(tabCharacter.length),txt.selectionStart)==tabCharacter)//2 space
      {
				txt.value=txt.value.substring(0,txt.selectionStart-(tabCharacter.length))+txt.value.substring(txt.selectionEnd,txt.value.length);
				txt.selectionEnd=cursor-(tabCharacter.length);
      }
		}
	}
	if(event.keyCode==70 && getKey("alt")=="on" && getKey("ctrl")=="on")//Ctrl+Alt+F
  {
    _("menuFile").focus();
  }
	if(event.keyCode==69 && getKey("alt")=="on" && getKey("ctrl")=="on")//Ctrl+Alt+E
  {
    _("menuEdit").focus();
  }
	if(event.keyCode==86 && getKey("alt")=="on" && getKey("ctrl")=="on")//Ctrl+Alt+V
  {
    _("menuView").focus();
  }
	if(event.keyCode==73 && getKey("alt")=="on" && getKey("ctrl")=="on")//Ctrl+Alt+I
  {
    _("menuInsert").focus();
  }
  keychanged(0);
}
function scrollAll(e,event)
{
	if(ignoreScrollEvents==true)
	{
		ignoreScrollEvents=false;
		return;
	}
	else
	{
		ignoreScrollEvents=true;
		_('num').scrollTop=e.scrollTop;
	}
}
function release(event)
{
	if(event.keyCode==16 || event.which==16)
	setKey("shift","off");
	else if(event.keyCode==17 || event.which==17)
	setKey("ctrl","off");
	else if(event.keyCode==18 || event.which==18)
	setKey("alt","off");
}
function keychanged(s)
{
	var text=_("txt").value;
	var tlines=text.split("\n");
	var tcount=tlines.length;
	var n=_("num").value;
	var nlines=n.split("\n");
	var ncount=nlines.length;
	var nlast=nlines[ncount-2];
	var cn=0;
  var i=0,c=1;
	if(nlast>tcount)
	{
		_("num").value="";
		for(i=0;i<tcount;)
		{
			_("num").value+=(++i)+"\n";
		}
	}
	else
	{
		for(i=nlast;i<tcount;)
		{
			_("num").value+=(++i)+"\n";
		}
	}
  c=i.toString().length;
  c=(c<1)?1:c;
  _("num").cols=c;
  updateStatus();
	updatePreview();
}
function updatePreview()
{
  var txt=_("txt");
  var fileType=txt.getAttribute("openedFileType");
  if(fileType=="")
  {
    return;
  }
  fileType=fileType.toLowerCase();
  var axtemp=encodeURIComponent(txt.value);
  var pre=_("pre");
  if(fileType=="html")
  {
    pre.src='data:text/html;charset=utf-8,'+axtemp;
  }
  else if(fileType=="svg")
  {
    pre.src='data:image/svg+xml;charset=utf-8,'+axtemp;
  }
  else if(fileType=="txt")
  {
    pre.src='data:text/plain;charset=utf-8,'+axtemp;
  }
  else if(fileType=="")
  {
    pre.src='data:text/html;charset=utf-8,<html><body style=\"color:rgb(255,255,255);font-size:18px;text-align:center;padding-top:45px;padding-left:20px;padding-right:20px;\">No file opened.<hr style=\"border-color:rgba(255,255,255,0.2);width:20%;\"></body></html>';
  }
  else
  {
    pre.src='data:text/html;charset=utf-8,<html><body style=\"color:rgb(255,255,255);font-size:18px;text-align:center;padding-top:45px;padding-left:20px;padding-right:20px;\">No preview available.<hr style=\"border-color:rgba(255,255,255,0.2);width:20%;\"></body></html>';
  }
}
function insertSpanTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+15;
  var p="<span class=\"\"></span>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  updatePreview();
}
function insertInput()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+13;
  var p="<input type=\"text\" value=\"\" placeholder=\"\" class=\"\" />";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+4;
  updatePreview();
}
function insertButton()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+42;
  var p="<button type=\"button\" onclick=\"\" class=\"\">Button</button>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+6;
  updatePreview();
}
function insertTextarea()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+34;
  var p="<textarea placeholder=\"\" class=\"\"></textarea>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  updatePreview();
}
function insertDivTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+14;
  var p="<div class=\"\"></div>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  updatePreview();
}
function insertPTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+12;
  var p="<p class=\"\"></p>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  updatePreview();
}
function insertAnchorTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+9;
  var p="<a href=\"url\" target=\"_blank\" class=\"\">Link</a>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+3;
  updatePreview();
}
function insertImageTag()
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+10;
  var p="<img src=\"url\" alt=\"\" class=\"\"/>";
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to+3;
  updatePreview();
}
function insertColor(p)
{
  var txt=_("txt");
  var start=txt.selectionStart,end=txt.selectionEnd,to=start+7;
  txt.value=txt.value.substring(0,start)+p+txt.value.substring(end,txt.value.length);
  txt.focus();
  txt.selectionStart=to;
  txt.selectionEnd=to;
  updatePreview();
}
function scrollN()
{
	if(ignoreScrollEvents==true)
	{
		ignoreScrollEvents=false;
		return;
	}
	else
	{
		ignoreScrollEvents=true;
		if(_('num').scrollTop>=_('txt').scrollTop)
		{
			_('txt').scrollTop=_('num').scrollTop;
			_('num').scrollTop=_('txt').scrollTop;
		}
		else
		{
			_('txt').scrollTop=_('num').scrollTop;
		}
	}
}
function setCursor(pos)
{
	_('txt').selectionStart=parseInt(pos);
}
function setMessage(type,message)
{
  var x=document.createElement("P");
  x.setAttribute("class",type);
  x.innerHTML=message;
  var b=document.body;
  b.insertBefore(x,b.firstChild);
  setTimeout(function(){
    x.parentNode.removeChild(x);
  },3000);
}
function fontSize(operator)
{
	var size=16;
	if(typeof(Storage) !== "undefined")
	{
		if(sessionStorage.fontSize)
		{
			size=parseInt(sessionStorage.fontSize);
			if(isNaN(size))
			{
				return;
			}
			if(operator=="+")
			{
				size++;
			}
			else if(operator=="-")
			{
				size--;
			}
			else
			{
				size=16;
			}
			if(size<10)
			{
				alert("Minimum Font size reached");
				size=10;
			}
			else if(size>45)
			{
				alert("Maximum Font size reached");
				size=45;
			}
			sessionStorage.fontSize=size;
		}
		else
		{
			sessionStorage.fontSize=16;
		}
		size=sessionStorage.fontSize;
	}
	_("txt").style.fontSize=size+"px";
	_("num").style.fontSize=size+"px";
}
function updateStatus()
{
  var fsb=parseInt(_('txt').value.length);
  var Size="";
  var byteA=[" B"," KB"," MB"," GB"," TB"," PB"," EB"," ZB"," YB"];
  var bi=0;
  if(fsb<=1024)
  {
    Size=fsb;
  }
  while(fsb>1024)
  {
    bi++;
    fsb/=1024;
    Size=fsb.toFixed(2);
  }
  if(bi<9)
  {
    Size+=byteA[bi];
  }
  else
  {
    Size="Too Large";
  }
  _('fsize').innerHTML=Size;
  _('fsizeb').innerHTML=_('txt').value.length+" B";
}
function openfile(url)
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
  x.onreadystatechange=function(){
    if(this.readyState==4)
    {
      if(this.status==200)
      {
        var r=this.responseText;
        r=JSON.parse(r);
        if(r.type=="success")
        {
          _("txt").setAttribute("openedFilePath",url);
          _("txt").innerHTML=r.message;
          _("fname").innerHTML=r.filename;
          _("fpath").innerHTML=r.filepath;
          _("ftype").innerHTML=r.filetype;
          _("fext").innerHTML=r.fileext;
          _("fmd").innerHTML=r.filemodifieddate;
          _("fpropertyread").innerHTML=(r.filepropertyread=="r")?"Allowed":"Denied";
          _("fpropertywrite").innerHTML=(r.filepropertywrite=="w")?"Allowed":"Denied";
          _("fpropertyexecute").innerHTML=(r.filepropertyexecute=="x")?"Yes":"No";
          _("txt").setAttribute("openedFileType",r.fileext);
          document.title="Editor : "+r.filename;
          collapseDirectoryViewer();
          expandPreview();
        }
        else
        {
          setMessage(r.type,r.message)
        }
        keychanged(0);
      }
      else
      {
        setMessage("error","Unknown error");
      }
    }
  };
  x.open("POST","functions/readFile.php?url="+url);
  x.send();
}
function adjustNumberLine()
{
  _("num").innerHTML="1\n";
}
function setKey(key,value)
{
  document.body.setAttribute(key+"-key",value);
}
function getKey(key)
{
  return document.body.getAttribute(key+"-key");
}
function outOfRange()
{
  var b=document.body.classList;
  b.add("outofrange");
}
function inRange()
{
  var b=document.body.classList;
  b.remove("outofrange");
}
