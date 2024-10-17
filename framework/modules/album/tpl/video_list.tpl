<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields=new Array('title','cat_id');
		var msgs=new Array('Video title','Choose Category');
		{literal}	
		
		function delVideo(id)
		{
			if (confirm("Do you want to delete this video?"))
			{
			str = "{/literal}{makeLink mod=album pg=album_admin}act=del_video{/makeLink}{literal}&video_id="+id;
			window.location.href = str;
			}
		}
		
		function check(){
			if (chk(document.admFrm)){
				return true;
			}else{
				//document.getElementById('preloadDyn').style.display='none';
				return false;
			}
		}
		function getListByCountry()
		{
			document.admFrm.submit();
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
	<table align="center" width="65%" border="0" cellspacing="0" cellpadding="0" class="naBrdr"> 
  <tr> 
    <td class="naH1">Video Listing here </td>
  </tr> 
  <tr> 
    <td>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return check()"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="95%" height="244" valign="top" class="blacktext" bgcolor="#F6F5F5">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
    <td align="center" class="tdHeight"></td>
  </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
              <td colspan="3" class="normaltext"> </td>
            </tr>
            <tr align="center">
              <td colspan="3" class="normaltext">&nbsp;</td>
            </tr>
			<tr align="center" bgcolor="#FFFFFF" >
			<td colspan="3" height="25">
			<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>
			<td width="26%"><div align="right"><span>Search by title or description</span></div></td>
			<td width="1%">&nbsp;</td>
			<td width="22%"><input type="text" id="state_search" name="state_search" value="{$smarty.request.state_search}"></td>
			<td width="25%"><input name="btn_search" type="submit" class="naBtn" value="Search" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Category</td>
              <td width="26%" colspan="3" class="normaltext"><select name="state_id" id="state_id"  style="width:150px" onChange="getListByCountry();"  >
			   <option value="">Select All</option>
			   {foreach from=$STATE_LIST item=state}
				 <option value="{$state.id}" {if ($smarty.request.state_id==$state.id)} selected {/if}>{$state.name}</option>
				{/foreach}
   			 </select></td>
			</tr></table></td>
            </tr>
            <tr class="naGridTitle">
              <td height="25" width="0%">&nbsp;</td>
              <td width="26%">&nbsp;</td>
              <td width="74%">&nbsp;</td>
            </tr>
            
    {if (count($VIDEO_LIST)>0)}       
   
	{foreach from=$VIDEO_LIST item=video}
                            <tr bgcolor="#F6F5F5">
                              <td colspan="3" bgcolor="#F6F5F5" class="blacktext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td height="10"><div></div></td>
                                </tr>
                                <tr>
                                  <td height="1" bgcolor="#DEDEDE"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
                                </tr>
                                <tr>
                                  <td height="10"><div></div></td>
                                </tr>
                              </table></td>
                              </tr>
                            <tr bgcolor="#F6F5F5">
                              <td height="70" valign="top" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              <td align="center" valign="middle" bgcolor="#F6F5F5" class="blacktext"><table width="138" height="50"  border="0" cellpadding="0" cellspacing="0" class="border">
                                <tr>
                                  <td width="136" height="70" align="center" valign="middle"><a href="{makeLink mod=album pg=album_admin}act=video_details&video_id={$video->id}{/makeLink}"><img alt="" style="border:5px solid #FFFFFF;" src="{$smarty.const.SITE_URL}/modules/album/video/thumb/{$video->id}.jpg" border="0"></a></td>
                                </tr>
                              </table></td>
                              <td valign="middle" bgcolor="#F6F5F5" class="smalltext"><table width="100%"  border="0">
                                  
                                  <tr>
                                    <td width="60%" valign="middle"><table width="87%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr class="smalltext">
                                        <td height="15" colspan="3" class="smalltext"><strong>{$video->title}</strong></td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="33%" height="15">Posted</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$video->postdate|date_format:"%A, %B %e, %Y"}</td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="33%" height="15">Owner</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$video->username}</td>
                                      </tr>
									   <tr class="smalltext">
                                        <td width="33%" height="15">State</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$video->state_name}</td>
                                      </tr>
									  
                                    </table></td>
                                    <td width="40%" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="100px" align="center">{if $video->home_appearance=='Y'}<img alt="" src="{$GLOBAL.tpl_url}/images/home_video.gif" border="0" title="" />{else}<a href="{makeLink mod=album pg=album_admin}act=make_home&video_id={$video->id}&state_id={$video->state}&state_ret_id={$smarty.request.state_id}&state_search={$smarty.request.state_search}{/makeLink}">Make home<a/>{/if} </td>
                                            <td><a href="{makeLink mod=album pg=album_admin}act=edit_video&video_id={$video->id}{/makeLink}"><img alt="Edit" title="Edit" src="{$GLOBAL.tpl_url}/images/edit.gif" border="0" /></a></td>
                                            <td>&nbsp;</td>
                                            <td><a href="javascript:delVideo('{$video->id}');"><img alt="" src="{$GLOBAL.tpl_url}/images/delete.gif" border="0" title="Delete" /></a></td>
                                          </tr>
                                        </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                            
                            {/foreach}
				{else}			
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td  ><span style="color:#FF0000"><strong>No video found</strong></span></td>
				</tr>
				{/if}
							
  	
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
  <tr>
  <td bgcolor="#F6F5F5"><div align="right">{$VIDEO_NUMPAD}</div></td>
</table>
</form>
<br><br>
</td>
</tr>
<br>
</table>