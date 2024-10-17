<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Email List</td> 
          <td nowrap align="right" class="titleLink"><!-- <form name="form1" method="post" action="{makeLink mod=member pg=user}act=subscr_log&id={$smarty.request.id}&limit={$smarty.request.limit}{/makeLink}" style="margin:0px;">
            Search:
            <input type="text" name="keyword" value="{$smarty.request.keyword}">
            <input type="submit" name="Submit" value="Submit" class="naBtn">
          </form> --></td> 
          <td nowrap align="right" class="titleLink"><!-- {if $MEMBER_LIMIT}Results Per Page{/if} --> </td>
          <td nowrap align="right" class="titleLink"><!-- {$MEMBER_LIMIT} --></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
       
        <tr>
          <td width="50%" nowrap class="naGridTitle" colspan="2" height="24" align="left">Email</td> 
          
        </tr>
         
		{foreach from=$EMAILS item=email_ar}
        <tr class="{cycle values="naGrid1,naGrid2" advance=false}"> 
          <td valign="middle" colspan="2" height="24" align="left">{$email_ar->email} </td> 
         
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30"><!-- {$MEMBER_NUMPAD} --></td> 
        </tr>
		
      </table></td> 
  </tr> 
</table>