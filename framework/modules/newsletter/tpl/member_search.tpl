<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<script language="javascript">
{literal}
function _pass(id, name, email,from) {
	
	opener.document.getElementById("memberName").innerHTML = name;
	opener.document.subFrm.email.value = email;
	opener.document.subFrm.member_id.value = id;
	if(from=='store'){
		validStoreMember(id);
	}
	else{
		window.close();
	}
}

function validStoreMember(id)
{
	var req1  = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResvalidStoreMember);
	str	 = "id="+id;
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_store}act=valid_store_member{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverResvalidStoreMember(_var)
{
	if(_var==true)
	{
	document.getElementById('msg_div').innerHTML="<span style='color:#FF0000'><strong>This user is already assigned to another store.</strong></span>";
	opener.document.getElementById("memberName").innerHTML = '';
	opener.document.subFrm.email.value = '';
	opener.document.subFrm.member_id.value = '';
	}
	else{
	window.close();
	}
}

{/literal}
</script>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
	<tr>
		<td colspan="4" align="center"><div id="msg_div"></div></td>
		</tr>
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
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left"><a class="linkOneActive" href="#" onClick="_pass('{$memberSearch->id}', '{$memberSearch->first_name} {$memberSearch->last_name} ({$memberSearch->username})', '{$memberSearch->email}','{$smarty.request.from}');void(0);">{$memberSearch->first_name} {$memberSearch->last_name}</a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left"><a class="linkOneActive" href="#" onClick="_pass('{$memberSearch->id}', '{$memberSearch->first_name} {$memberSearch->last_name} ({$memberSearch->username})', '{$memberSearch->email}','{$smarty.request.from}');void(0);">{$memberSearch->email}</a></td> 
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