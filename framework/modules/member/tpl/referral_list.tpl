<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function pageSearch()
	{
	cat_search	=	document.getElementById("category_search").value;
	document.frm_referral.keysearch.value='Y';
	document.frm_referral.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
	document.frm_referral.submit();
	}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_referral.elements.length;i++)
			{
			var e = frm_referral.elements[i];
					
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
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal} Criteria?'))
	{
		document.frm_referral.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_referral.submit();
	}
}
function popUp1(group_id) {
	//alert(url);
	{/literal}
window.open('{makeLink mod=flyer pg=attribute_options}act=options_view{/makeLink}&id='+group_id, "name_of_window", "width=540,height=380,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
}
</script>


<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_referral" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId} Criteria</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=form{/makeLink}&sId={$smarty.request.sId}&forms_id={$FORM_ID}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit} ">Add New {$smarty.request.sId} Criteria</a> 
	</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
  
    <tr class=naGrid2>
	<td valign=middle align=left >
	{if count($REF_CRITERIA_LIST) > 0}<div align=center class="titleLink"><a href="#" onClick="javascript:deleteSelected();">Delete</a></div>{/if}
    </td>
    	<td colspan=4 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		 <td align="left" valign=middle><!-- Search: --></td>
			<td><!-- <input type="text" id="category_search" name="category_search" value="{$REF_CRITERIA_SEARCH_TAG}"> --></td>
			<td><!-- <input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();"> --></td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$REF_CRITERIA_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_referral,'category_id[]')"></td>
          <td width="70%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=ref_id display="Referral Plans"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="15%" align="left" nowrap class="naGridTitle">&nbsp;</td>
              <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($REF_CRITERIA_LIST) > 0}
        {foreach from=$REF_CRITERIA_LIST item=referral name=foo}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if isset($STORE_ID) and $referral->is_private eq N}<input type="checkbox" name="category_id[]" value="{$referral->ref_id}" disabled>{else}<input type="checkbox" name="category_id[]" value="{$referral->ref_id}" >{/if}</td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=form&ref_id={$referral->ref_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_id={$smarty.request.store_id}{/makeLink}">{$referral->count} {$referral->package_name} Referral = {$referral->reward_count} 
		  {if $referral->type eq 'D'}Days{elseif $referral->type eq 'M'} Month {else} Year {/if} Free</a></td> 
          <td align="left" valign="middle">&nbsp;</td>
          <td align="left" valign="middle">&nbsp;</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$REF_CRITERIA_NUMPAD}</td> 
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