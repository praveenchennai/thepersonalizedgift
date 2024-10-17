<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
   
  
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
      <td colspan=3 class="naGridTitle"><span class="group_style">Field Details </span></td> 
    </tr> 
  
	<tr valign="middle" class=naGrid1> 
      <td width=40% height="50"><div align=right class="element_style">Name</div></td> 
      <td width="3%" height="50">:</td> 
      <td  width="57%" height="50"><input type="text" name="name" value="{$ITEM_VALUE.name}" class="formText" size="33" maxlength="50" > </td> 
    </tr>
		
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
	  <input type="hidden" name="feature_id" value="{$ITEM_VALUE.feature_id}">
	    <input type="hidden" name="item_id" value="{$ITEM_VALUE.value_id}">
	
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
		
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>