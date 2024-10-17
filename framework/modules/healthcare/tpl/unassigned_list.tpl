<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></script>
{literal}
<script language="javascript">

function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<fruser.elements.length;i++)
			{
			var e = fruser.elements[i];
					
					if(e.name=='user_id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one {/literal}{$smarty.request.sId}{literal}");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal}?'))
	{
		document.fruser.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=user_delete&&pageNo={$smarty.request.pageNo}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.fruser.submit();
	}
}
</script>
{/literal}
<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
	<tr><td>{messageBox}</td></tr>
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Registered Users-->{$smarty.request.sId}</td> 
		  <td nowrap  align="right">Search by {$SEARCH_BY_DATE} date: From <input name="created_frm" type="text" class="formText" id="dob" value="{$smarty.request.created_frm}" size="14" maxlength="25" onFocus="popUpCalendar(this, this, 'mm-dd-yyyy', 0, 0)" readonly >&nbsp;To <input name="created_to" type="text" class="formText" id="dob" value="{$smarty.request.created_to}" size="14" maxlength="25" onFocus="popUpCalendar(this, this, 'mm-dd-yyyy', 0, 0)" readonly >
		  &nbsp;<input type="submit" border="0" value="Search"> </td> 
		  <td align="right" ><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}{/makeLink}&sId={$SUBNAME}&mId={$MID} ">View All</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		<tr>
        	<td colspan="6"  align="right" class="naGrid1">Items per Page: {$LIMIT_LIST}</td>
        </tr>
        <tr>
          <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="username" display="User Name"}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="first_name" display="First Name"}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  {if $NO_LNAME eq 'Y'}
		  <td width="150" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="joindate" display="Member Since"}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  {else}
		   <td width="150" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="last_name" display="Last Name"}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  {/if}
		  <td colspan="1" class="naGridTitle" height="24" align="left" width="134">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="email" display="Email"}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td colspan="1" class="naGridTitle" height="24" align="left" width="134">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="assigned_date" display="Time "}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td colspan="1" class="naGridTitle" height="24" align="left" width="134">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="refill" display="Refill"}act={$smarty.request.act}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
        </tr>
        {if count($USER_LIST) > 0}
        {foreach from=$USER_LIST item=user}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=view&id={$user->medical_questionnaire_id}&assign_id={$user->assignID}&refill={$user->refill}&doct_id={$user->doctor_id}&pharma_id={$user->pharmacy_id}&stat={$user->status}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&pageNo={$smarty.request.pageNo}{/makeLink}">{$user->username}</a></td> 
		  <td valign="middle" height="24" align="left">{$user->first_name}</td> 
		   {if $NO_LNAME eq 'Y'}
		  <td valign="middle" height="24" align="left">{$user->joindate|date_format}</td> 
		  {else}
		   <td valign="middle" height="24" align="left">{$user->last_name}</td> 
		   {/if}
		  <td  height="24" align="left" valign="middle">{$user->email}</td> 
		  <td  height="24" align="left" nowrap valign="middle">{$user->assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
		  <td  height="24" align="left" valign="middle">{if $user->refill mod 3 ==0 AND $user->refill>0}Reconsult{/if}
		    {if $user->refill mod 3 ==1}First{/if}
		  	{if $user->refill mod 3 ==2}Second{/if}
		  </td>
		   </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>