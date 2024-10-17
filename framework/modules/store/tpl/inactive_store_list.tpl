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
		document.frmStore.action='{/literal}{makeLink mod=`$MOD` pg=`$PG`}act=store_delete&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}{literal}'; 
		document.frmStore.submit();
	}
}

function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=850,height=200,screenX=150,screenY=150,top=150,left=150')
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
		<td nowrap class="naH1">Deleted {$smarty.request.sId}</td> 
          <td nowrap align="right"  width="20%">&nbsp;</td> 
		    <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
		  <td class="naGrid1" align="left">{if count($STORE_LIST) > 0}<div align=left class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td>
		    <td nowrap  align="center" class="naGrid1" ><a href="{makeLink mod=$MOD pg=$PG}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.store_limit}&pageNo={$smarty.request.store_pageNo}{/makeLink}" >Retail Shop </a></td> 
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
          <td width="25%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=store pg=index orderBy="name" display="Store URL"}act=inactive_store_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}</td> 
          <td width="25%" nowrap class="naGridTitle">{makeLink mod=store pg=index orderBy="heading" display="Store Name"}act=inactive_store_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}</td>
		   <td width="5%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=store pg=index orderBy="first_name" display="First Name"}act=inactive_store_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}</td> 
		    <td width="5%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=store pg=index orderBy="last_name" display="Last Name"}act=inactive_store_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}</td> 
         
		     
			 
			 <td  width="10%"  nowrap class="naGridTitle" align="center">&nbsp;</td>
			     
        </tr>
		
        {foreach from=$STORE_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
		
		 <td valign="middle" height="24" align="center"><input type="checkbox" name="store_ids[]" value="{$row->id}" ></td>  
          
          <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=$MOD pg=$PG}act=form&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}">{$row->name}</a></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=$MOD pg=$PG}act=form&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}">{$row->heading}</a></td> 
		  
		  <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=member pg=user}act=view&id={$row->user_id}&mem_type=2&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}">{$row->first_name}</a></td> 
		  
		  <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=member pg=user}act=view&id={$row->user_id}&mem_type=2&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}">{$row->last_name}</a></td> 
			  <td  align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=store pg=index}act=inactive_store_list&id={$row->id}&mem_type=2&sId={$smarty.request.sId}&fId={$smarty.request.fId}&status=N&limit={$smarty.request.limit}&store_limit={$smarty.request.store_limit}&pageNo={$smarty.request.pageNo}&store_pageNo={$smarty.request.store_pageNo}{/makeLink}">Restore</a></td> 
			  
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