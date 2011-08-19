$(function() {
	//  Developed by Roshan Bhattarai 
	//  Visit http://roshanbh.com.np for this script and more.
	//  This notice MUST stay intact for legal use 
	//these two line adds the color to each different row
	$("#searchResultsTable tr:even").addClass("eventr");;
	$("#searchResultsTable tr:odd").addClass("oddtr");;
	//handle the mouseover , mouseout and click event
	$("#searchResultsTable tr").mouseover(function() {$(this).addClass("trover");}).mouseout(function() {$(this).removeClass("trover");}).click(function() {$(this).toggleClass("trclick");}); 
});