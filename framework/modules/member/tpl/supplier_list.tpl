{literal}
<script language="javascript">

function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<fruser.elements.length;i++)
			{
			var e = fruser.elements[i];
					
					if(e.name=='user_id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one {/literal}{$smarty.request.sId}{literal}");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal}?'))
	{
		document.fruser.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=user_delete&&pageNo={$smarty.request.pageNo}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.fruser.submit();
	}
}
</script>
{/literal}


<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Registered Users-->{$SUBNAME}</td> 
		  <td nowrap  align="right">Search by username: <input type="text" name="txtsearch">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" ><a href="{makeLink mod=member pg=user}act=supplier_view{/makeLink}&sId={$SUBNAME}&mId={$MID} ">Add Supplier</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=80% cellpadding="5" cellspacing="2"> 
	<tr>
		  		<td colspan="1"  align="center" class="naGrid1">{if count($USER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td> 
          		<td colspan="6"  align="right" class="naGrid1">Items per Page: {$LIMIT_LIST}</td>
          </tr>
		
        <tr>
			<td width="20" nowrap class="naGridTitle" align="center"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.fruser,'user_id[]')"></td>
          <td width="32" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="150" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="username" display="User Name"}act=supplier_list{/makeLink}</td> 
		  <td width="150" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="first_name" display="First Name"}act=supplier_list{/makeLink}</td> 
		  <td width="150" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="last_name" display="Last Name"}act=supplier_list{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="308">{makeLink mod=member pg=user orderBy="email" display="Email"}act=supplier_list{/makeLink}</td> 

        </tr>
        {if count($USER_LIST) > 0}
        {foreach from=$USER_LIST item=user}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
		 <td valign="middle" align="center"><input type="checkbox" name="user_id[]" value="{$user->id}" ></td>
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=active&stat={$user->active}&done=supplier&id={$user->id}{/makeLink}"><img border="0" title="Activate/Deactivate"  src="{$GLOBAL.tpl_url}/images/active{$user->active}.gif" /></a></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=supplier_view&id={$user->id}{/makeLink}">{$user->username}</a></td> 
		  <td valign="middle" height="24" align="left">{$user->first_name}</td> 
		   <td valign="middle" height="24" align="left">{$user->last_name}</td> 
		  <td valign="middle" height="24" align="left">{$user->email}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>