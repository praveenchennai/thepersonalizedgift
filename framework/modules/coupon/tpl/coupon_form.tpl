<link type="text/css" rel="stylesheet" href="{$smarty.const.SITE_URL}/scripts/ui/datepicker/demos.css"/>
<link type="text/css" rel="stylesheet" href="{$smarty.const.SITE_URL}/scripts/ui/datepicker/themes/base/jquery.ui.all.css"/>

<link type="text/css" rel="stylesheet" href="{$smarty.const.SITE_URL}/scripts/validateform/css/general.css"/>


<script script="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/jquery.js"></script>
<script script="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validation.js"></script>


<script script="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/ui/jquery-1.7.1.js"></script>
<script script="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/ui/jquery.ui.core.js"></script>
<script script="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/ui/jquery.ui.datepicker.js"></script>



<script script="javascript">
{literal}
$(function() {
		$( "#coupon_startdate,#coupon_enddate" ).datepicker( {
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			minDate: 0,
			onSelect: function ()
			{
				$('#coupon_startdate').removeClass('error');
				$('#sdateInfo').html('');
				
			}


		});
	});
	

function showDiscount(type)
{
	
	
	if(type==1)
	{
		$('#discount_amt').show();
		$("#title_discount").html('Percentage:  ');

	}
	else if(type==2)
	{
		$('#discount_amt').show();
		$("#title_discount").html('Amount:  ');
	}
	else
	{
		$('#discount_amt').hide();
		$("#title_discount").html('Amount:  ');
	}
}


