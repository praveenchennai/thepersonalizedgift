function displayAppropriateHeader(theSelect) {	
	var headerChoice= theSelect;
	alert(headerChoice);
	switch(headerChoice) {
		case "0": {
			document.getElementById('ContentDisp').innerHTML=document.getElementById('hideHeader').innerHTML; 
			break;
		}
	    case "1": {
			document.getElementById('ContentDisp').innerHTML=document.getElementById('customHeader').innerHTML;
			
			break;
		}
		case "2": {
			
			break;
		}
	}
}