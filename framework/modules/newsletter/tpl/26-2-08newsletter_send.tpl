<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript">
function loadSub(reg_pack)
{
	//mem_type
	document.getElementById('sub_div').innerHTML="<strong class='lbClass'>Loading...</strong>";
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	
	{/literal}
	str="reg_pack="+reg_pack+"&selected={$smarty.request.sub_pack}";
	//alert(str)
	req1.open("POST", "{makeLink mod=member pg=ajax_store}act=sub_pack_news{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverRese(_var) {
	document.getElementById('sub_div').innerHTML=_var;
}

function sendMail(id) {
	document.sendFrm.sendBtn.disabled = true;
	document.sendFrm.sendBtn.value = 'Sending...';
	serverCall(id, 0);
}
function serverCall(id, page) {

	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, serverResponse);
	{/literal}
	req.open("GET", "{makeLink mod=newsletter pg=ajax}{/makeLink}&id="+id+"&page="+page);
	{literal}
	req.send(null);
}
function serverResponse(_var) {
	_var = _var.split('|');
	if(_var[1] > 0) {
		document.getElementById("log").innerHTML += _var[2] + '<br>';
		document.getElementById("percentage").style.width = (_var[1]/_var[3])*100+'%';
		serverCall(_var[0], _var[1]);
	} else {
		document.sendFrm.sendBtn.disabled = false;
		document.sendFrm.sendBtn.value = 'Check Sent Log';
		document.sendFrm.sendBtn.onclick = function () { window.location.href='{/literal}{makeLink mod=newsletter pg=log}act=list{/makeLink}{literal}'; }
		document.getElementById("log").innerHTML += 'Successfully finished sending newsletter<br>';
	}
	document.getElementById("log").scrollTop = document.getElementById("log").scrollHeight;
}

function test(){
if (document.sendFrm.list_id){
	if (document.sendFrm.list_id.value>0)
		document.sendFrm.all_users.disabled = "true"; 
	if (document.sendFrm.list_id.value == "")
		document.sendFrm.all_users.disabled = ""; 
}

if (document.sendFrm.all_users){
	if (document.sendFrm.all_users.checked){
		document.sendFrm.list_id.disabled = "true"; 
		document.sendFrm.confirmed[0].disabled = "true";
		document.sendFrm.confirmed[1].disabled = "true";
		document.sendFrm.confirmed[2].disabled = "true";
		document.sendFrm.reg_pack.disabled = "";
		document.sendFrm.date_from.disabled = "";
		document.sendFrm.date_to.disabled = "";
		
	}else{
		document.sendFrm.list_id.disabled = "";
		document.sendFrm.confirmed[0].disabled = "";
		document.sendFrm.confirmed[1].disabled = "";
		document.sendFrm.confirmed[2].disabled = "";
		document.sendFrm.reg_pack.disabled = "true";
		document.sendFrm.date_from.disabled = "true";
		document.sendFrm.date_to.disabled = "true";
		document.sendFrm.sub_pack.style.display = "none";  
 
	}
}


}


