<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript" type="text/javascript">

function removeImge(adv_id,field) {
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_remove);
	{/literal}
	if(confirm ("Are you sure want to delete the image"))
	{literal}{{/literal}
		req.open("GET", "{makeLink mod=advertiser pg=ajax_removeImage}{/makeLink}&field="+field+"&adv_id="+adv_id);
		//alert("{makeLink mod=advertiser pg=ajax_removeImage}{/makeLink}&field="+field+"&adv_id="+adv_id);
	{literal}}{/literal}
	else
		return false;	
	{literal}
	req.send(null);
	
}  


function display_remove(_var) {
	_var = _var.split('|');
	if(_var[0]=="0")
	{
	var e= eval("document.getElementById('Image_"+_var[1]+"')");
	//alert(e.innerHTML);
	e.innerHTML="";
	}
	else
		{
		
		}
	
}



</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="advform" action="" style="margin: 0px;" enctype="multipart/form-data"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Advertisement</td> 
      <td align="right"><a href="{makeLink  mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&sId=List&fId=67{/makeLink}">List {$smarty.request.sId}</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Advertisement Details</span></td> 
    </tr> 
	
		<tr class={cycle values="naGrid2,naGrid1"}> 
		  <td valign=top width="40%"><div align=right class="element_style">User Name</div></td> 
		  <td width="1%" valign=top>:</td> 
		  <td width="59%">{$ADVERTISE_DETAILS.username}</td> 
		</tr>
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top><div align="right"> Title </div></td>
		  <td valign=top>:</td>
		  <td><input name="adv_title" type="text" value="{$ADVERTISE_DETAILS.adv_title}"></td>
    </tr>
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top><div align="right"> Description </div></td>
		  <td valign=top>:</td>
		  <td><textarea cols="30" rows="4" class="formText" name="adv_description">{$ADVERTISE_DETAILS.adv_description}</textarea></td>
    </tr>
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top> <div align="right">Url</div></td>
		  <td valign=top>:</td>
		  <td><input name="adv_url" type="text" value="{$ADVERTISE_DETAILS.adv_url}"></td>
    </tr>

		
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top><div align="right"> Image </div></td>
		  <td valign=top>:</td>
		  <td><input name="adv_image" type="file" id="adv_image" size="30"></td>
    	</tr>

		{if $ADVERTISE_DETAILS.adv_image}
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top>&nbsp;</td>
		  <td valign=top></td>
		  <td><div id="Image_adv_image"><img src="{$smarty.const.SITE_URL}/modules/advertiser/images/{$ADVERTISE_DETAILS.id}.{$ADVERTISE_DETAILS.adv_image}" width="120" height="120">&nbsp;<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','adv_image'); return false;">Remove</a></div></td>
   		 </tr>

		{/if}

		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top> <div align="right">Budget </div></td>
		  <td valign=top>:</td>
		  <td><input name="adv_budget" type="text" value="{$ADVERTISE_DETAILS.adv_budget}">	      </td>
    </tr>
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top><div align="right">Daily Budget </div></td>
		  <td valign=top>&nbsp;</td>
		  <td><input name="adv_budget2" type="text" value="{$ADVERTISE_DETAILS.adv_daily_budget}" /></td>
    </tr>
		<tr class={cycle values="naGrid2,naGrid1"}>
		  <td valign=top><div align="right">Active</div></td>
		  <td valign=top>:</td>
		  <td>

				<table border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td><input name="active" type="radio" value="Y" id="Y"{if $ADVERTISE_DETAILS.active ne 'N'} checked{/if}></td>
				  <td><label for="Y">Yes</label></td>
				  <td>&nbsp;</td>
				  <td><input name="active" type="radio" value="N" id="N"{if $ADVERTISE_DETAILS.active eq 'N'} checked{/if}></td>
				  <td><label for="N">No</label></td>
				</tr>
				</table>			</td>
    </tr> 

	
	
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center align="right">&nbsp;</td></tr>
  </form> 
</table>
