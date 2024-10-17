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
      <td valign=top colspan=3 >Canada Post  Form </td>
    </tr>
    <tr class=naGrid2>
      <td align="right" >CanadaPost Server </td>
      <td width="6" valign=top>&nbsp;</td>
      <td align="left"><input name="canadaPost_server" type="text" class="input" id="canadaPost_server" value="{$FORM_VALUES.canadaPost_server}" size="40"/><br />
        &nbsp;<font color="#999999">Ex: sellonline.canadapost.ca</font></td>
    </tr>
    <tr class=naGrid2>
      <td align="right" >Customer ID </td>
      <td valign=top>:</td>
      <td width="536" align="left"><input name="customer_id" type="text" class="input" id="customer_id" value="{$FORM_VALUES.customer_id}" size="30"/><br />
        &nbsp;<font color="#999999">Ex: CPC_DEMO_XML</font></td>
      </tr>
    
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">City</span></td>
      <td valign=top><span class="fieldname">:</span></td>
      <td align="left"><span class="formfield">
        <input name="canadaPostCity" type="text" class="Form2" id="canadaPostCity" value="{$FORM_VALUES.canadaPostCity}" size="25" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">State</span></td>
      <td valign=top><span class="fieldname">:</span></td>
      <td align="left"><span class="formfield">
        <input name="canadaPostState" type="text" class="Form2" id="canadaPostState" value="{$FORM_VALUES.canadaPostState}" size="25"/>
      </span></td>
    </tr>
    
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">Country</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left"><span class="formfield">

<select name="canadaPostCountry" class="Form2" id="canadaPostCountry">
	{html_options values=$COUNTRIES.dbvalue output=$COUNTRIES.label selected=$FORM_VALUES.canadaPostCountry}
</select>

      </span></td>
    </tr>

<tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname"> Zip/Postal Code</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left"><span class="formfield">
        <input name="canadaPostZip" type="text" class="Form2" id="canadaPostZip" value="{$FORM_VALUES.canadaPostZip}" size="10" maxlength="7" />
      </span></td>
    </tr>


    <tr class=naGrid2>
      <td  align="right" valign=middle><span class="fieldname"> Domestic Ship Service</span></td>
      <td valign=middle><span class="fieldname">: </span></td>
      <td align="left">
{foreach from=$CANADAPOST_DOMESTIC_SERVICES item=service}
	<input class="Form2" type="checkbox" name="canadaDomSvcs[]" value="{$service.dbvalue}" {if $service.active eq 'Y'}checked{/if} />{$service.label}<br />
{/foreach}	  
	 </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=middle>International Ship Service </td>
      <td valign=middle>:</td>
      <td align="left">

{foreach from=$CANADAPOST_INTNL_SERVICES item=service}
	<input class="Form2" type="checkbox" name="canadaIntlSvcs[]" value="{$service.dbvalue}" {if $service.active eq 'Y'}checked{/if} />{$service.label}<br/>
{/foreach}

	 </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=middle>USA Ship Service </td>
      <td valign=middle>:</td>
      <td align="left">

{foreach from=$CANADAPOST_USA_SERVICES item=service}
	<input class="Form2" type="checkbox" name="canadaUsaSvcs[]" value="{$service.dbvalue}" {if $service.active eq 'Y'}checked{/if} />{$service.label}<br/>
{/foreach}


	</td>
    </tr>
    
   <tr class=naGrid2>
     <td colspan="3" valign=top>&nbsp;	 </td>
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