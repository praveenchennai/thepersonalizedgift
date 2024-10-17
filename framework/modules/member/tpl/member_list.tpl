<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><span class="naH1">Registered Users</span></td>
  </tr> 
  <tr> 
    <td>
		<table width=100% border=0 cellpadding="0" cellspacing="0"> 
       {if count($USER_LIST) >0}				
        <tr>
          <td width="1%" align="left" nowrap class="naGridTitle">&nbsp;</td>
			<td width="8%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="username" display="User Name"}act=userlist{/makeLink}</td> 
			<td width="10%" height="24" align="left" nowrap class="naGridTitle"></td>
		</tr>     
   		{foreach from=$USER_LIST item=user}
		   <tr class="{cycle values="naGrid1,naGrid2"}">
		      <td width="1%" align="left" nowrap>&nbsp;</td>
		  	  <td width="8%" height="24" align="left" nowrap><a class="linkOneActive" href="{makeLink mod=member pg=user}act=view&id={$user->id}{/makeLink}">{$user->username}</a></td>           
			  <td width="10%" height="24" align="left" nowrap><a class="linkOneActive" href="{makeLink mod=extras pg=extras}act=assignuser&user_id={$user->id}{/makeLink}">Asign Coupon</a></td>
		   </tr>   
      {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}			
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>
	  </td> 
  </tr> 
</table>