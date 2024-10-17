{literal}
<script language="javascript">

function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frmStore.elements.length;i++)
			{
			var e = frmStore.elements[i];
					
					if(e.name=='store_ids[]')
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
		document.frmStore.action='{/literal}{makeLink mod=`$MOD` pg=`$PG`}act=store_del&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}{literal}'; 
		document.frmStore.submit();
	}
}
</script>
{/literal}
       
		  
<table width="95%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frmStore" method="post" action="">
<table align="center" width="95%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=store pg=index}act=form{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">Add New {$smarty.request.sId}</a></td> 
        </tr>
        <tr>
		  <td class="naGrid1" align="left">{if count($STORE_LIST) > 0}<div align=left class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td>
          <td  align="right" class="naGrid1"><strong>Results per page</strong> {$STORE_LIMIT}</td>
        </tr> 
      </table>
	 </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="1" > 
        {if count($STORE_LIST) > 0}
        <tr>
		
		 <td width="5%" nowrap class="naGridTitle" height="24" align="center">&nbsp;&nbsp;<input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frmStore,'store_ids[]')"></td> 
			
		
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center">Active</td> 
          <td width="25%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=store pg=index orderBy="name" display="Store URL"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="25%" nowrap class="naGridTitle">{makeLink mod=store pg=index orderBy="heading" display="Store Name"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
		   <td width="5%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=store pg=index orderBy="first_name" display="First Name"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		    <td width="5%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=store pg=index orderBy="last_name" display="Last Name"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="5%" nowrap class="naGridTitle" align="center">{makeLink mod=store pg=index orderBy="active" display="Status"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
		     <td  width="10%"  nowrap class="naGridTitle" align="center">{makeLink mod=store pg=index orderBy="enddate" display="Subscription"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
        </tr>
		
        {foreach from=$STORE_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
		
		 <td valign="middle" height="24" align="center"><input type="checkbox" name="store_ids[]" value="{$row->id}" ></td> 
		 
		
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center">&nbsp;{ if $row->mem_active eq 'Y'} Active {else} Deactive{/if}</td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=$MOD pg=$PG}act=form&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->name}</a></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=$MOD pg=$PG}act=form&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->heading}</a></td> 
		  
		  <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=member pg=user}act=view&id={$row->user_id}&mem_type=2&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->first_name}</a></td> 
		  
		  <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=member pg=user}act=view&id={$row->user_id}&mem_type=2&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->last_name}</a></td> 
		  
          <td height="24" align="center" valign="middle"> {if $row->active.gif=='Y'} 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$row->active.gif}.gif"/>
			<a href="{makeLink mod=$MOD pg=$PG}act=active&id={$row->id}&stat={$row->active}&cat_id={$smarty.request.cat_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&aid={$smarty.request.aid}{/makeLink}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$row->active.gif}.gif"/></a>
		{else}
			<a href="{makeLink mod=$MOD pg=$PG}act=active&id={$row->id}&stat={$row->active}&cat_id={$smarty.request.cat_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&aid={$smarty.request.aid}{/makeLink}"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$row->active.gif}.gif"/></a>
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$row->active.gif}.gif"/>
		{/if}</td> 
		     <td  align="left" valign="middle">{$row->subs_status}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="9" class="msg" align="center" height="30">{$STORE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="9" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
</form>