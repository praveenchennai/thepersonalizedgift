
  
 String.prototype.trim = function() {
	a = this.replace(/^\s+/, '');
	return a.replace(/\s+$/, '');
};

function agreeTerms(left_div,class_name)
{
	document.getElementById(left_div).className = class_name;
		
}
  //functions for default texts
function changeDefault(fld_value,field_name,default_val,default_class,null_class)
{ 

	if(fld_value == default_val) {
		document.getElementById(field_name).value=''
		document.getElementById(field_name).className = default_class;
	}
	//document.getElementById(field_name).className = null_class;
}

function checkDefault(fld_value,field_name,default_val,null_class)
{
	if(fld_value == '')
		{
			document.getElementById(field_name).value= default_val;
			  
			document.getElementById(field_name).className = null_class;
		}
}


function showTips(hint_holder,hint_hint,hint_error,main_div)
{
	
    document.getElementById(hint_holder).style.display = 'block';
	document.getElementById(hint_hint).style.display = 'block';
	document.getElementById(hint_error).style.display = 'none';
	document.getElementById(main_div).className = 'form_wrap';
	
}

function hideTips(div_name)
{
    //alert("hi");
	document.getElementById(div_name).style.display = 'none';
}

function setDropdownDefault(left_div,error_div,class_name)
{
	document.getElementById(left_div).className=class_name;
	document.getElementById(error_div).style.display = 'none';
}
