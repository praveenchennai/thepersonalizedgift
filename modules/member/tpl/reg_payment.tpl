<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript">

String.prototype.trim = function() {
	a = this.replace(/^\s+/, '');
	return a.replace(/\s+$/, '');
};

function convertNumberToDecimal(amount, precision)
{
	var num = new Number(amount);
	return num.toFixed(precision);
}

function checkPromoCode(PromoElementObj)
{
	
	document.getElementById('loadinginfo').innerHTML	=	'Validating Coupon Code..';
	
	document.frmReg.tot_amt.value			=	document.frmReg.orig_tot_amt.value;
	document.frmReg.UPGRADE_TOT_AMT.value	=	document.frmReg.orig_UPGRADE_TOT_AMT.value;
	
	document.getElementById('totalblock').innerHTML		=	'$' + convertNumberToDecimal(document.frmReg.orig_tot_amt.value, 2);
	
	//document.getElementById('deductblock1').style.display	=	'none';
	//document.getElementById('deductblock2').style.display	=	'none';
	
	promo_code	=	PromoElementObj.value.trim();
	
	if(promo_code == '') {
		PromoElementObj.value	=	'';
		document.getElementById('loadinginfo').innerHTML	=	'';
		return;
	}
	
	//document.getElementById('btn_save').disabled		=	'disabled';
	
	{/literal}
	XHRObj	=	newXMLHttpRequest();
	XHRObj.onreadystatechange = getReadyStateHandler(XHRObj, promoCodeResponse);
	Params	=	'PromotionCode=' + promo_code + '&user_id=' + {$smarty.request.user_id} {if $SUBPACK_ID neq ''} + '&sub_pack=' +{$SUBPACK_ID}{/if} {if $PACKAGE_ID neq ''} + '&reg_pack=' +{$PACKAGE_ID}{/if};
	XHRObj.open("POST", "{makeLink mod=member pg=ajax_store}act=check_promocode{/makeLink}&" + Math.random());
	XHRObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	XHRObj.send(Params);
	{literal}
}

function promoCodeResponse(responsePromoStatus)
{
	var	ResultArray		=	responsePromoStatus.split('|');
	promostatus			=	ResultArray[0].trim();
	deductionamount		=	convertNumberToDecimal(parseFloat(ResultArray[1].trim()),2);
	RegDeductionamount	=	convertNumberToDecimal(parseFloat(ResultArray[2].trim()),2);
	
		
	if(promostatus == 'YES') {
		totalamount		=	document.frmReg.tot_amt.value;
		//totalamount		=	convertNumberToDecimal((totalamount - deductionamount), 2)
		
		packageamount	=	parseFloat(document.frmReg.UPGRADE_TOT_AMT.value);
		packageamount	=	convertNumberToDecimal((packageamount - deductionamount), 2);
		
		regamount		=	parseFloat({/literal}{$REGISTRATION_AMT}{literal});
		regamount		=	convertNumberToDecimal((regamount - RegDeductionamount), 2);
		subpackamount	=	parseFloat({/literal}{$SUBDCRIPTION_AMT}{literal});
		subpackamount	=	convertNumberToDecimal((subpackamount - deductionamount), 2);
		newtotalAmt		=	convertNumberToDecimal((parseFloat(subpackamount) + parseFloat(regamount)), 2)
		totalDeduction	=	convertNumberToDecimal((parseFloat(deductionamount) + parseFloat(RegDeductionamount)), 2)
				
		
		document.getElementById('totaldeduction_setup').innerHTML		=	'$' + RegDeductionamount;
		document.getElementById('totaldeduction_subscription').innerHTML=	'$' + deductionamount;
		document.getElementById('totaldeduction').innerHTML				=	'$' + totalDeduction;
		document.getElementById('totalblock').innerHTML					=	'$' + newtotalAmt;
		document.getElementById('loadinginfo').innerHTML		=	'';	
		
		document.frmReg.subscription_discount.value		=	convertNumberToDecimal(deductionamount, 2);
		document.frmReg.tot_amt.value					=	convertNumberToDecimal(newtotalAmt, 2);
		document.frmReg.reg_amt.value					=	convertNumberToDecimal(regamount, 2);
		document.frmReg.subpack_amt.value				=	convertNumberToDecimal(subpackamount, 2);
		document.frmReg.UPGRADE_TOT_AMT.value			=	convertNumberToDecimal(packageamount, 2);
		document.getElementById('hid_div').style.display = 'inline';
		
		
	}
	
	if(promostatus == 'NO') {
		document.getElementById('loadinginfo').innerHTML	=	'Enter Valid Coupon Code..';
		document.frmReg.promo_code.value					=	'';	
		document.frmReg.subscription_discount.value			=	'';	
		
		document.getElementById('totaldeduction_setup').innerHTML		=	'$0.00';
		document.getElementById('totaldeduction_subscription').innerHTML=	'$0.00';
		document.getElementById('totaldeduction').innerHTML				=	'$0.00';
		document.frmReg.tot_amt.value					=	{/literal}{$TOT_AMT}{literal};
		document.frmReg.reg_amt.value					=	{/literal}{$REGISTRATION_AMT}{literal};
		document.frmReg.subpack_amt.value				=	{/literal}{$SUBDCRIPTION_AMT}{literal};
	}
	
	document.getElementById('btn_save').disabled		=	'';
	
}

function validatePromoCode()
{
	checkPromoCode(document.frmReg.promo_code);
}




