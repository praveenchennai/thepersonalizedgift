<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">{$smarty.request.sId} </td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=order pg= 	paymentType}act=creditlist&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}"><!--{$smarty.request.sId}-->CreditCard List</a></td>
        </tr>
      </table></td>
    </tr>
	
	 <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Credit Card  Details </span></td> 
    </tr> 

    <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Name</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="name" value="{$PAYMENT.name}" class="formText" size="30" maxlength="150"> </td> 
    </tr> 

    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Image</div></td>
      <td valign=top>:</td>
      <td><input name="logo_extension" type="file" id="logo_extension"></td>
    </tr>
	{if $PAYMENT.logo_extension ne ''}
	<tr class=naGrid1>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><img src="{$GLOBAL.mod_url}/images/paymenttype/{$PAYMENT.id}{$PAYMENT.logo_extension}" width="50" height="30"></td>
    </tr>
	 {/if}
	 <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  <input type="hidden" name="id" value="{$PAYMENT.id}">
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
