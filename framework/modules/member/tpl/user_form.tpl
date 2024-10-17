<script language="javascript">
{literal}
function showPopup(static) {
window.open( "{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=subscr_log&id={$smarty.request.id}{/makeLink}{literal}", "", 
"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )

}
{/literal}
</script>


<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">User Management</td> 
      <td align="right"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">View All</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{messageBox}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">User Details</span></td> 
    </tr> 
	{if $USER.company_name != ''}
	<tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style">Company Name:</div></td> 
      <td width="1%" valign=top>&nbsp;</td> 
      <td width="59%">{$USER.company_name} </td> 
    </tr> 
	{/if}
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Username:</div></td> 
      <td width="1%" valign=top>&nbsp;</td> 
      <td width="59%">{$USER.username} </td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style">Password:</div></td> 
      <td width="1%" valign=top>&nbsp;</td> 
      <td width="59%">****** {*$USER.password*} </td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> First Name:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.first_name}</td> 
    </tr>
		
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> Last Name:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.last_name}  </td> 
    </tr> 
	{if $NO_DOB eq 'Y'}
	   <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Account Name:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input type="text" name="account_name" value="{$USER.account_url}" maxlength="20" size="20"> </td> 
    </tr> 
	
	{/if}
	{if $NO_DOB eq 'Y'}

    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Account URL:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>http://{$USER.account_url}.{$DOMAIN_URL} </td> 
    </tr> 
	
	{/if}

	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Email:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.email} </td> 
    </tr> 
	
	{if $NO_DOB eq 'Y'}
	   <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Hear about us:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.hear_about_us}</td> 
    </tr> 
	{/if}
	{if $NO_DOB eq 'Y'}

    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Referral Name:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.referral_name}</td> 
    </tr> 
	
	{/if}
	
	{if $dobshow eq 'Y'}
	
	{if $NO_DOB ne 'Y'}
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Date of Birth:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{if $MOD_HEALTH eq '1'}{$USER.dob|date_format:"%m/%d/%Y"}{else}{$USER.dob|date_format}{/if} </td> 
    </tr> 
	{/if}
	
	{if $NO_DOB ne 'Y'}
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Gender:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.gender} </td> 
    </tr> 
	{/if}
	{/if}
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Address:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.address1} {if $USER.address2} <br>{$USER.address2}{/if}</td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">City:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.city} </td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Country:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.country_name} </td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">State:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.state} </td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Zipcode:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.postalcode} </td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Telephone:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.telephone} </td> 
    </tr> 
	
	<!--<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Mobile:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.mobile} </td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Fax:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$USER.fax} </td> 
    </tr> -->
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Member Since:</div></td> 
      <td valign=top>&nbsp;</td> 
       <td>{if $MOD_HEALTH eq '1'}{$USER.joindate|date_format:"%m-%d-%Y"}{else}{$USER.joindate|date_format}{/if} </td>
    </tr> 
   {if $USER.reg_pack}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Registration Pack:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><!-- {$PCK.package_name} (${$PCK.fee})  -->
	   <select name="reg_pack" >
        <option value="">--Packages--</option>
	       {html_options values=$REGPACKG.id output=$REGPACKG.name selected=$USER.reg_pack}
      </select>
	  </td> 
    </tr> 

	{/if}
	
	{if $USER.sub_pack}
		<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Subscription Pack:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><select name="sub_pack" >
        <option value="">--Select--</option>
	       {foreach from=$SUB_PACKAGE item=row}
		   <option value="{$row->sub_id}" {if $row->sub_id eq $SUB_PACK} selected="selected" {/if} >{$row->name}</option>
		   {/foreach}
      </select>
	  </td> 
    </tr> 
	
	
		<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Stop Subscription:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input type="checkbox"  name="stop_subs" value="Y" class="formText" {if $USER.stop_subs eq 'Y'} checked="checked" {/if} /></td> 
    </tr> 
	
	{/if}
	
	
	{if $USER.sub_pack}
	
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Subscription Start Date:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><!--{$START_DATE|date_format} -->{$USER.joindate|date_format} &nbsp;&nbsp;&nbsp; <a href="#" onClick="javacript:showPopup('static');return false;">View All Subscriptions</a>

	  </td> 
    </tr>
	
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Subscription Status:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><!-- {$SUB_STATUS} --> Expires On 
	<select name="end_year"  >
	<option value="">--Year--</option>
	{html_options values=$YEARS output=$YEARS selected=$SUB_EYEAR}
	</select>
