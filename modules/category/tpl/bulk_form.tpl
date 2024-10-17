<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table width=80% border=0 align="center" cellpadding=5 cellspacing=0 class=naBrDr>
             <tr>
               <td colspan=3 class="naGridTitle"><span class="group_style">Add/Edit Bulk Price </span></td>
             </tr>
             <tr class=naGrid1>
               <td height="10" colspan="3" align="right" valign=top></td>
             </tr>
             <tr class=naGrid1>
               <td width="40%" align="right" valign=top>Minimum Quatity</td>
               <td width="1" valign=top>:</td>
               <td><input name="min_qty" type="text" class="formText" id="min_qty" value="{$BULK.min_qty}" size="30" maxlength="25" ></td>
             </tr>
             <tr class=naGrid1>
               <td align="right" valign=top>Maximum Quatity </td>
               <td valign=top>:</td>
               <td><input name="max_qty" type="text" class="formText" id="max_qty" value="{$BULK.max_qty}" size="30" maxlength="25" ></td>
             </tr>
             <tr class=naGrid1>
               <td align="right" valign=top>Unit Price </td>
               <td valign=top>:</td>
               <td><input name="unit_price" type="text" class="formText" id="unit_price" value="{$BULK.unit_price}" size="30" maxlength="25" ></td>
             </tr>
			 <tr class="naGridTitle">
    <td colspan=3 valign="center"><div align=center><input name="btnsubmit" type='submit' class="naBtn" value="Submit">&nbsp;<input name="btnsubmit" type='Reset' class="naBtn" value="Reset"></div></td>
  </tr></table>  </form> 
