function addEvent(elm, evType, fn, useCapture)
{
if(elm.addEventListener)
  {
  elm.addEventListener(evType, fn, useCapture);
  return true;
  }
else if (elm.attachEvent)
  {
  var r = elm.attachEvent('on' + evType, fn);
  return r;
  }
else
  {
  elm['on' + evType] = fn;
  }
}
// removes the square border that IE
// insists on adding to checkboxes and radio
function removeCheckBoxBorders()
{
var el = document.getElementsByTagName("input");
for (i=0;i<el.length;i++)
  {
  var type = el[i].getAttribute("type");
  if((type=="checkbox")||(type=="radio"))
    {
   el[i].style.border = "none";
    }
  }
}
addEvent(window, 'load', removeCheckBoxBorders, false);