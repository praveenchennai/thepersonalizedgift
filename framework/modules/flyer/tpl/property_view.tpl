<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function statusChange() 
{	{/literal}
	var	id	=	document.frm_flyer.status_id.value;
	document.frm_flyer.action="{makeLink mod=flyer pg=flyer}act=list{/makeLink}&status_id="+id; 
	document.frm_flyer.submit();
	{literal}
}
function pageSearch()
	{
	cat_search	=	document.getElementById("flyer_search").value;
	document.frm_flyer.keysearch.value='Y';
	document.frm_flyer.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
	document.frm_flyer.submit();
	}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_flyer.elements.length;i++)
			{
			var e = frm_flyer.elements[i];
					
					if(e.name=='category_id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one {/literal}{$smarty.request.sId}{literal}");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal}?'))
	{
		document.frm_flyer.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_flyer.submit();
	}
}
</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_flyer" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" border =0 align="center"> 
        <tr> 
          <td nowrap class="naH1" width="86%" >{$smarty.request.sId}&nbsp;<font size="2px">{$FLYER_LIST.0.albumname}</font></td> 
          <td nowrap align="left" class="titleLink" width="16%">&nbsp;
		  <a href="{makeLink mod=flyer  pg=flyer}act=property_view&propid={$smarty.request.propid}&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&flyer_id={$flyer->flyer_id}&details={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}">View Listing</a>
		  
	
	</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="8" align="left" class="naGrid1">{$DISPLAY_PATH}</td>
        </tr>
		
   
	    <tr>
	      <td width="2%" class="naGridTitle">&nbsp;</td>
          <td width="15%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=name display="Buyer"}act=view_property&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&propid={$smarty.request.propid}{/makeLink}</td> 
       
		  <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=sdate display="Start Date"}act=view_property&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&propid={$smarty.request.propid}{/makeLink}</td>
		  <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=edate display="End Date"}act=view_property&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&propid={$smarty.request.propid}{/makeLink}</td>

		  <td width="15%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=bookdate display="Booked Date"}act=view_property&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&propid={$smarty.request.propid}{/makeLink}</td>
          <td width="12%" align="right" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=bookamt display="Booked Amount"}act=view_property&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&propid={$smarty.request.propid}{/makeLink}</td>
		 
		  <td width="13%" align="right" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=totalamt display="Total Amount"}act=view_property&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&propid={$smarty.request.propid}{/makeLink}</td>
	 <td width="2%" class="naGridTitle">&nbsp;</td>
	  
	    
		</tr>
        {if count($FLYER_LIST) > 0}
        {foreach from=$FLYER_LIST item=flyer name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td >&nbsp;</td>
          <td align="left" valign="middle">{$flyer.name} </td> 
          
		  <td align="left" valign="middle">{$flyer.sdate} </td>
			  <td align="left" valign="middle">{$flyer.edate} </td>
          <td align="left" valign="middle">{$flyer.bookdate}</td>
		  <td align="right" valign="middle">{$flyer.bookamt}</td>
          <td align="right" valign="middle">{$flyer.totalamt}</td>
		   <td  >&nbsp;</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="8" class="msg" align="center" height="30">{$FLYER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="8" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
   <tr>
    <td>&nbsp;</td>
  </tr> 
  <tr>
    <td>&nbsp;</td>
  </tr>
</table><input type="hidden" name="keysearch" value="N">
</form>