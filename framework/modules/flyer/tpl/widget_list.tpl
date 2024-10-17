<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function pageSearch()
	{
	cat_search	=	document.getElementById("category_search").value;
	document.frm_widget.keysearch.value='Y';
	document.frm_widget.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
	document.frm_widget.submit();
	}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_widget.elements.length;i++)
			{
			var e = frm_widget.elements[i];
					
					if(e.name=='category_id[]')
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
		document.frm_widget.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_widget.submit();
	}
}
</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_widget" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink">&nbsp;
	</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
   
    <tr class=naGrid2>
	<td valign=middle align=left >
	
	{if count($WIDGET_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}
   
	</td>
    	<td colspan=5 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		  <td align="left" valign=middle>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$WIDGET_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_widget,'category_id[]')"></td>
          <td width="25%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=title display="`$smarty.request.sId` Title"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="15%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=type display="Type"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="25%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=first_name display="User"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
		  <td width="15%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=modified_date display="Modified Date"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="15%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($WIDGET_LIST) > 0}
        {foreach from=$WIDGET_LIST item=widget name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if isset($STORE_ID) and $widget->is_private eq N}<input type="checkbox" name="category_id[]" value="{$widget->category_id}" disabled>{else}<input type="checkbox" name="category_id[]" value="{$widget->widget_id}" >{/if}</td> 
          <td align="left" valign="middle">{$widget->title} </td> 
          <td align="left" valign="middle">{if $widget->type eq 'L'}Listing {else}Gallery {/if}</td>
          <td align="left" valign="middle">{$widget->first_name} {$widget->last_name}</td>
		  <td align="left" valign="middle">{$widget->modified_date}</td>
          <td align="left" valign="middle"><a href="{$WIDGET_PATH}{$widget->widget_id}&flg=1" target="_blank">View Gadget</a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$WIDGET_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
   <tr>
    <td>&nbsp;</td>
  </tr> 
  <tr>
    <td>&nbsp;</td>
  </tr>
</table><input type="hidden" name="keysearch" value="N">
</form>