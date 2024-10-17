<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;">
<table border=0 width=95% cellpadding=5 cellspacing=1 class=naBrDr> 
	<tr class=naGrid1>
      <td width="189" align="right" valign=top><div align=right class="element_style">Adjust Price</div></td>
      <td width="12" valign=top>:</td>
	  <td width="213">
	  {if $PRODUCTS_ACCESSORY.adjust_price ne ''}
	  {  assign var='adjustPrice' value= $PRODUCTS_ACCESSORY.adjust_price }
	  {  assign var='adjustPriceChk' value= 'checked' }
	  {else}
	  {  assign var='adjustPrice' value= $ACCESSORY.adjust_price }
	  {  assign var='adjustPriceChk' value= '' }
	  {/if}
	  
	   {if $PRODUCTS_ACCESSORY.adjust_weight ne ''}
	  {  assign var='adjustWeight' value= $PRODUCTS_ACCESSORY.adjust_weight }
	  {  assign var='adjustWeightChk' value= 'checked' }
	  {else}
	  {  assign var='adjustWeight' value= $ACCESSORY.adjust_weight }
	  {  assign var='adjustWeightChk' value= '' }
	  {/if}
	  <input type="text" name="adjust_price" value="{$adjustPrice}" class="formText"  style="width:200" maxlength="150"></td>
      <td width="270"><label>
        <input type="checkbox" name="chk_adjust_price" {$adjustPriceChk} value="1" />
      Only for this product </label></td>
	</tr>
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Adjust Weight</div></td>
      <td valign=top>:</td>
      <td><input type="text" name="adjust_weight" value="{$adjustWeight}" class="formText"  style="width:200" maxlength="150"></td>
      <td><input type="checkbox" name="chk_adjust_weight" {$adjustWeightChk} value="1" /> 
        Only for this product </td>
    </tr>
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center> 
	  		<input type="hidden" name="id" value="{$smarty.request.id}">
			<input type="hidden" name="prd_id" value="{$smarty.request.prd_id}">
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
		  <input type=button value="Close" class="naBtn" onClick="window.close();">  
        </div></td> 
</table>
</form> 