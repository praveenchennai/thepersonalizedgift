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
          <td nowrap class="naH1">Members</td> 
          <td nowrap align="right" class="titleLink"><form name="form1" method="post" action="{makeLink mod=newsletter pg=memberSearch}act=list&limit={$smarty.request.limit}{/makeLink}" style="margin:0px;">
            Search:
            <input type="text" name="keyword" value="{$smarty.request.keyword}">
            <input type="submit" name="Submit" value="Submit" class="naBtn">
          </form></td> 
          <td nowrap align="right" class="titleLink">{if $MEMBER_LIMIT}Results Per Page{/if} </td>
          <td nowrap align="right" class="titleLink">{$MEMBER_LIMIT}</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($MEMBER_LIST) > 0}
        <tr>
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=newsletter pg=memberSearch orderBy="first_name" display="Name"}act=list&keyword={$smarty.request.keyword}{/makeLink}</td> 
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=newsletter pg=memberSearch orderBy="email" display="Email"}act=list&keyword={$smarty.request.keyword}{/makeLink}</td> 
        </tr>
        {foreach from=$MEMBER_LIST item=memberSearch}
        <tr> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left"><a class="linkOneActive" href="#" onClick="_pass('{$memberSearch->id}', '{$memberSearch->first_name} {$memberSearch->last_name} ({$memberSearch->username})', '{$memberSearch->email}');void(0);">{$memberSearch->first_name} {$memberSearch->last_name}</a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left"><a class="linkOneActive" href="#" onClick="_pass('{$memberSearch->id}', '{$memberSearch->first_name} {$memberSearch->last_name} ({$memberSearch->username})', '{$memberSearch->email}');void(0);">{$memberSearch->email}</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$MEMBER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>