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

function sendMail(id,batch_email,delayed_seconds) {
	document.sendFrm.sendBtn.disabled = true;
	document.sendFrm.sendBtn.value = 'Sending...';
	document.getElementById("percNum").style.display = "inline";
	serverCall(id, 0, batch_email, delayed_seconds);
}
function serverCall(id, page, batch_email, delayed_seconds) {

	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, serverResponse);
	{/literal}
	req.open("GET", "{makeLink mod=newsletter pg=ajax}{/makeLink}&id="+id+"&page="+page+"&batch_email="+batch_email+"&delayed_seconds="+delayed_seconds+"&"+Math.random());
	{literal}
	req.send(null);
}
function serverResponse(_var) {

	_var = _var.split('|');
	
	if(_var[1] > 0) {//alert(_var[1]);alert(_var[4]);
		document.getElementById("log").innerHTML += _var[2] + '<br>';
		document.getElementById("percentage").style.width = (_var[1]/_var[3])*100+'%';
		document.getElementById("percNum").innerHTML =  "<strong>" + Math.round((_var[1]/_var[3])*100)+'%' + "</strong>";
		
		serverCall(_var[0], _var[1],_var[4],_var[5]);
	} else {
	
		document.getElementById("log").innerHTML += _var[2] + '<br>';
		document.getElementById("percentage").style.width = '100'+'%';
		document.getElementById("percNum").style.display = "none";
	
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

function showPopup() {
window.open( "{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=view_newsletter_emails&reg_pack={$smarty.request.reg_pack}&sub_pack={$smarty.request.sub_pack}&email={$smarty.request.email}&confirmed={$smarty.request.confirmed}&active={$smarty.request.active}&country={$smarty.request.country}&date_from={$smarty.request.date_from}&date_to={$smarty.request.date_to}&active_store={$smarty.request.active_store}{/makeLink}{literal}", "", 
"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )

}
</script>
{/literal}
<form method="POST" name="sendFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table border=0 width=85% cellpadding=5 cellspacing=1 class=naBrDr> 
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
	{if $MAILINGLIST_ENABLE eq 'Y'}
    <tr class=naGrid2> 
      <td width="40%" align="right">Select Members From</td> 
      <td width="1%">:</td> 
      <td width="60%" valign="middle">&nbsp;Mailing List&nbsp;<select name="list_id" id="list_id" onchange="test()" {if $MAILINGLIST_ENABLE eq 'N'}disabled="disabled" {/if} >
        <option value="">Select Mailing List</option>
        {html_options values=$MAILINGLIST.id output=$MAILINGLIST.name selected=$smarty.request.list_id}
        </select>
		&nbsp;&nbsp;&nbsp;Or&nbsp;&nbsp;&nbsp;Registered Members&nbsp;<input type="checkbox" name="all_users" value="1" size="40" checked="checked" {if $MAILINGLIST_ENABLE eq 'N'} disabled="disabled" {/if} onclick="test();">
		
	  </td> 
    </tr>
	{/if}
	{if $MAILINGLIST_ENABLE eq 'N'} <input type="hidden" name="all_users" value="1" /> {/if}
	<tr class=naGrid1>
	  <td  width="40%" align="right">Registration Type </td>
	  <td>:</td>
	  <td> <select name="reg_pack" id="reg_pack" class="input" value="" onChange="return loadSub(this.value)" style="width:180px;" >
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
                    <input type="text" name="date_from" value="{$DATE_FROM}" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" {if $MAILINGLIST_ENABLE eq 'Y'} disabled{/if} />
                 To:
                 <input type="text" name="date_to" size="10" value="{$DATE_TO}" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" {if $MAILINGLIST_ENABLE eq 'Y'} disabled{/if} /></td>
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
	{if $MAILINGLIST_ENABLE eq 'Y'}
	<tr class=naGrid1>
	  <td  width="40%" align="right"> Confirmed Subscribers</td>
	  <td>:</td>
	  <td><table border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td><input name="confirmed" type="radio" value="N" id="N"{if $smarty.request.confirmed eq 'N'} checked{/if} {if $MAILINGLIST_ENABLE eq 'N'} disabled="disabled" {/if}></td>
		  <td><label for="Y">Yes</label></td>
		  <td>&nbsp;</td>
		  <td><input name="confirmed" type="radio" value="Y" id="Y"{if $smarty.request.confirmed eq 'Y'} checked{/if} {if $MAILINGLIST_ENABLE eq 'N'}disabled="disabled" {/if}></td>
		  <td><label for="N">No</label></td>
		  <td>&nbsp;</td>
		  <td><input name="confirmed" type="radio" value="" id="BB"{if $smarty.request.confirmed eq ''} checked{/if} {if $MAILINGLIST_ENABLE eq 'N'} disabled="disabled" {/if}></td>
		  <td><label for="BB">Both</label></td>
		</tr>
	  </table></td>
	</tr>
	{/if}
	<tr class=naGrid2>
	  <td  width="40%" align="right"> Active Registered Users</td>
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
	{if $STORE_ENABLE eq 'Y'}
	<tr class=naGrid1>
	  <td  width="40%" align="right"> Active Store</td>
	  <td>:</td>
	  <td><table border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td><input name="active_store" type="radio" value="N" id="active_store_radio"{if $smarty.request.active_store eq 'N'} checked{/if}></td>
		  <td><label for="radio">Yes</label></td>
		  <td>&nbsp;</td>
		  <td><input name="active_store" type="radio" value="Y" id="active_store_radio2"{if $smarty.request.active_store eq 'Y'} checked{/if}></td>
		  <td><label for="radio2">No</label></td>
		  <td>&nbsp;</td>
		  <td><input name="active_store" type="radio" value="" id="active_store_radio3" {if $smarty.request.active_store eq ''} checked{/if}> </td>
		  <td><label for="radio3">Both</label> </td>
		</tr>
		{/if}
	  </table></td>
	</tr>
	{elseif $STEP == 2}
	<tr align="center" class=naGrid1>
	  <td colspan="3">Sending Newsletter 
      <strong>{$NEWSLETTER}</strong> to <strong><a href="javascript:void(0);" onclick="showPopup();return false;">{$smarty.request.count}&nbsp;(View Email)</a></strong> members</td>
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
	<!--<tr class=naGrid1>
	  <td align="right">Number of E-mails in a batch</td>
	  <td>:</td>
	  <td><input type="text" name="batch_email" value="" size="40"></td>
    </tr>
	<tr class=naGrid2>
	  <td align="right">Number of Seconds to be delayed for the next batch </td>
	  <td>:</td>
	  <td><input type="text" name="delayed_seconds" value="" size="40"></td>
    </tr>-->
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
	  <td>{$SCHEDULE.member_count}&nbsp;</td>
    </tr>
	<tr class=naGrid1>
	  <td align="right">Sender</td>
	  <td>:</td>
	  <td>{$SCHEDULE.sender_email}</td>
    </tr>
	<tr class="naGrid2">
	  <td colspan="3" align="center"><div align="left" id="log" style="padding:5px; width:500px; overflow:auto; height:100px; border:1px solid silver;"></div><br>
	  <div style="width:500px; height:15px; border:1px solid gray;" align="left">
	  	<div id="percentage" style="width:0%; height:15px; background-color:#00CC00; float:left;"></div>
	  </div><div id="percNum" style="width:75px;display:none;background-image:url({$GLOBAL.tpl_url}/images/grid/loading.gif);background-position:right;background-repeat:no-repeat"></div>
	  </td>
	</tr>
	{/if}
	{if $smarty.request.step eq 3}
    <tr class="naGridTitle"> 
	{if $SCHEDULE.time_interval gt 0}
	{assign var='interval' value=$SCHEDULE.time_interval} 
	{/if}
	{if $SCHEDULE.batch_nos gt 0}
	{assign var='batch' value=$SCHEDULE.batch_nos} 
	{/if}
	{if $interval eq 0}
	{assign var='interval' value=$smarty.request.delayed_seconds} 
	
	{/if}
	{if $batch eq 0}
	{assign var='batch' value=$smarty.request.batch_email} 
	
	{/if}
	
      <td colspan=3 valign=center><div align=center> 
	   <!-- {if ($smarty.request.batch_email eq "" and $smarty.request.delayed_seconds eq "")}!-->
          <input type="button" name="sendBtn" value="Send Now" class="naBtn" onClick="sendMail({$smarty.request.id});">
	 <!-- {else}
	  {if ($smarty.request.batch_email neq "" and $smarty.request.delayed_seconds neq "")}
          <input type="button" name="sendBtn" value="Send Now" class="naBtn" onClick="sendMail({$smarty.request.id},{$smarty.request.batch_email},{$smarty.request.delayed_seconds});">
	  {else}
	  {if ($smarty.request.batch_email eq "" )}
          <input type="button" name="sendBtn" value="Send Now" class="naBtn" onClick="sendMail({$smarty.request.id},0,{$smarty.request.delayed_seconds});">
	  {/if}
	  
	   {if ($smarty.request.delayed_seconds eq "")}
          <input type="button" name="sendBtn" value="Send Now" class="naBtn" onClick="sendMail({$smarty.request.id},{$smarty.request.batch_email},0);">
	  {/if}
	  {/if}
	  
	  {/if}!-->
        </div></td> 
    </tr> 
	{else}
	<tr class="naGridTitle">
      <td colspan="3" align="center"><input type="submit" value="Next &raquo;" class="naBtn"><input type="hidden" name="step" value="{$STEP}"></td>
    </tr>
	{/if}
  </table>
</form> 
