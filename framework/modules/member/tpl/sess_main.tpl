<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Session History</td> 
		  <td nowrap  align="right">Search by username: <input type="text" name="txtsearch">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" ><a href="{makeLink mod=member pg=user}act=session{/makeLink}">{if $smarty.request.txtsearch!=""}View All History{/if}</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($SESS_LIST) > 0}       
		<tr>
		  <td  align="right" colspan="3" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 

        </tr>
		 <tr>
          <td  nowrap class="naGridTitle" height="24" align="center" width="10%">Status</td> 
          <td  height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="username" display="User Name"}act=session{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="308">{makeLink mod=member pg=user orderBy="duration,duration2,duration3" display="Total Duration"}act=session{/makeLink}</td> 
        </tr>
        {foreach from=$SESS_LIST item=user}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><img border="0" title="{if $user->image=='Y'}Online{else}Offline{/if}"  src="{$GLOBAL.tpl_url}/images/{$user->image}.gif" /></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=session_det&user_id={$user->user_id}&username={$user->username}{/makeLink}">{$user->username}</a></td> 
		  <td valign="middle" height="24" align="left">{$user->dur_str}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$SESS_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>