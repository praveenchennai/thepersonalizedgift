<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">UserList Referred By {$smarty.request.referer}</td> 
		  <td nowrap  align="right">Search by username: <input type="text" name="txtsearch">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" >&nbsp;</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
	     {if count($USER_LIST) > 0}
        <tr>
        
		   <td nowrap class="naGridTitle" height="24" align="left" width="200">{makeLink mod=member pg=user orderBy="screen_name" display="Screen Name"}act=referalldetails{/makeLink}</td>  
		 
        		  <td nowrap class="naGridTitle" height="24" align="left" width="200">{makeLink mod=member pg=user orderBy="email" display="Email"}act=referalldetails{/makeLink}</td> 
      
					    <td nowrap class="naGridTitle" height="24" align="left" width="200">Joined Date</td>
        </tr>
        {foreach from=$USER_LIST item=user}
        <tr> 
		
         
          <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2"  advance=false}" height="24" align="left">{$user->screen_name}</td>  
		  <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2"  advance=false}" height="24" align="left">{$user->email}</td> 
		 
		     <td nowrap class="{cycle name=bg values="naGrid1,naGrid2"  advance=false}" height="24" align="left" width="200">{$user->joindate|date_format}</td>
		  
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="7" class="msg" align="center" height="30">&nbsp;</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="7" class="naError" align="center" height="30">&nbsp;</td> 
        </tr>
        {/if}
		
		 <tr class="naGridTitle"> 
      <td colspan=7 valign=center><div align=center> 
	  
          <input name="Button" type=button class="formbutton" value="Go Back" onClick="javascript:history.go(-1)"> 
		  
        </div></td> 
    </tr> 
      </table></td> 
  </tr> 
</table></form>