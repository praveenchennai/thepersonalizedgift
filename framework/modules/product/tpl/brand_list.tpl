<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_brand" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId} 
            <input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
		  </td> 
          <td nowrap align="right" class="titleLink">
		  {if $STORE_PERMISSION.add == 'Y'}
		  <a href="{makeLink mod=$MOD pg=$PG} act=form&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink} ">Add New {$smarty.request.sId} </a>
		  {/if}
		  </td> 
		
        </tr> 
      </table></td> 
  </tr>
  {if count($BRAND_LIST) > 0}		
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>{if $STORE_PERMISSION.delete == 'Y'}<a class="linkOneActive" href="#" onclick="javascript:if(confirm('Are you sure you want to delete?')){ldelim}document.frm_brand.action='{makeLink mod=$MOD pg=brand}act=delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_brand.submit();{rdelim}">Delete</a>{/if}</td>
    <td width="100%">&nbsp;</td>
    <td nowrap><strong>Results per page:</strong></td>
	<td>{$BRAND_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($BRAND_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_brand,'brand_id[]')"></td>
          <td width="48%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=brand_name display="`$smarty.request.sId` Name"}act=list&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
          <td width="48%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=company_name display="Company Name"}act=list&fId={$smarty.request.fId}&amp&sId={$smarty.request.sId}{/makeLink}</td>
		  </tr>
        {foreach from=$BRAND_LIST item=brand}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="brand_id[]" value="{$brand->brand_id}"></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&brand_id={$brand->brand_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$brand->brand_name} </a></td> 
          <td valign="middle" height="24" align="left">{$brand->company_name}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$BRAND_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
<br>
{if $STORE_PERMISSION.edit == 'Y'}
<table width=80% border=0 align="center" cellpadding=5 cellspacing=0 class=naBrDr>
             <tr>
               <td colspan=3 class="naGridTitle"><span class="group_style">Mass Updates</span></td>
             </tr>
             <tr class=naGrid1>
               <td height="10" colspan="3" align="right" valign=top><div></div></td>
             </tr>
             <tr class=naGrid1>
               <td width="40%" align="right" valign=top>Company Name </td>
               <td width="1" valign=top>:</td>
               <td><input name="company_name" type="text" class="formText" id="company_name" value="" size="30" maxlength="25" ></td>
             </tr>


               <td height="10" colspan=3 valign="center" class="naGrid1"><div></div></td>
             </tr>
             <tr class="naGridTitle">
    <td colspan=3 valign="center"><div align=center>
        <input name="btnsubmit" type=submit class="naBtn" value="Mass Update">
&nbsp;
        <input name="reset" type=reset class="naBtn" value="Reset">
    </div></td>
  </tr></table>
<br />
{/if}
</form>