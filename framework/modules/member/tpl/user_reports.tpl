<form name="frmuser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Registered Users-->{$SUBNAME}</td> 
		  <td nowrap  align="right">Report on : 
		   	<select name="report_key" onchange="window.location.href='{makeLink mod=member pg=user}act=reports{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId} &report_key='+this.value">
     	   <option value="0">All users</option> 
		<!--    <option value="1">New Members</option>
			<option value="2">Cancelled Members</option>
			<option value="3">Active in Last 30 days</option>
			<option value="4">Active in Last 60 days</option>-->
			 {html_options values=$REPORT_KEYS.id output=$REPORT_KEYS.name selected=$REQID}
      
		 </select>
			</td> 
		  <td align="right" >&nbsp;</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		<tr>
		  <td  align="right" colspan="6" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 
        </tr>
        <tr>
           
          <td width="20%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="username" display="User Name"}act=reports&report_key={$REQID}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td width="20%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="first_name" display="First Name"}act=reports&report_key={$REQID}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td width="20%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="last_name" display="Last Name"}act=reports&report_key={$REQID}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="20%">{makeLink mod=member pg=user orderBy="email" display="Email"}act=reports&report_key={$REQID}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="20%">{makeLink mod=member pg=user orderBy="joindate" display="Join Date"}act=reports&report_key={$REQID}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
        </tr>
        {if count($USER_LIST) > 0}
        {foreach from=$USER_LIST item=user}
        <tr class="{cycle values="naGrid1,naGrid2"}">
           
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=view&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$user->username}</a></td> 
		  <td valign="middle" height="24" align="left">{$user->first_name}</td> 
		  <td valign="middle" height="24" align="left">{$user->last_name}</td> 
		  <td valign="middle" height="24" align="left">{$user->email}</td> 
		  <td valign="middle" height="24" align="left">{$user->joindate}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>