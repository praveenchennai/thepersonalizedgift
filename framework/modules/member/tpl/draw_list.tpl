<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">UserList For Draw </td> 
		  <td nowrap  align="right">Search by username: <input type="text" name="txtsearch">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" ><a href="{makeLink mod=member pg=user}act=list{/makeLink}"></a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
	     {if count($USER_LIST) > 0}
        <tr>
         
		 
        		  <td nowrap class="naGridTitle" height="24" align="left" width="200">{makeLink mod=member pg=user orderBy="email" display="Email"}act=referlist{/makeLink}</td> 
      
        		  <td nowrap class="naGridTitle" height="24" align="left" width="200">{makeLink mod=member pg=user orderBy="email" display="Draw Points"}act=referlist{/makeLink}</td> 
				    <td nowrap class="naGridTitle" height="24" align="left" width="200">{makeLink mod=member pg=user orderBy="email" display="No of Draw Entries"}act=referlist{/makeLink}</td> 
					    <td nowrap class="naGridTitle" height="24" align="left" width="200">Details</td>
        </tr>
        {foreach from=$USER_LIST item=user}
        <tr> 
		  <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2"  advance=false}" height="24" align="left">{$user->email}</td> 
		  <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2" advance=false}" height="24" align="left">{$user->ref_count*10}</td> 
		  <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2" advance=false}" height="24" align="left">{$user->ref_count}</td> 
		    <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2" advance=false}" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=referalldetails&referer={$user->email}{/makeLink}">View</a></td> 
		  
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="7" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="7" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>