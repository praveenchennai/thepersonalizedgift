{literal}
<script language="javascript">
	function check()
	{
		if (document.pckFrm.name.value=="")
		{
			alert("Please specify a Package Name");
			document.pckFrm.name.focus();
			return false;
		}
		else if ((document.pckFrm.duration.value=="") || (isNaN(document.pckFrm.duration.value)) || (document.pckFrm.duration.value==0))
		{
			alert("Please specify a valid Duration");
			document.pckFrm.duration.focus();
			return false;
		}
		else if (document.pckFrm.type.value=="")
		{
			alert("Please specify a Duration Type");
			document.pckFrm.type.focus();
			return false;
		}
		else if ((document.pckFrm.fees.value=="") || (isNaN(document.pckFrm.fees.value)))
		{
			alert("Please specify a valid Registration Fee");
			document.pckFrm.fees.focus();
			return false;
		}
		
		else
		{
			if (document.pckFrm.fees.value==0)
			{
				return confirm("You are about to save this package with no registration fees.Please Conifrm");
			}
			else
			{
				return true;
			}
		}
		return true;
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
      <td colspan="2" align="left" nowrap class="naH1">Subscription Packages</td> 
      <td align="right"><a href="{makeLink mod="member" pg="user"}act=sub_list{/makeLink}">List All Subscriptions</a></td> 
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
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style">Package Name</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%"><input name="name" type="text" class="formText" id="name" value="{$smarty.request.name}" size="30" maxlength="25" > </td> 
    </tr> 
    <tr class=naGrid1>
      <td valign=top><div align="right">Duration</div></td>
      <td valign=top>:</td>
      <td><input name="duration" type="text" class="formText" id="duration" value="{$smarty.request.duration}" size="10" maxlength="25" > <select name="type" class="formText" id="type" style="width:115px">
      <option value="">Select Type</option>
	  <option value="M" {if $smarty.request.type=='M'} selected {/if}>Month</option>
	  <option value="D" {if $smarty.request.type=='D'} selected {/if}>Days</option>
	  <option value="Y" {if $smarty.request.type=='Y'} selected {/if}>Year</option>
	  </select></td>
    </tr>
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Subscription Fee</div></td> 
      <td valign=top>:</td> 
      <td><input name="fees" type="text" class="formText" id="fees" value="{$smarty.request.fees}" size="30" maxlength="255" >
        USD </td> 
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Active</div></td> 
      <td valign=top>:</td> 
      <td><input type=checkbox name="active" value="Y" {if $smarty.request.active=='Y'} checked {/if}></td> 
    </tr> 
	<!--
	 <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Paypal Code </div></td> 
      <td valign=top>:</td> 
      <td><textarea name="paypal_code" id="paypal_code" class="formText" style=" width:300px; height:100px">{$smarty.request.paypal_code}</textarea></td> 
    </tr> 
	-->
    {if $REG_LIST}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Assign Registration Packages</span></td> 
    </tr> 
    {foreach item=reg from=$REG_LIST}
    <tr class={cycle values="naGrid2,naGrid1"}> 
      <td valign=top><div align=right class="element_style">{$reg->package_name}</div></td> 
      <td valign=top>:</td> 
      <td><span class="formCheckbox"> 
        <input type=checkbox name="reg_type[]" value="{$reg->id}" {if $reg->chk!=''} checked{/if}> 
        </span> </td> 
    </tr> 
    {/foreach}
    {/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>
