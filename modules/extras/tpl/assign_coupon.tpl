
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	var fields=new Array('coupoin_id');
	var msgs=new Array('Select Coupon');
</SCRIPT>

<table width="100%"  border="0">
  <tr>
    <td><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="post" enctype="multipart/form-data" name="frm" onSubmit="return chk(this);">
      <tr>
		<td nowrap class="naH1"> Assign Coupon </td> 
          <td nowrap align="right" class="titleLink" colspan="4">&nbsp;</td> 
        </tr>
	<tr align="left">
      <td colspan=5 valign=top>{if $OK=='T'}{messageBox}{/if}</td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=5><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
   <tr class=naGrid2> 
      <td  align="right" valign=top> Coupon </td> 
      <td width=3 valign=top>:</td> 
      <td width="330" colspan="3" align="left">
	  	<select name=coupon_id>
          <option value="">-- SELECT A COUPON --</option>
          	{html_options values=$SECTION_LIST.id output=$SECTION_LIST.coupon_name selected=$smarty.request.coupon_id}
		</select>
	  	<input type="hidden" name="email_id"  value="{$EMAIL_ID}">
	  	<input type="hidden" name="act" value="setuser">
	  </td>
   </tr>
   <tr class=naGrid2>
     <td width="205" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="3" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=5 valign=center>	  <div align=center>	  
	       <input type=submit value="Assign" class="naBtn">&nbsp; 
        </div></td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>