<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr align="center"> 
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
    <tr align="left">
      <td colspan=3 valign=top>&nbsp;</td>
    </tr>
	
	 <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Gateway  Details </span></td> 
    </tr> 
  <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Gateway</div></td> 
      <td width=1 valign=top>:</td> 
	  {if $GATEWAY_DATA.gateway eq 'paypal_standard' }
	  {assign var='flag1' value='selected'}
	  {/if}
	  {if $GATEWAY_DATA.gateway eq 'paypal_pro' }
	  {assign var='flag2' value='selected'}
	  {/if}
	  {if $GATEWAY_DATA.gateway eq 'authorize.net' }
	  {assign var='flag3' value='selected'}
	  {/if}
	  {if $GATEWAY_DATA.gateway eq 'linkpoint' }
	  {assign var='flag4' value='selected'}
	  {/if}
      <td> <select name="gateway">
	   		<option value="" >--- Gateway---</option>
           <option value="paypal_standard" {$flag1}>Paypal Standard</option>
           <option value="paypal_pro" {$flag2}>Paypal Pro</option>
		   <option value="authorize.net" {$flag3}>Authorize.net</option>
		   <option value="linkpoint" {$flag4}>LinkPont</option>
		   </select>  
	  
	   </td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Login</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="login" value="{$GATEWAY_DATA.login}" class="formText" size="30" maxlength="150"> </td> 
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Password</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="password" value="{$GATEWAY_DATA.password}" class="formText" size="30" maxlength="150"> </td> 
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Transaction Key</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="transaction_key" value="{$GATEWAY_DATA.transaction_key}" class="formText" size="30" maxlength="150"> </td> 
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Transaction Type </div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="transaction_type" value="{$GATEWAY_DATA.transaction_type}" class="formText" size="30" maxlength="150"> </td> 
    </tr> 
	

<!--    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Image</div></td>
      <td valign=top>:</td>
      <td><input name="logo_extension" type="file" id="logo_extension"></td>
    </tr>
	{if $PAYMENT.logo_extension ne ''}
	<tr class=naGrid1>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><img src="{$GLOBAL.mod_url}/images/paymenttype/{$PAYMENT.id}{$PAYMENT.logo_extension}"></td>
    </tr>
	 {/if}-->
	 <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  <input type="hidden" name="id" value="{$GATEWAY_DATA.id}">
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
