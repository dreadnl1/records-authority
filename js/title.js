var myTitle = (document.title);
var goodTitle = myTitle.indexOf("<br/>");
if(goodTitle == -1) {
} else {
myTitle = myTitle.substr(0,goodTitle)
}
document.title = myTitle;