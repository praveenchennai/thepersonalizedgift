{literal}
<script language="javascript">
	function check()
	{
		if (document.pckFrm.sub_pack_id.value=="")
		{
			alert("Please select a Package");
			document.pckFrm.sub_pack_id.focus();
			return false;
		}
		if ((document.pckFrm.count1.value=="") || (isNaN(document.pckFrm.count1.value)))
		{
			alert("Please specify a valid No. of Package");
			document.pckFrm.count1.focus();
			return false;
		}

		if ((document.pckFrm.count2.value=="") || (isNaN(document.pckFrm.count2.value)))
		{
			alert("Please specify a valid No. for Reward");
			document.pckFrm.count2.focus();
			return false;
		}
		if (document.pckFrm.type.value=="")
		{
			alert("Please select a Type");
			document.pckFrm.type.focus();
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
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="pckFrm" action="" style="margin: 0px;" onSubmit="return check()"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Referral Package</td> 
      <td align="right"><a href="{makeLink mod="member" pg="referral_admin"}act=list{/makeLink}">List All Referral Packages</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Package Details</span></td> 
    </tr> 
	   <tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style"> <strong>Referral Criteria</strong></div></td> 
      <td width="1%" valign=top>&nbsp;</td> 
      <td width="59%">&nbsp;</td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Subscription Package</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%"><select name="sub_pack_id"   tabindex="5" >
          {html_options values=$SUB_PACK.id output=$SUB_PACK.package_name selected=$REF_RESULT.reg_pack_id}
	  </select> </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">No. of Package</div></td> 
      <td valign=top>:</td> 
      <td><input type="text" name="count1" value="{$REF_RESULT.count}" class="formText" size="10" maxlength="25" >        </td> 
    </tr>

	
	  <tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style"> <strong>Referral Reward</strong></div></td> 
      <td width="1%" valign=top>&nbsp;</td> 
      <td width="59%">&nbsp;</td> 
    </tr> 
	 <tr class=naGrid1>
      <td align="right" valign=top>Reward</td>
      <td valign=top>:</td>
      <td><input type="text" name="count2" value="{$REF_RESULT.reward_count}" class="formText" size="10" maxlength="25" >
	  <select name="type" class="formText" id="type" style="width:115px">
      <option value="">Select Type</option>
	  <option value="M" {if $REF_RESULT.type=='M'} selected {/if}>Month</option>
	  <option value="D" {if $REF_RESULT.type=='D'} selected {/if}>Days</option>
	  <option value="Y" {if $REF_RESULT.type=='Y'} selected {/if}>Year</option>
	  </select>
	  </td>
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Active</div></td> 
      <td valign=top>:</td> 
      <td><input type=checkbox name="active" value="Y" {if $REF_RESULT.active=='Y'} checked {/if}></td> 
    </tr> 
    {if $TYPE_LIST}    {foreach item=mem from=$TYPE_LIST}    {/foreach}
    {/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  <input type="hidden" value="{$REF_ID}" name="ref_id"}>
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>
