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
		  <td nowrap  align="right">Search: <input type="text" name="txtsearch">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" ><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&mem_type={$smarty.request.mem_type}{/makeLink}&sId={$SUBNAME}&mId={$MID} ">View All</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		{if $SECTION_LIST}
			<tr>
		  		<td colspan="2"  align="center" class="naGrid1">{if count($USER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td> 
          		<td colspan="4"  align="right" class="naGrid1">Member Type : 
		  			<select name=section_id onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=user}act=list{/makeLink}&mem_type='+this.value">
							{html_options values=$SECTION_LIST.id output=$SECTION_LIST.type selected=`$smarty.request.mem_type`}
           			</select>		  
		  		</td>
		  		<td colspan="2"  align="right" class="naGrid1">Items per Page: {$LIMIT_LIST}</td>
          	</tr>
		{else}
			<tr>
		  		<td colspan="2"  align="center" class="naGrid1">{if count($USER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td> 
          		<td colspan="6"  align="right" class="naGrid1">Items per Page: {$LIMIT_LIST}</td>
          </tr>
		  {/if}
        <tr>
			 <td width="27" nowrap class="naGridTitle" align="center"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.fruser,'user_id[]')"></td>
          <td width="32" nowrap class="naGridTitle" align="center"></td>
          <td width="65" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="active" display="Active"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  {foreach from=$FIELDS_CS item=field}	
		  	{if $field->showInList=="Y"}
		  		<td {$field->list_param} nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy=`$field->field_name` display="`$field->display_name`" }act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td> 
			{/if}		
		  {/foreach}	     
        </tr>
        {if count($USER_LIST) > 0}
        {foreach from=$USER_LIST item=user name=loop1}
        <tr class="{cycle values="naGrid1,naGrid2"}">
		  <td valign="middle" align="center"><input type="checkbox" name="user_id[]" value="{$user->id}" ></td> 
          <td  valign="middle" align="center"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=user_modify&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td  valign="middle" height="24" align="center"> {if $user->active eq 'Y' } 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$user->active}.gif"/>
			<a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=active&stat={$user->active}&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$user->active}.gif"/></a>
		{else}
			<a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=active&stat={$user->active}&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$user->active}.gif"/></a>
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$user->active}.gif"/>
		{/if}</td>
		   {foreach from=$FIELDS_CS item=field name=loop2}	
		  {if $field->showInList=="Y"}
		  <!-- $aid variable holds the value of the sub-accessory id -->  
		  	<td  width="84" {$field->list_data_align}><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=view&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&pageNo={$smarty.request.pageNo}{/makeLink}">
		{$FIELDS_VALS[$smarty.foreach.loop1.index][$smarty.foreach.loop2.index]}</a></td> 
	{/if}
{/foreach}	
		  
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