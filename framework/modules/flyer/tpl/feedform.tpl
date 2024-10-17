<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table width=80% border=0 align="center" cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
	<input type="hidden" name="feed_id" value="{$smarty.request.feed_id}">
  
    <tr align="left">
      <td colspan=3 valign=top>
	  <table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"><!--Categories-->{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=feedlist&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">{$smarty.request.sId} List</a></td>
        </tr>
      </table>
	  	  {messageBox}
	  </td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">&nbsp;</td>
    </tr>
    <tr> 
      <td height="25" colspan=3 class="naGridTitle"><span class="group_style">Feed Details </span></td> 
    </tr> 
  
	<tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Feed Title </div></td> 
      <td width="3%" align="center" valign=top>:</td> 
      <td  width="57%"><input type="text" name="feed_title" value="{$FEED_VALUE.feed_title}" class="formText" size="40" maxlength="50" > </td> 
    </tr>
	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Listing Category </div></td> 
      <td width=3% align="center" valign=top>:</td> 
      <td>
<select name="listing_category" id="listing_category" >
	<option value="0">All Categories</option>
	{html_options values=$CATEGORY_ARRAY.cat_id output=$CATEGORY_ARRAY.cat_name selected=$FEED_VALUE.listing_category}
</select>	  </td> 
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top> Header Part of Feed </td>
	   <td align="center" valign=top>:</td>
	   <td><textarea name="feed_headertext" cols="60" rows="7" class="formText" id="feed_headertext">{$FEED_VALUE.feed_headertext}</textarea></td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>Footer Part of Feed </td>
	   <td align="center" valign=top>:</td>
	   <td><textarea name="feed_footertext" cols="60" rows="7" class="formText" id="feed_footertext">{$FEED_VALUE.feed_footertext}</textarea></td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>Active</td>
	   <td align="center" valign=top>:</td>
	   <td>
<select name="feed_status" id="feed_status" >
	<option value="Y" {if $FEED_VALUE.feed_status eq 'Y'} selected="selected" {/if} >Yes</option>
	<option value="N" {if $FEED_VALUE.feed_status eq 'N'} selected="selected" {/if}>No</option>
</select>
	   </td>
    </tr>
		
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
		   <input type="submit" value="Submit" name="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>