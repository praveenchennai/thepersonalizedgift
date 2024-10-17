<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Session History->{$smarty.request.username}</td> 
		  <td nowrap  align="right">&nbsp;		    </td> 
		  <td align="right" ><a href="{makeLink mod=member pg=user}act=session{/makeLink}">All History</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
	<tr>
		  <td  align="right" colspan="6" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 

        </tr>
        {if count($SESS_LIST) > 0}
        <tr>
          <td  nowrap class="naGridTitle" height="24" align="center" width="10%">Status</td>
		  <td  nowrap class="naGridTitle" height="24" align="center" width="10%">IP Address</td> 
		  <td  nowrap class="naGridTitle" height="24" align="center" >IP Location</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="20%">{makeLink mod=member pg=user orderBy="login_time" display="Login Time"}act=session_det&user_id={$smarty.request.user_id}&username={$smarty.request.username}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="20%">{makeLink mod=member pg=user orderBy="logout_time" display="Logout Time"}act=session_det&user_id={$smarty.request.user_id}&username={$smarty.request.username}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="center" >{makeLink mod=member pg=user orderBy="duration" display="Duration"}act=session_det&user_id={$smarty.request.user_id}&username={$smarty.request.username}{/makeLink}</td> 
        </tr>
        {foreach from=$SESS_LIST item=user}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><img border="0" title="{if $user->status=='Y'}Online{else}Offline{/if}"  src="{$GLOBAL.tpl_url}/images/{$user->status}.gif" /></td> 
		  <td valign="middle" height="24" align="left">{$user->ip_address}</td> 
		  <td valign="middle" height="24" align="left">City:{$user->city}, Country:{$user->country}</td> 
		  <td valign="middle" height="24" align="left">{$user->login_time|date_format:"%b %e, %Y %H:%M:%S"}</td> 
		  <td valign="middle" height="24" align="left">{$user->logout_time|date_format:"%b %e, %Y %H:%M:%S"}</td> 
		  <td valign="middle" height="24" align="center">{$user->duration}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$SESS_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>