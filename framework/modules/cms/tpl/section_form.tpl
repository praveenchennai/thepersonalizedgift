<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td nowrap class="naH1" colspan="2">{$smarty.request.sId}</td> 
	  <td align="right"><a href="{makeLink mod=$smarty.request.mod pg="$PG"}act=list{/makeLink}&sId={$SUBNAME}&mId={$MID} ">List {$smarty.request.sId}</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Section Details</span></td> 
    </tr> 
    <tr class="{if $smarty.request.id}naGrid2{else}naGrid1{/if}"> 
      <td width=40% align="right" valign=top>Section Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="name" value="{$SECTION.name}" class="formText" size="30" maxlength="255" ></td> 
    </tr>
	{if $smarty.request.id}
	{/if}
    <tr class="naGrid1">
      <td align="right">Show Menu</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="show_menu" type="radio" value="Y" id="Y"{if $SECTION.show_menu ne 'N'} checked{/if}></td>
            <td><label for="Y">Yes</label></td>
            <td>&nbsp;</td>
            <td><input name="show_menu" type="radio" value="N" id="N"{if $SECTION.show_menu eq 'N'} checked{/if}></td>
            <td><label for="N">No</label></td>
          </tr>
      </table></td>
    </tr>
   <tr class="naGrid2">
      <td align="right">Show in sub store</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="show_sub_store" type="radio" value="Y" id="Y"{if $SECTION.show_sub_store ne 'N'} checked{/if}></td>
            <td><label for="Y">Yes</label></td>
            <td>&nbsp;</td>
            <td><input name="show_sub_store" type="radio" value="N" id="N"{if $SECTION.show_sub_store eq 'N'} checked{/if}></td>
            <td><label for="N">No</label></td>
          </tr>
      </table></td>
    </tr> 
    <tr class="naGrid1">
      <td align="right">Active</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="active" type="radio" value="Y" id="Y"{if $SECTION.active ne 'N'} checked{/if}></td>
            <td><label for="Y">Yes</label></td>
            <td>&nbsp;</td>
            <td><input name="active" type="radio" value="N" id="N"{if $SECTION.active eq 'N'} checked{/if}></td>
            <td><label for="N">No</label></td>
          </tr>
      </table>
      <input type="hidden" name="store_id" value="{$smarty.session.store_id}">	 </td>
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
