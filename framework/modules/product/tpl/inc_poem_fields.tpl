<table cellpadding="0" cellspacing="0" border="0">

{section name=op loop=$OP_COUNT}

<tr valign="middle" class=naGrid2>
      <td width="300" height="25"  align="right"><span class="fieldname">Opening Line {$smarty.section.op.iteration} :</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="opt{$smarty.section.op.index}" id="opt{$smarty.section.op.index}" value="{$smarty.request.first_name}" size="40" maxlength="255"  />
      </span></td>
    </tr>
{/section}	
{section name=cl loop=$CL_COUNT}

<tr valign="middle" class=naGrid2>
      <td width="300" height="25"  align="right"><span class="fieldname">Closing Line {$smarty.section.cl.iteration} :</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="col{$smarty.section.cl.index}" id="col{$smarty.section.cl.index}" value="{$smarty.request.first_name}" size="40" maxlength="255"  />
      </span></td>
    </tr>
{/section}	

	</table>
	<input type="hidden" name="txt1" value="hii" />