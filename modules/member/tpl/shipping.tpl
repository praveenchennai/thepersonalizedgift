<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
function pagesub()
	{
	if(chk(document.frmReg))
		{
		//document.frmReg.state.value=document.
		if(document.getElementById('new_state').type=='text')
			{
			//alert(document.getElementById('new_state').value);
			state=document.getElementById('new_state').value;
			}
		else 
			{
			//alert(document.getElementById('new_state').options[document.getElementById('new_state').selectedIndex].value);
			state=document.getElementById('new_state').options[document.getElementById('new_state').selectedIndex].value;
			}
		document.frmReg.state.value=state;
		{/literal}
		document.frmReg.action='{makeLink mod=member pg=register}act=shipping_det{/makeLink}';
		{literal}
		document.frmReg.submit();
		}
	}
function show_state(opt_name,country_id,state_name) {
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name+"&classname=ajaxinput";
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}");
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	document.getElementById('div_state').innerHTML=_var;
	}

</SCRIPT>
{/literal}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	var fields=new Array('fname', 'lname', 'address1', 'postalcode', 'country' );
	var msgs=new Array('Shipping First Name','Shipping Last Name','Shipping Address','Shipping Zip/Postal Code','Shipping Country');
	
	
	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
	
</SCRIPT>
{/literal}
<form name="frmReg" id="frmReg" method="post" action="" onSubmit="return chk(this);"> 
<div style="width:700px; padding: 5px; margin: 0px auto;">
	<div class="greyboldext">{$MOD_VARIABLES.MOD_HEADS.HD_SHIP_DETAILS}</div>
	<br>
    <div><div class="hrline"></div><strong>{$MOD_VARIABLES.MOD_COMM.COMM_CMN_REQ_FIELD}</strong></div>
	<div align="center"><span class="bodytext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}&nbsp; </strong></span></div>
    <div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_FIRST_NAME} *</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="fname" type="text" class="input" id="fname" value="{$BILLING_ADDRESS.fname}" size="30"/></span>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_LAST_NAME} *</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="lname" type="text" class="input" id="lname" value="{$BILLING_ADDRESS.lname}" size="30"/></span>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_STREET_ADDRESS} *</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span>
	  <input name="address1" type="text" class="input" id="address1" value="{$BILLING_ADDRESS.address1}" size="30" /></span>
	</div><br>
	<div class="bodytext">
		<span>&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="address2" type="text" class="input" id="address2" value="{$BILLING_ADDRESS.address2}" size="30" /></span>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_CITY}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="city" type="text" class="input" id="city" value="{$BILLING_ADDRESS.city}" size="30" /></span>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_ZIP} *</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="postalcode" type="text" class="input" id="postalcode" value="{$BILLING_ADDRESS.postalcode}" size="30" /></span>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_COUNTRY} *</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span>
			<select name="country" class="input" id="country" style="width:165px " onChange="javascript: show_state('new_state',this.value,'');">
    	    	<option value="">---{$MOD_VARIABLES.MOD_HEADS.HD_SELECT_COUNTRY_DROP}---</option>
        	    	{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$BILLING_ADDRESS.country}
    		</select>
		</span>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_STATE_PROVINCE}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><div id="div_state" class="bodytext" style="display:inline"><input name="state" type="text" class="input" id="state" value="{$BILLING_ADDRESS.state}" size="30" /></div></span>
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_state('new_state',{$BILLING_ADDRESS.country},'{$BILLING_ADDRESS.state}');</SCRIPT>
	</div><br>
	<div class="bodytext">
		<span>{$MOD_VARIABLES.MOD_LABELS.LBL_PHONE}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="telephone" type="text" class="input" id="telephone" value="{$BILLING_ADDRESS.telephone}" size="30" /></span>
	</div><br>
	<!--
	<div class="bodytext">
		<span>Mobile:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;
		<span><input name="mobile" type="text" class="input" id="mobile" value="{$BILLING_ADDRESS.mobile}" size="30" /></span>
	</div><br>-->
	<div class="bodytext">
		<!--<span><input type="submit" class="button_class" style="height:22;width:80" onClick="javascript:pagesub()" value="Submit" /></span>
		<span><input name="button" type="button" class="button_class" style="height:22;width:80" onClick="javascript: history.go(-1)" value="Cancel" /></span>-->
		<span><input type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg" onClick="javascript:pagesub()"></span>
	    <span><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" onclick="javascript: history.go(-1)" border="0"></span>
	</div><br>
	<input type="hidden" name="state" />
</div>
</form>