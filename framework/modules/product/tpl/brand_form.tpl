<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&mId={$MID}{/makeLink}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	 <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">{$smarty.request.sId} Details</span></td> 
    </tr> 
    <tr class=naGrid1>
      <td height="10" valign=top width=40%>&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td></td>
    </tr>
	{if $FIELDS.0==Y}
    <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">{$smarty.request.sId} Name</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="brand_name" value="{$BRAND.brand_name|escape:'html'}" class="formText" size="33" maxlength="150"> </td> 
    </tr> 
	{/if}
	{if $FIELDS.1==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Company</td>
      <td valign=top>:</td>
      <td><input type="text" name="company_name" value="{$BRAND.company_name|escape:'html'}" class="formText" size="33" maxlength="150"></td>
    </tr>
	{/if}
	{if $FIELDS.2==Y}
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Description </div></td>
      <td valign=top>:</td>
      <td><textarea name="brand_description" cols="30" rows="4" class="formText">{$BRAND.brand_description|escape:'html'}</textarea></td>
    </tr>
	 {/if}
	{if $FIELDS.3==Y}
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Logo</div></td>
      <td valign=top>:</td>
      <td><input name="brand_logo" type="file" id="brand_logo"></td>
    </tr>
	{if $BRAND.brand_logo ne ''}
	<tr class=naGrid1>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><img src="{$smarty.const.SITE_URL}/modules/product/images/brand/thumb/{$BRAND.brand_id}{$BRAND.brand_logo}"></td>
    </tr>
	 {/if}
	 {/if}
	 <tr class="naGrid1">
	   <td height="10" colspan=3 valign=center>&nbsp;</td>
    </tr>
	{if $STORE_PERMISSION.edit == 'Y'}
	 <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  <input type="hidden" name="brand_id" value="{$BRAND.brand_id}">
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
    </tr>{/if} <tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
