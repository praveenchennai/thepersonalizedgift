<script language="javascript">
{literal}
function _pass(id, name, email) {
	opener.document.getElementById("memberName").innerHTML = name;
	opener.document.subFrm.email.value = email;
	opener.document.subFrm.member_id.value = id;
	window.close();
}
{/literal}
</script>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Subscription Log</td> 
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
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">Start Date</td> 
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">End Date</td> 
        </tr>
         {if $MEMBER_LIST1|@count gt 0}
		 <tr> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left">{$MEMBER_LIST1->startdate|date_format} </td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left"><!--{$MEMBER_LIST1->enddate|date_format}--></td> 
        </tr> 
		  {/if}
		{foreach from=$MEMBER_LIST item=memberSearch}
        <tr> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left">{$memberSearch->startdate|date_format} </td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left">{$memberSearch->enddate|date_format}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30"><!-- {$MEMBER_NUMPAD} --></td> 
        </tr>
		
      </table></td> 
  </tr> 
</table>