{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>

<table width="100%"  border="0">

  <tr>
    <td valign="top"><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
	<form class="cmxform"  method="post" enctype="multipart/form-data" name="couponForm" id="couponForm" >
	<input type="hidden" name="coupon_id" id="coupon_id" value="{$smarty.request.id}" />
{if $onlysubscription eq 'Yes'}
	<input type="hidden" name="substract_from" value="S" />
{/if}

   <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="18%" nowrap class="naH1">Coupon</td>
          <td width="82%" align="right" nowrap class="titleLink"><a href="{makeLink mod=store pg=coupon_coupon}act=list{/makeLink}&sId={$smarty.request.sId}&mId={$MID}">Coupons List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class="{cycle values="naGrid1,naGrid2"}">
    	<td valign=top colspan=4><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=4 ><strong>{if $smarty.request.id eq ''}Add{else}Edit{/if} Coupon</strong></td>
    </tr>
    <tr class="{cycle values="naGrid1,naGrid2"}">
      <td width="30%"  align="right" valign=top>Title:</td>
      <td width="2%" valign=top>&nbsp;</td>
      <td colspan="2" align="left"><div><input name="coupon_title" type="text" class="input" id="coupon_title" value="{$COUPON.coupon_title}" size="30"/> <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Coupon Name: i.e. &quot;10% Discount&quot; " fgcolor="#eeffaa"} /><span id="titleInfo"></span></div></td>
    </tr>
    <tr class="{cycle values="naGrid1,naGrid2"}">
      <td  align="right" valign=top>Coupon Code:</td>
      <td valign=top>&nbsp;</td>
      <td colspan="2" align="left"><div><input name="coupon_code" type="text" class="input" id="coupon_code" value="{$COUPON.coupon_code}" size="30"/> 
        <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Unique coupon code that you will give to your customers to enter into the shopping cart &quot;Coupon Code&quot; field." fgcolor="#eeffaa"} /><span id="codeInfo"></span></div></td>
    </tr>
    <tr class="{cycle values="naGrid1,naGrid2"}"> 
      <td  align="right" valign=top> Start Date:</td> 
      <td width=2% valign=top>&nbsp;</td> 
      <td colspan="2" align="left"><div><input name="coupon_startdate" type="text" class="input" id="coupon_startdate" value="{$COUPON.coupon_startdate}" size="30"  readonly/> <span id="sdateInfo"></span></div></td> 
    </tr>
   <tr class="{cycle values="naGrid1,naGrid2"}">
     <td align="right" valign=top> End Date:</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left"><input name="coupon_enddate" type="text" class="input" id="coupon_enddate" value="{if $COUPON.coupon_enddate gt 0}{$COUPON.coupon_enddate}{/if}" size="30" readonly/>  <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="If end date is empty then the coupon does not expire." fgcolor="#eeffaa"} /></td>
   </tr>
   
    <tr class="{cycle values="naGrid1,naGrid2"}"> 
      <td  align="right" valign=top>Min Amount to use Coupon:</td> 
      <td width=2% valign=top>&nbsp;</td> 
      <td colspan="2" align="left"><div><input name="coupon_min_amount" type="text" class="input" id="coupon_min_amount" value="{$COUPON.coupon_min_amount}" size="30" /><img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Enter the minimum dollar amount to validate coupon." fgcolor="#eeffaa"} /><span id="minAmtInfo"></span></div></td> 
    </tr>
	
   <tr class="{cycle values="naGrid1,naGrid2"}">
     <td align="right" valign=top>This discount coupon can be for:</td>
     <td valign=top>&nbsp;</td>
     <td  align="left" colspan="2">
	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  {foreach from=$COUPON_ITEMS item=temp key=key name=temp_loop}
	  <tr>
	     <td valign="top" height="23" width="5%"><input type="radio" style="width:20px" class="inputradio" name="discount_item_id" id="discount_item_id" value="{$temp.item_id}"  {if $COUPON.discount_item_id eq  $temp.item_id or ($COUPON.discount_item_id eq ''  and  $smarty.foreach.temp_loop.index eq 0)} checked="checked" {/if} /></td>
		<td valign="top"width="95%"><div>{$temp.item_name}{if $temp.help_txt neq ''}&nbsp;<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="`$temp.help_txt`" fgcolor="#eeffaa"} />{/if}</div>&nbsp;</td>
	  </tr>
	  {/foreach}
	 
	</table>

	</td>
     </tr>
	
     <tr class="{cycle values="naGrid1,naGrid2"}"> 
      <td  align="right" valign=top>Discount coupon usage limit :</td> 
      <td width=2% valign=top>&nbsp;</td> 
      <td colspan="2" align="left"><input name="coupon_one_time" type="text" class="input" id="coupon_one_time" value="{if $COUPON.coupon_one_time>0}{$COUPON.coupon_one_time}{/if}" size="30" />  <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="
  Set the number of times a customer can use this coupon code. Leave blank for unlimited." fgcolor="#eeffaa"} /></td> 
    </tr>
   
     <tr class="{cycle values="naGrid1,naGrid2"}"> 
      <td  align="right" valign=top>Discount Type :</td> 
      <td width=2% valign=top>&nbsp;</td> 
      <td colspan="2" align="left"><div><select name="discount_mode_id" id="discount_mode_id" style=" width:195px" onchange="showDiscount(this.value);"> 
	  <option value="">--Select--</option>
	  {foreach from=$COUPON_DISCOUNT_MODE item=temp}
	  <option value="{$temp.mode_id}" {if $COUPON.discount_mode_id eq $temp.mode_id} selected="selected" {/if} >{$temp.mode_value}</option>
	  {/foreach}
	  </select><span id="modeInfo"></span></div></td> 
    </tr>
	<tr class="naGrid2">
		<td colspan="5" ><div  style="display:{if $COUPON.discount_mode_id neq ''}block{else}none{/if}" id="discount_amt"><table width="100%" border="0" cellpadding=2 cellspacing=5 >
		  <tr class="naGrid2"> 
			  <td  align="right"  valign="middle"  width="30%" id="title_discount"  >{if $COUPON.discount_mode_id eq 1}Percentage:{else}Amount:{/if}</td> 
			  <td   valign="middle"   width="2%" >&nbsp;</td> 
			  <td colspan="2" align="left"><input name="coupon_discount" type="text" class="input" id="coupon_discount" value="{$COUPON.coupon_discount}" size="30" /> </td> 
			</tr>
		</table></div>
		</td>
	</tr>
	
	
	 <tr class="{cycle values="naGrid1,naGrid2"}"> 
      <td  align="right" valign=top>Active:</td> 
      <td width=2% valign=top>&nbsp;</td> 
      <td colspan="2" align="left"><input type="radio" style="width:20px" name="coupon_active" id="active1" value="Y" {if $COUPON.coupon_active eq 'Y' or $COUPON.coupon_active eq ''}  checked="checked"{/if}/>Yes&nbsp;&nbsp;<input type="radio"  style="width:20px" name="coupon_active" id="active2" value="N" {if $COUPON.coupon_active eq 'N' }  checked="checked"{/if} />No</td> 
    </tr>
  
    <tr class="naGridTitle"> 
      <td colspan=5 valign=center><div align=center>	 
	  			<input type="submit" value="Submit" class="naBtn" style="width:100px" />
	     <!--  <input type=submit value="{if $smarty.request.id eq ''}Submit{else}Update{/if}" class="naBtn" >-->&nbsp; 
          <input type=reset value="Reset" class="naBtn" style="width:100px"> 
        </div>	 </td> 
    </tr> 
	 <tr><td colspan=3 valign=center>&nbsp;</td></tr> 
	</form> 
</table>
</td>
  </tr>
 
</table>