<select name="end_month">
<option value="">--Month--</option>
<option value="01" {if $SUB_EMONTH eq '01'} selected {/if}>January</option>
<option value="02" {if $SUB_EMONTH eq '02'} selected {/if}>February</option>
<option value="03" {if $SUB_EMONTH eq '03'} selected {/if}>March</option>
<option value="04" {if $SUB_EMONTH eq '04'} selected {/if}>April</option>
<option value="05" {if $SUB_EMONTH eq '05'} selected {/if}>May</option>
<option value="06" {if $SUB_EMONTH eq '06'} selected {/if}>June</option>
<option value="07" {if $SUB_EMONTH eq '07'} selected {/if}>July</option>
<option value="08" {if $SUB_EMONTH eq '08'} selected {/if}>August</option>
<option value="09" {if $SUB_EMONTH eq '09'} selected {/if}>September</option>
<option value="10" {if $SUB_EMONTH eq '10'} selected {/if}>October</option>
<option value="11" {if $SUB_EMONTH eq '11'} selected {/if}>November</option>
<option value="12" {if $SUB_EMONTH eq '12'} selected {/if}>December</option>
</select>
	
	<select name="end_day"  >
	<option value="">--Date--</option>
	{html_options values=$DAYS output=$DAYS selected=$SUB_EDATE}
	</select>
	  </td> 
    </tr> 
	{/if}
	{if $ARTIST_GROUP eq 'Yes'}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Artist Group:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input type="checkbox" name="artist_id"  {if $USER.artist_id eq 1} checked {/if} value="1"></td> 
    </tr>  
	{/if}
	{if $NO_DOB eq 'Y'}
	{if $ARB_ID ne ''}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">ARB Subscription ID:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$ARB_ID} </td> 
    </tr> 
	{/if}
	{/if}
		
	{if $USER.promo_code neq ''}
	<tr class=naGrid2> 
		<td valign=top><div align=right class="element_style">Promo Code:</div></td>
		<td valign=top>&nbsp;</td> 
		<td>{$USER.promo_code} </td> 
	</tr>
	{/if}
	
	{if $USER.subscription_discount neq ''}
	<tr class=naGrid2> 
		<td valign=top><div align=right class="element_style">Deduction in Subscription:</div></td>
		<td valign=top>&nbsp;</td> 
		<td>${$USER.subscription_discount|string_format:"%.2f"}</td> 
	</tr>
	{/if}
	{if $SPECIAL_DISCOUNT eq 'Y'}
	<tr class=naGrid1> 
		<td valign=top><div align=right class="element_style">Special Discount:</div></td>
		<td valign=top>&nbsp;</td> 
		<td>{$USER.sp_discount|string_format:"%.2f"}</td> 
	</tr>
	{/if}
    <tr class="naGridTitle">
      <td colspan=3 valign=center><div  style=" margin-left:180px"> 
	  <input type="hidden" name="id" value="{$USER.id}">
          <input name="Button" type=button class="formbutton" value="Go Back" onClick="javascript:history.go(-1)">
		  {if $USER.sub_pack} 
		  <input type="submit" name="btn_upgrade" class="formbutton" value="Submit">
		  {/if}
		  &nbsp;&nbsp;
		   {if $USER.active eq 'N'}  
		  <input type="button" class="formbutton" value="Resend Activation Email" onclick="window.location='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=reacivationmail&user_id={$smarty.request.id}&txtsearch={$smarty.request.txtsearch}{/makeLink}'">
		  {/if}
		  <input type="button" class="formbutton" value="Change Email Address" onclick="window.location='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=change_email&user_id	={$smarty.request.id}&txtsearch={$smarty.request.txtsearch}{/makeLink}'">
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>