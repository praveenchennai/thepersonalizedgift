<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
   
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"><!--Categories-->{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">&nbsp;</td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">FeatureDetails </span></td> 
    </tr> 
  
	<tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Attribute Groupname </div></td> 
      <td width="3%" valign=top>:</td> 
      <td  width="57%"><input type="text" name="group_name" value="{$ATTRIBUTE_VALUE.group_name}" class="formText" size="33" maxlength="50" > </td> 
    </tr>
	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Active </div></td> 
      <td width=3% valign=top>:</td> 
      <td><input type=checkbox name="active" value="Y" {if isset($ATTRIBUTE_VALUE.active) && $ATTRIBUTE_VALUE.active=='Y'} checked{/if} {if !isset($ATTRIBUTE_VALUE.active)} checked{/if}>  </td> 
    </tr>
		
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
	  <input type="hidden" name="attribute_id" value="{$ATTRIBUTE_VALUE.attr_group_id}">
	
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
		
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>