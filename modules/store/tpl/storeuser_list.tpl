<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td></td> 
  </tr> 
  <tr> 
    <td>
	<table border=0 width=100% cellpadding="5" cellspacing="2"> 	
        {if count($USER_LIST) > 0}
        <tr>
          <td width="440" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=store pg=storeuser orderBy="username" display="User Name"}act=list{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="308">{makeLink mod=store pg=storeuser orderBy="email" display="Email"}act=list{/makeLink}</td> 
	   </tr>
        {foreach from=$USER_LIST item=user}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=store pg=storeuser}act=view&id={$user->id}{/makeLink}">{$user->username}</a></td> 
		  <td valign="middle" height="24" align="left">{$user->email}</td> 
		</tr> 
        {/foreach}
        <tr> 
          <td colspan="2" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>
	  </td> 
  </tr> 
</table></form>