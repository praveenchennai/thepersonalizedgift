{literal}
<script language="javascript">
	function check()
	{
		if (isNaN(document.frmFee.value.value))
		{
			alert("Please specify a value");
			document.frmFee.value.focus();
			return false;
		}

		if(document.frmFee.type.selectedIndex==0)
		{
			if(document.frmFee.value.value>100)
			{
				alert("Please specify a valid percenatge");
				document.frmFee.value.focus();
				return false;
			}	
		}
		
		return true;
	}

</script>
{/literal}
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="POST"  name="frmFee" style="margin: 0px;" onSubmit="return check()"> 
    <tr> 
      </tr> 
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">Blog Settings</td>
          <td nowrap align="right" class="titleLink">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">
        
      </span></td> 
    </tr> 
 
    <tr class=naGrid1>
      <td width="40%" valign=top><div align=right class="element_style">Show blog with user profile </div></td>
      <td width="1" valign=top>:</td>
      <td class="element_style"><span class="smalltext">
        <select name="type" class="formText" id="type" style="width:180px" >
	      <option value="Y" {if $type=='Y'} selected {/if}>Yes</option>
          <option value="N" {if $type=='N'} selected {/if}>No</option>
        </select>
      </span></td>
    </tr>
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="formbutton">&nbsp; 
          <input type=reset value="Reset" class="formreset" > 
        </div></td> 
    </tr> 
  </form> 
</table>
