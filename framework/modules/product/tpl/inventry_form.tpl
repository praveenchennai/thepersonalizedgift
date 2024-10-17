{literal}

<style type="text/css">
<!--
.border1 {
	border: 1px solid #FFFFFF;
	background-color:#EFEFEF;
}
-->
</style>


{/literal}

<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk(this);"> 

<input type="hidden" name="tmpcount" value="1"  />

 	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
  <table border=0 width=90% cellpadding="2" cellspacing="2" class="naBrDr"> 
    <tr align="left">
      <td colspan=9 valign=top><table width="100%" align="center">
          <tr>
            <td nowrap class="naH1">Manage Inventry</td>
            <td nowrap align="right" class="titleLink"></td>
			<td nowrap align="right" class="titleLink"></td>
			
          </tr>
      </table></td>
    </tr>
 
  <tr>
  	<td><table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr align="right" class="naGrid1">
    <td width="406">Part Number</td>
    <td width="12">:<input type="hidden" name="part_num" value="{$INVENTRY.part_num}" /></td>
    <td width="144"><input type="text" name="part_num_dummy" value="{$INVENTRY.part_num}" disabled="disabled"/></td>
    <td width="336">&nbsp;</td>
    </tr>
 <tr align="right" class="naGrid2">
   <td width="406">Model Number</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="model_num" value="{$INVENTRY.model_num}" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr align="right" class="naGrid1">
   <td width="406">Description</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="description" value="{$INVENTRY.description}" /></td>
    <td>&nbsp;</td>
    </tr>
 <tr align="right" class="naGrid2">
    <td width="406">Conditon</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="condition" value="{$INVENTRY.conditon}" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr align="right" class="naGrid1">
    <td width="406">Price</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="price" value="{$INVENTRY.price}" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr align="right" class="naGrid2">
    <td width="406">Quoted Price</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="quoted_price" value="{$INVENTRY.quoted_price}" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr align="right" class="naGrid1">
   <td width="406">Aircraft Type</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="aircraft_type" value="{$INVENTRY.aircraft_type}" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr align="right" class="naGrid2">
   <td width="406">Quantity</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="quantity" value="{$INVENTRY.quantity}" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr align="right" class="naGrid2">
   <td width="406">Manufacturer</td>
    <td width="12">:</td>
    <td width="144"><input type="text" name="manufacturer" value="{$INVENTRY.manufacturer}" /></td>
    <td>&nbsp;</td>
  </tr>
	<tr align="right" class="naGrid1">
   		<td width="406" colspan="4">&nbsp;</td>
    </tr>
	<tr align="center" class="naGrid2">
   <td width="406">&nbsp;</td>
    <td width="12">&nbsp;</td>
    <td width="144"><input type="submit" name="submit" value="Update" class="naBtn" /></td>
    <td>&nbsp;</td>
    </tr>
</table>

	</td>
  </tr>
  <tr><td colspan=9 class="msg" align="center" height="30">{$NUMPAD}</td></tr>
	<tr><td colspan=9 valign=center>&nbsp;</td></tr>
</table>

</form>
