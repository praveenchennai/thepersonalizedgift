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
      <td valign=top width=40%><div align=right class="element_style">Form Title </div></td> 
      <td width="3%" valign=top>:</td> 
      <td  width="57%"><input type="text" name="form_title" value="{$FEATURE_VALUE.form_title}" class="formText" size="33" maxlength="50" > </td> 
    </tr> 
	
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style"> Category </div></td>
      <td valign=top>:</td>
      <td>
	  <select name="category_name" >
       {html_options values=$CATEGORY_ARRAY.cat_id output=$CATEGORY_ARRAY.cat_name selected=$FEATURE_VALUE.category_name}
 	 </select>
	  </td>
    </tr>
	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Active </div></td> 
      <td width=3% valign=top>:</td> 
      <td><input type=checkbox name="active" value="Y" {if isset($FEATURE_VALUE.active) && $FEATURE_VALUE.active=='Y'} checked{/if} {if !isset($FEATURE_VALUE.active)} checked{/if}>  </td> 
    </tr>
	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Show Accomodation </div></td> 
      <td width=3% valign=top>:</td> 
	    <td width=3% valign=top><input name="show_accomodation" type="checkbox"  value="Y" {if  isset($FEATURE_VALUE.show_accomodation) &&  $FEATURE_VALUE.show_accomodation == 'Y'}checked{/if} {if !isset($FEATURE_VALUE.show_accomodation)} checked{/if}/></td> 
    </tr>
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Show Quantity </div></td> 
      <td width=3% valign=top>:</td> 
	    <td width=3% valign=top><input name="show_Qty" type="checkbox" id="show_Qty" value="Y" {if   isset($FEATURE_VALUE.show_Qty) &&  $FEATURE_VALUE.show_Qty == 'Y'}checked{/if} {if !isset($FEATURE_VALUE.show_Qty)} checked{/if}/></td> 
    </tr>
		
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
	  <input type="hidden" name="forms_id" value="{$FEATURE_VALUE.form_id}">
	
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
		
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>