</script>
{/literal}
<form method="POST" name="sendFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Send Newsletter</td> 
	  <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Newsletters</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
      <td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">Step {$STEP}/3 : {$STEP_MESSAGE.$STEP}</td> 
    </tr> 
	{if $STEP == 1}
    <tr class=naGrid2> 
      <td width="40%" align="right">Select Members From</td> 
      <td width="1%">:</td> 
      <td width="60%" valign="middle">&nbsp;Mailing List&nbsp;<select name="list_id" id="list_id" onchange="test()" {if $smarty.request.all_users eq '1'} '1'}'1'}'1'}'1'}'1'}'1'}'1'}"disabled=disabled" {/if} >
        <option value="">Select Mailing List</option>
        {html_options values=$MAILINGLIST.id output=$MAILINGLIST.name selected=$smarty.request.list_id}
        </select>
		&nbsp;&nbsp;&nbsp;Or&nbsp;&nbsp;&nbsp;Registered Members&nbsp;<input type="checkbox" name="all_users" value="1" size="40" {if $smarty.request.all_users eq '1'} checked{/if} {if $smarty.request.list_id gt 0} "disabled=disabled" {/if} onclick="test();">
		</td> 
    </tr>
	<tr class=naGrid1>
	  <td  width="40%" align="right">Registration Type </td>
	  <td>:</td>
	  <td> <select name="reg_pack" id="reg_pack" class="input" value="" onChange="return loadSub(this.value)" style="width:180px;" {if $smarty.request.all_users ne '1'} disabled{/if}>
        <option value="">Select a plan</option>
        
	     {html_options options=$REGPACKG selected=$smarty.request.reg_pack}
    
      </select></td>
	</tr>
	<tr class="naGrid2">
	  <td  width="40%" align="right">Subscription Type </td>
	  <td>:</td>
	  <td> <div id="sub_div"></div></td>
	</tr>
	<tr class="naGrid1">
	 <td  width="40%" align="right">Date Range </td>
	  <td>:</td>
	  <td> From:
                    <input type="text" name="date_from" value="{$DATE_FROM}" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" {if $smarty.request.all_users ne '1'} disabled{/if} />
                 To:
                 <input type="text" name="date_to" size="10" value="{$DATE_TO}" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" {if $smarty.request.all_users ne '1'} disabled{/if} /></td>
	</tr>
	<tr class=naGrid2>
	  <td  width="40%" align="right">Email Contains </td>
	  <td>:</td>
	  <td><input type="text" name="email" value="{$smarty.request.email}" size="40"></td>
	</tr>
	
	<tr class=naGrid1> 
      <td  width="40%" align="right" valign=top>Country </td> 
      <td width=1% valign=top>:</td> 
      <td width="59%">
	  <select name="country" class="input" id="country" style="width:195px " onChange="javascript: show_state('state',this.value,'');">
		<option value="">---Select a Country---</option>
			{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
	</select></td> 
    </tr>
   {if $MAILINGFORMAT eq 'B'}
	<tr class=naGrid2>
	  <td  width="40%" align="right">Format</td>
	  <td>:</td>
	  <td><table border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td><input name="format" type="radio" value="H" id="H"{if $smarty.request.format eq 'H'} checked{/if}></td>
		  <td><label for="H">HTML</label></td>
		  <td>&nbsp;</td>
		  <td><input name="format" type="radio" value="T" id="T"{if $smarty.request.format eq 'T'} checked{/if}></td>
		  <td><label for="T">Text</label></td>
		  <td>&nbsp;</td>
		  <td><input name="format" type="radio" value="" id="B"{if $smarty.request.format eq ''} checked{/if}></td>
		  <td><label for="B">Both</label></td>
		</tr>
	  </table></td>
	</tr>
	{/if}
	<tr class=naGrid1>
	  <td  width="40%" align="right">Confirmed</td>
	  <td>:</td>
	  <td><table border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td><input name="confirmed" type="radio" value="Y" id="Y"{if $smarty.request.confirmed eq 'Y'} checked{/if} {if $smarty.request.all_users eq '1'} '1'}'1'}'1'}'1'}'1'}'1'}'1'}"disabled=disabled" {/if}></td>
		  <td><label for="Y">Yes</label></td>
		  <td>&nbsp;</td>
		  <td><input name="confirmed" type="radio" value="N" id="N"{if $smarty.request.confirmed eq 'N'} checked{/if} {if $smarty.request.all_users eq '1'} '1'}'1'}'1'}'1'}'1'}'1'}'1'}"disabled=disabled" {/if}></td>
		  <td><label for="N">No</label></td>
		  <td>&nbsp;</td>
		  <td><input name="confirmed" type="radio" value="" id="BB"{if $smarty.request.confirmed eq ''} checked{/if} {if $smarty.request.all_users eq '1'} '1'}'1'}'1'}'1'}'1'}'1'}'1'}"disabled=disabled" {/if}></td>
		  <td><label for="BB">Both</label></td>
		</tr>
	  </table></td>
	</tr>
	<tr class=naGrid2>
	  <td  width="40%" align="right">Active</td>
	  <td>:</td>
	  <td><table border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td><input name="active" type="radio" value="Y" id="radio"{if $smarty.request.active eq 'Y'} checked{/if}></td>
		  <td><label for="radio">Yes</label></td>
		  <td>&nbsp;</td>
		  <td><input name="active" type="radio" value="N" id="radio2"{if $smarty.request.active eq 'N' || $smarty.request.active eq ''} checked{/if}></td>
		  <td><label for="radio2">No</label></td>
		  <td>&nbsp;</td>
		  <td><input name="active" type="radio" value="" id="radio3" {if $smarty.request.active eq ''} checked{/if}> </td>
		  <td><label for="radio3">Both</label> </td>
		</tr>
	  </table></td>
	</tr>
	{elseif $STEP == 2}
	<tr align="center" class=naGrid1>
	  <td colspan="3">Sending Newsletter 
      <strong>{$NEWSLETTER}</strong> to <strong>{$smarty.request.count}</strong> members</td>
    </tr>
	<tr class=naGrid2>
	  <td width="39%" align="right">Send From Name</td>
	  <td width="1%">:</td>
	  <td width="69%"><input type="text" name="sender_name" value="{$OWNER.name}" size="40"></td>
    </tr>
	<tr class=naGrid1>
	  <td align="right">Send From Email</td>
	  <td>:</td>
	  <td><input type="text" name="sender_email" value="{$OWNER.email}" size="40"></td>
    </tr>
	<tr class=naGrid2>
	  <td align="right">Reply-To Email </td>
	  <td>:</td>
	  <td><input type="text" name="replyto_email" value="{$OWNER.email}" size="40"></td>
    </tr>
	{else}
	<tr class=naGrid2>
	  <td width="49%" align="right">Newsletter</td>
	  <td width="1%">:</td>
	  <td width="50%">{$SCHEDULE.newsletter_name}</td>
    </tr>
	<tr class=naGrid1>
	  <td align="right">Mailing List</td>
	  <td>:</td>
	  <td>{if $SCHEDULE.list_name eq null} All Users {else}{$SCHEDULE.list_name}{/if}</td>
    </tr>
	<tr class=naGrid2>
	  <td align="right">Selected Members</td>
	  <td>:</td>
	  <td>{$SCHEDULE.member_count}</td>
    </tr>
	<tr class=naGrid1>
	  <td align="right">Sender</td>
	  <td>:</td>
	  <td>{$SCHEDULE.sender_name} &lt;{$SCHEDULE.sender_email}&gt;</td>
    </tr>
	<tr class="naGrid2">
	  <td colspan="3" align="center"><div align="left" id="log" style="padding:5px; width:500px; overflow:auto; height:100px; border:1px solid silver;"></div><br>
	  <div style="width:500px; height:15px; border:1px solid gray;" align="left">
	  	<div id="percentage" style="width:0%; height:15px; background-color:#00CC00; float:left;"></div>
	  </div>
	  </td>
	</tr>
	{/if}
	{if $smarty.request.step eq 3}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type="button" name="sendBtn" value="Send Now" class="naBtn" onClick="sendMail({$smarty.request.id});">
        </div></td> 
    </tr> 
	{else}
	<tr class="naGridTitle">
      <td colspan="3" align="center"><input type="submit" value="Next &raquo;" class="naBtn"><input type="hidden" name="step" value="{$STEP}"></td>
    </tr>
	{/if}
  </table>
</form> 
