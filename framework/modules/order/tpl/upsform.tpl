<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>

<table width="820" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top">
<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frm">
  	<input type="hidden" name="act" value="{$smarty.request.act}">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$smarty.request.mId}">
	<input type="hidden" name="store_id" value="{$smarty.request.store_id}">
	<input type="hidden" name="shipmethod_id" value="{$smarty.request.shipmethod_id}">
	
    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;<!--<a href="{makeLink mod=order pg=order}act=shippingselmethodslist{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a>--></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr class=naGrid2>
      <td valign=top colspan=3 ><strong>UPS Form</strong> </td>
    </tr>
    <tr class=naGrid2>
      <td width="228" align="right" >User Name</td>
      <td valign=top>:</td>
      <td width="518" align="left"><input name="user_name" type="text" class="input" id="user_name" value="{$FORM_VALUES.user_name}" size="30"/>        <font color="#999999" size="-3">&nbsp; </font></td>
      </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top>Password</td> 
      <td width=7 valign=top>:</td> 
      <td align="left"><input name="password" type="text" class="input" id="password" value="{$FORM_VALUES.password}" size="30" /></td> 
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>User Key </td>
      <td valign=top>:</td>
      <td align="left"><input name="user_key" type="text" class="input" id="user_key" value="{$FORM_VALUES.user_key}" size="30" /></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Pickup Type</td>
      <td valign=top>:</td>
      <td align="left">


<select name="upsType">
	{html_options values=$UPS_PICKUP_TYPES.dbvalue selected=$FORM_VALUES.upsType output=$UPS_PICKUP_TYPES.label}
</select>	</td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname"> Package Type</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left">
<select name="upsPackage">
	{html_options values=$UPS_PACKAGE_TYPES.dbvalue selected=$FORM_VALUES.upsPackage output=$UPS_PACKAGE_TYPES.label}
</select>	  </td>
    </tr>
    
    <tr class=naGrid2>
      <td  align="right" valign=middle><span class="fieldname"> Ship Service</span></td>
      <td valign=middle><span class="fieldname">: </span></td>
      <td align="left">

{foreach from=$SHIP_SERVICES item=current}
	<input type="checkbox" name="upsSvcs[]" value="{$current.dbvalue}" {if $current.active == 'Y'}checked{/if} />{$current.label}<br/>
{/foreach}

<!--	  
<input class="Form2" type="checkbox" name="upsSvcs[]" value="01" />UPS Next Day Air &reg;<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="02" />UPS Second Day Air &reg;<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="03" />UPS Ground<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="07" />UPS Worldwide Express<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="08" />UPS Worldwide Expedited<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="11" />UPS Standard<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="12" />UPS 3 Day Select &reg;<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="13" />UPS Next Day Air Saver &reg;<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="14" />UPS Next Day Air &reg; Early A.M. &reg;<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="54" />UPS Worldwide Express Plus<br/>
<input class="Form2" type="checkbox" name="upsSvcs[]" value="59" />UPS 2nd Day Air A.M. &reg;
-->	  </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=middle>&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
   <tr class=naGrid2>
     <td colspan="3" valign=top>	 </td>
     </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center>	  
	       <input type=submit name="Submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>