<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields=new Array('title','cat_id','state');
		var msgs=new Array('Video title','Choose Category','Select State');
		{literal}	
		function check(){
			if (chk(document.admFrm)){
				return true;
			}else{
				//document.getElementById('preloadDyn').style.display='none';
				return false;
			}
		}
		function loadFunc(){
		{/literal}
			{if $smarty.request.act != "editvideo"}
		{literal}
			//document.getElementById('preloadDyn').style.display='inline';
		{/literal}
			{/if}
		{literal}
		}
		{/literal}	
	</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class="naBrdr"> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td width="47%" nowrap class="naH1">Upload Video</td> 
          <td width="53%" align="right" nowrap class="titleLink"><!-- <a href="{makeLink mod=member pg=user}act=sub_form{/makeLink}">Add New</a> --></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return check()"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="95%" height="244" valign="top" class="blacktext">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
    <td align="center" class="tdHeight"></td>
  </tr>
        <tr>
          <td class="naGrid1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
              <td colspan="3" class="naGridTitle">&nbsp;</td>
            </tr>
           
            <tr>
              <td colspan="3" align="left" class="normaltext" style="padding-left:5px"> Generally, uploading will take 1-5 minutes for every 1MB with a high-speed Internet connection. You'll be taken to the movie page when it's done.              </td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr align="center">
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr class="naGrid1">
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
            </tr>
            <tr class="naGrid1">
              <td width="21%" align="right" class="normaltextbold">Title:</td>
              <td width="4%">&nbsp;</td>
              <td width="75%"><span class="smalltext">
                <input name="title" type="text" id="title2" class="input" value="{$smarty.request.title}" size="40">
              </span></td>
            </tr>
			 <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			<tr class="naGrid1">
              <td width="21%" align="right" class="normaltextbold">State:</td>
              <td width="4%">&nbsp;</td>
              <td width="75%"><span class="smalltext">
               <select name="state" id="state"  style="width:253px" tabindex="7" >
			   <option value="">Select State</option>
			   {foreach from=$STATE_LIST item=state}
				 <option value="{$state.id}">{$state.name}</option>
				{/foreach}
   			 </select>
              </span></td>
            </tr>
            <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			
			<tr class="naGrid1">
              <td width="21%" align="right" class="normaltextbold">Category:</td>
              <td width="4%">&nbsp;</td>
              <td width="75%"><span class="smalltext">
               <select name="cat_id" id="cat_id"  style="width:253px" tabindex="7" >
			   <option value="">Select Category</option>
	 {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name selected=$smarty.request.cat_id}
	  
      
    </select>
              </span></td>
            </tr>
            <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="naGrid1">
              <td valign="middle" align="right" class="normaltextbold">Description:</td>
              <td>&nbsp;</td>
              <td><textarea name="description" cols="39" rows="3" id="textarea">{$smarty.request.description}</textarea></td>
            </tr>
			 <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			 <tr class="naGrid1">
              <td valign="middle" align="right" class="normaltextbold">Location:</td>
              <td>&nbsp;</td>
              <td><textarea name="location" cols="39" rows="3" id="location">{$smarty.request.location}</textarea></td>
            </tr>
			 <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			 <tr class="naGrid1">
			   <td align="right" class="normaltextbold"><strong>Contact Details</strong></td>
			   <td>&nbsp;</td>
			   <td>&nbsp;</td>
			   </tr>
			 <tr class="naGrid1">
			   <td align="right" class="normaltextbold">&nbsp;</td>
			   <td>&nbsp;</td>
			   <td>&nbsp;</td>
			   </tr>
			 <tr class="naGrid1">
              <td width="21%" align="right" class="normaltextbold">Name:</td>
              <td width="4%">&nbsp;</td>
              <td width="75%"><span class="smalltext">
                <input name="name" type="text" id="name" class="input" value="{$smarty.request.name}" size="40">
              </span></td>
            </tr>
			 <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			 <tr class="naGrid1">
              <td width="21%" align="right" class="normaltextbold">Phone No:</td>
              <td width="4%">&nbsp;</td>
              <td width="75%"><span class="smalltext">
                <input name="phone" type="text" id="phone" class="input" value="{$smarty.request.phone}" size="40">
              </span></td>
            </tr>
			 <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			 <tr class="naGrid1">
              <td width="21%" align="right" class="normaltextbold">Email:</td>
              <td width="4%">&nbsp;</td>
              <td width="75%"><span class="smalltext">
                <input name="email" type="text" id="email" class="input" value="{$smarty.request.email}" size="40">
              </span></td>
            </tr>
			
			
   {if $smarty.request.act != "editvideo"}
	<tr class="naGrid1">
	  <td height="22" colspan="3"><div align="left"><span class="smalltext"><strong class="greyboldtext style1"> </strong></span></div></td>
	</tr>
  	<tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="normaltext"><strong>Do not upload copyrighted material for which you don't own the rights or have permission from the owner.</strong></td>
  </tr>

  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    {/if}
  {if $smarty.request.act != "editvideo"}
  <tr class="naGrid1">
    <td width="21%" align="right" class="normaltextbold">File Upload:</td>
    <td width="4%">&nbsp;</td>
    <td width="75%"><span class="smalltext">
      <input name="videoFile" class="input" type="file" size="40">
    </span> </td>
  </tr>
  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><span class="normaltext">(We support MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.)</span></td>
  </tr>
  {/if}
  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="naGrid1">
              <td valign="top" align="right" class="normaltextbold">&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="checkbox" name="home_appearance" id="home_apperence" value="Y"> Home page appearance</td>
            </tr>
  
  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><table width="50%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="27%"><span class="blackboldtext">
            <input type="submit" value="Submit" onclick="loadFunc()" class="naBtn">
          </span></td>
          <td width="4%">&nbsp;</td>
          <td width="69%"><span class="blackboldtext">
            <input name="button" type="button" onClick="window.location.href='{makeLink mod=album pg=album_admin}act=propdView&propid={$smarty.request.propid}{/makeLink}'" value="Cancel" class="naBtn">
          </span></td>
        </tr>
    </table></td>
  </tr>
  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="naGrid1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</form>
</td>
</tr>
</table>