function browser_check(){
if (navigator.appName=="Netscape"){
history.back();
}else{
history.go(-1);
}
}
	function checkEntry(){
	if(document.frmReg.agreed.checked==true)
	{
	return true;
	}
	else{
	alert("Please confirm by checking the checkbox");
	return false;}
	}
</script>
{/literal}
<form name="frmReg" id="frmReg" method="post" action="" onsubmit="return checkEntry()" > 
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td height="45" colspan="2" align="left" valign="middle" class="greyboldext" >Retail Web-Store Registration Details</td>
              </tr>
			   <tr>
   					 <td height="2" colspan="3" valign="top"><div class="hrline"></div></td>
		  </tr>
              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="18" colspan="6" class="bodytext"><div align="center">{messageBox}</div></td>
                  </tr>
				  <tr align="left">
				    <td height="18" colspan="6" class="styletext">{$MSGBODY}</td>
			      </tr>
                   <!--<tr>
                    <td class="bodytext">&nbsp;</td>
                    <td height="18" class="styletext" colspan="4">&nbsp;</td>
                  </tr>
				 
                  <tr>
                    <td class="bodytext">&nbsp;</td>
                    <td height="18" class="styletext" colspan="4">Press the 'Make Payment' button below to make payment and complete the registration process. All fees are paid via PAYPAL.</td>
                  </tr>
				  -->
					<!-- coupon validation -->
					<tr>
                    <td width="4" align="left" valign="middle" class="bodytext"></td>
                    <td height="18" colspan="4" align="left" valign="middle" class="bodytext">
					<!-- <form name="frmReg" id="frmReg" method="post" action="" > -->
					<table width="100%"  border="0">
					<tr>
                        <td width="37%" align="left"><strong>Have a Coupon Code? Enter it here</strong></td>
                        <td width="63%"  align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="37%" align="left">
                          <label>
                          <input name="promo_code" id="promo_code" type="text" class="input_list2" /> </label>
						 <br> <strong><span id="loadinginfo" style="font-size:11px;" class="lbClass"></span></strong>						  </td>
                        <td width="63%"  align="left"><a href="javascript:void(0);" onclick="validatePromoCode();">Validate Coupon</a></td>
                      </tr>
					
					  <tr>
					  
                        <td colspan="2">
							<div style="display:none" id="hid_div">
									<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="37%" align="right" valign="top"><strong>Set-Up  Discount</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td width="63%" align="left" valign="top"><div id="totaldeduction_setup" style="font-weight:bold;">$0.00</div></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Subscription Discount</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td align="left" valign="top"><div id="totaldeduction_subscription" style="font-weight:bold; ">$0.00</div></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Total Discount</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td align="left" valign="top"><div id="totaldeduction" style="font-weight:bold; ">$0.00</div></td>
  </tr>
</table>

									
							</div>
						</td>
					  </tr>
					 
					   <tr>
                        <td width="37%" align="right"><strong>Total Fee</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
                        <td width="63%"  align="left"><div id="totalblock" style="font-weight:bold; ">${$TOT_AMT|string_format:"%.2f"}</div> </td>
                      </tr>
                      <tr align="right">
                        <td colspan="2"><div id="deductblock1" style="display:none;"><em style="font-size:11px;"></em></div></td>
                      </tr>
					   <tr align="right">
                        <td colspan="2"></td>
                      </tr>
                    </table>
					<!-- </form> -->
					</td>
                    <td width="14" class="bodytext">&nbsp;</td>
                  </tr>
				  
				  
				  <tr>
                    <td width="4" align="left" valign="middle" class="bodytext"></td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;					</td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" align="left" valign="middle" class="bodytext"></td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td width="14" class="bodytext">&nbsp;</td>
                  </tr>
					<!--  end coupon validation -->
                  <tr>
                    <td width="4" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="30%">&nbsp;</td>
                          <td width="12%" align="left" valign="top">
                            <input name="btn_save" type="image" src="{$GLOBAL.tpl_url}/images/confirm.jpg"  id="btn_save"  value="Make Payment" style="width:125">&nbsp;</td>
							<td>&nbsp;</td>
                          <td width="65%" align="left" valign="top"><img  name="btn_back" id="btn_back" src="{$GLOBAL.tpl_url}/images/back.jpg"  style="width:125" onClick="browser_check();">
                         </td>
                        </tr>
                    </table></td>
                    <td width="14" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle" class="bodytext">
						<input name="tot_amt" type="hidden" id="tot_amt" value="{$REGISTRATION_AMT+$SUBDCRIPTION_AMT}">
						<input name="reg_amt" type="hidden" id="reg_amt" value="{$REGISTRATION_AMT}">
						<input name="subpack_amt" type="hidden" id="subpack_amt" value="{$SUBDCRIPTION_AMT}">
						<input type="hidden" name="subscription_discount" value="" />
						<input name="orig_tot_amt" type="hidden" id="orig_tot_amt" value="{$TOT_AMT}">
						<input name="orig_UPGRADE_TOT_AMT" type="hidden" id="orig_UPGRADE_TOT_AMT" value="{$UPGRADE_TOT_AMT}">
						<input name="upgrade" type="hidden" id="upgrade" value="{$UPGRADE}">
						<input name="UPGRADE_TOT_AMT" type="hidden" id="UPGRADE_TOT_AMT" value="{$UPGRADE_TOT_AMT}">
						<input type="hidden" name="no_reg_flg" value="{$smarty.request.no_reg_flg}">
						</td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td width="273" class="bodytext">&nbsp;</td>
                    <td width="256" class="bodytext">&nbsp;</td>
                    <td class="bodytext">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
  </table>
</form>
