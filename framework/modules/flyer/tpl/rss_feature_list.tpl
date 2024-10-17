<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table border=0 width=100% cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
   
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">Fields</td>
          <td nowrap align="right" class="titleLink">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">&nbsp;</td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
    	
	 <tr align="center" > 
      <td colspan="3" valign=top> 
	  
	
	
	 	  <!-- *********** Features ******************* -->
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
	  <tr height="25"> <td colspan="8" class="naGridTitle">&nbsp;Select Fields</td>
	  </tr>
  <tr>
	{assign var="col2" value="1"}
	{foreach from=$GRP_FEATURES item=item_features name=loop3}
    <td  width="25%">&nbsp;
	 {html_checkboxes name="featureId" values=`$item_features.feature_id` selected=$SELECTED_FEATURES.form_id onClick='newSelect(this.value);'} 
		
		&nbsp;{$item_features.label}	</td>
    <td width="1%" >&nbsp;</td>
		{if $col2%4 eq 0}			</tr><tr><td >&nbsp;</td></tr><tr>
		{/if}
		{assign var="col2" value="`$col2+1`"}
	{/foreach}
    </tr>
   </table>
	 <!-- ******************** Features  ******************** -->	   </td> 
    </tr>
			
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
	  <input type="hidden" name="form_id" value="{$FORM_ID}">
	
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>