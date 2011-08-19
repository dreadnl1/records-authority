/**
*
* University of Denver - Penrose Library
* checks if file type is supported. 
* invoked:
* onBlur='checkExtension(document.survey[questionFieldName].value, spanId)'
*
* @param: input form value
  @param: span id
*
* TODO: generate this file using php in order to generate list of allowed file types dynamically.
*/
function checkExtension(file, id) {
  if (!file.match(/(\.pdf|\.docx|\.doc|\.txt|\.gif|\.jpg|\.jpeg|\.vsd|\.xls|\.xlsx|\.ppt|\.pptx|\.vdx|\.tiff|\.tiff)$/i)) {
	  document.getElementById(id).innerHTML = "This file type is not supported.";
  } else {
	 document.getElementById(id).innerHTML = "";
  } 	  
  return false;
} 