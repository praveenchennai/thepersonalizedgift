<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript" type="text/javascript">
function postPaymentForm(Formname,Action) {
	if(Action == 'Activate') {
		actionstr	=	'Enable';
		Formname.Action.value = 'Activate';    //document.eval(Formname)
		
/*		var temp = new Array();	
		var checks = document.getElementsByName('creditcards[]');
		arrindx	=	0;
		for(i=0; i<checks.length; i++) {
			if(checks[i].checked == true) {
				temp[arrindx]	=	checks[i].value;
				arrindx++;
			}
		}
		document.eval(Formname).CreditCards.value = temp.join('^*^');  */
	}	

	if(Action == 'Deactivate') {
		actionstr	=	'Disable';
		Formname.Action.value = 'DeActivate'; 	  //document.eval(Formname)
	}	
	
	if(confirm('Are you sure you want to '+ actionstr +' this payment method?')) {
		Formname.submit();  //document.eval(Formname)
		return true;
	} else {
		return false;
	}	
	
}
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>

<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Accessory-->{$smarty.request.sId}
		  </td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
		<tr> 
          <td nowrap >&nbsp;</td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr>


  <tr>
   <td valign=top class="naGrid1">

<form name="frm_accessory" method="post" action="" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="7%">{if $STORE_OWNER eq "store"}Store:{/if}</td>
   <td width="60%">
   {if $STORE_OWNER eq "store"}
	  <select name=store_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=paymentlist&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}&store_id='+this.value">
       {html_options values=$STORES.id output=$STORES.name selected=`$SELECTED_STORE_ID`}
	   </select>
	
	{/if}
	</td>
	  <td width="30%" align="right" >{if $STORE_OWNER eq "admin"} Payment By Admin  {/if}
	  </td>
	</tr>
</table>
</form>
	</td>
</tr> 
  <tr>
    <td align="right">

		
		
<br>		
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr height="30">
    <td align="right"><a href="{makeLink mod=order pg=paymentType}act=creditlist&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">List of all Credit Cards</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>

	</td>
  </tr>
  
  
  
  <tr> 
    <td>
	<table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($PAY_METHODS) > 0}
	    <tr>
	      <td width="4%" height="25" align="center" nowrap class="naGridTitle">&nbsp;</td>
          <td width="24%" height="25" align="left" nowrap class="naGridTitle">{makeLink mod=order pg=payment orderBy=paymethod_name display="`$smarty.request.sId` Method Name" } act=paymentlist&subact=paymentlist&sId={$smarty.request.sId}&store_name={$smarty.request.store_name}&fId={$smarty.request.fId}&mId={$MID} {/makeLink} </td> 
          <td width="72%" height="25" align="left" nowrap class="naGridTitle">Description</td>
          </tr>
     
	 


{foreach from=$PAY_METHODS item=method name=foo}
<form action="index.php" name="formpgs{$smarty.foreach.foo.index}" method="post">
	<input type="hidden" name="mod" value="{$MOD}">
	<input type="hidden" name="pg" value="{$PG}">
	<input type="hidden" name="act" value="form">
	<input type="hidden" name="storeowner" value="{$STORE_OWNER}">
	<input type="hidden" name="store_id" value="{$SELECTED_STORE_ID}">
	<input type="hidden" name="Action" value="">
	<input type="hidden" name="CreditCards" value="">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$MID}">
	

	{if $method.paymethod_id neq $PAY_METHODID}
		<input type="hidden" name="paymethod_id" value="{$method.paymethod_id}">
	{/if}	
	
	<tr class="{cycle name=bg values="naGrid1,naGrid2"}">
	  <td height="25" align="center"  valign="middle">
	  {if $method.paymethod_id == $PAY_METHODID}
		<input  style="border:0px" type="image" name="Submit" src="{$GLOBAL.tpl_url}/images/grid/Active.gif" title="Disable Payment Gateway" onClick="return postPaymentForm(this.form,'Deactivate')">
	  {else}
		<input style="border:0px" type="image" name="Submit" src="{$GLOBAL.tpl_url}/images/grid/Inactive.gif" title="Enable Payment Gateway" onClick="return postPaymentForm(this.form,'Activate')">
	  {/if}	  </td> 
	  <td height="25" align="left"><a href="{if $method.paymethod_id == $PAY_METHODID}{makeLink mod=$MOD pg=$PG}act=paymentdetailsform&storeowner={$STORE_OWNER}&store_id={$SELECTED_STORE_ID}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}{else}#{/if}" class="linkOneActive">{$method.paymethod_name}</a></td>
	  <td height="25" align="left">{$method.paymethod_description}</td> 
	</tr> 
</form>	
{/foreach}
		

		
		
		
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$ACCESSORY_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
	
	<tr class="naGrid2">
		<td colspan="3" class="naError" align="center" height="30">
		<form name="form2" method="post" style="margin:0px">
			<table width="100%" cellpadding="0" cellspacing="0">
				
				<tr>
				  <td width="4%" align="center" nowrap class="naGridTitle">&nbsp;</td>
				  <td height="24" colspan="2" align="left" nowrap class="naGridTitle">
				  {if $PAYMENT_METHOD_NAME neq ''}
					  Available Credit Cards for {$PAYMENT_METHOD_NAME}
				  {/if}
				  </td>
				</tr>
			{if count($CREDITCARD_LIST) > 0}
				{foreach from=$CREDITCARD_LIST item=creditcard}
				<tr class="{cycle name=bg values="naGrid1,naGrid2"}">
				  <td  valign="middle"  align="center">&nbsp;</td>
				  <td width="48%" height="35" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=order pg=paymentType}act=creditform&id={$creditcard.id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$creditcard.name} </a></td> 
				  <td height="35" align="left" nowrap>{if $creditcard.logo_extension ne ''} <img src="{$GLOBAL.modbase_url}/order/images/paymenttype/{$creditcard.id}{$creditcard.logo_extension}" width="50" height="30">{/if}</td>
				</tr> 
				{/foreach}
			{elseif count($CREDITCARD_LIST) eq 0}
					<tr class="naGrid2"> 
					  <td colspan="3" class="naError" style="font-size:13px" align="center" height="30">Enable Payment Gateway</td>
					</tr>
			{/if}
			</table>
		</form>
		
		
		</td>
	</tr>
		
		

		
		
		
      </table></td> 
  </tr>
  </table>
  <br>
  <br />
