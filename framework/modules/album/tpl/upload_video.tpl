<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields=new Array('title','cat_id');
		var msgs=new Array('Video title','Choose Category');
		{literal}	
		function check(){
			if (chk(document.admFrm)){
				return true;
			}else{
				document.getElementById('preloadDyn').style.display='none';
				return false;
			}
		}
		function loadFunc(){
		{/literal}
			{if $smarty.request.act != "editvideo"}
		{literal}
			document.getElementById('preloadDyn').style.display='inline';
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
          <td width="53%" align="right" nowrap class="titleLink"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="18%">&nbsp;</td>
              <td width="32%"><a href="{makeLink mod=album pg=album_admin}act=propdView&propid={$smarty.request.propid}{/makeLink}">View this property </a></td>
              <td width="2%">&nbsp;</td>
              <td width="26%"><a href="{makeLink mod=album pg=album_admin}act=edit_property&propid={$smarty.request.propid}&user_id={$smarty.request.user_id}{/makeLink}">Edit Property </a></td>
              <td width="1%">&nbsp;</td>
              <td width="21%"><a href="{makeLink mod=album pg=album_admin}act=propdView{/makeLink}">Property List</a></td>
            </tr>
          </table>            <!-- <a href="{makeLink mod=member pg=user}act=sub_form{/makeLink}">Add New</a> --></td> 
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
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
              <td colspan="3" class="normaltext"> </td>
            </tr>
            <tr align="center">
              <td colspan="3" class="normaltext">
			  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     			 <tr>
        		<td class="naGridTitle" style="padding-left:5px;height:24">{$PROP_DETAILS[0].prop_title}</td>
      			</tr>
   			 </table>
			  </td>
            </tr>
            <tr align="center" class="naGrid1">
              <td colspan="3" class="normaltext">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3" align="left" class="normaltext" style="padding-left:5px">Uploading a movie is simple, you'll be able to choose your movie file and set the privacy settings. Generally, uploading will take 1-5 minutes for every 1MB with a high-speed Internet connection. You'll be taken to the movie page when it's done.
              </td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr align="center">
              <td colspan="3"><span class="normaltext">{messageBox}</span></td>
            </tr>
            <tr class="naGrid1">
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><div id="preloadDyn"  style="position:absolute;left:350px;top:200px;width:400px;height:110px;border:1px solid #c2c7d3;display:none;background-color:#d5d8df;z-index:3000;">
<table border="0" cellspacing="0" cellpadding="0" align="center" height="100%" width="100%" background="{$GLOBAL.tpl_url}/images/divLoadingBg.jpg">
  <tr>
    <td valign="middle" width="30%">&nbsp;</td>
    <td valign="middle" width="10%"><img src="{$GLOBAL.tpl_url}/images/loading.gif"></td>
    <td valign="middle" width="40%"><b>&nbsp;&nbsp;Please wait file is uploading...</b></td>
	<td valign="middle" width="20%">&nbsp;</td>
  </tr>
</table>
</div></td>
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
              <td valign="top" align="right" class="normaltextbold">Description:</td>
              <td>&nbsp;</td>
              <td><textarea name="description" cols="32" rows="3" id="textarea">{$smarty.request.description}</textarea></td>
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
    <td align="right" class="normaltextbold">Default Video: </td>
    <td>&nbsp;</td>
    <td><input type="checkbox" name="default_vdo" value="checkbox">
    </td>
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
            <input type="submit" value="{if $smarty.request.act== "editvideo"}Edit info{else}Upload{/if}" onclick="loadFunc()" class="naBtn">
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
    <td colspan="3">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr class="naGridTitle">
              <td width="21%" class="headingtext2" style="padding-left:5px;height:20">Uploaded Videos</td>
              <td width="79%" class="headingtext2" style="padding-left:5px;height:20">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr class="naGrid1">
          <td align="center">&nbsp;</td>
        </tr>
		<tr ><td align="right" class="bodytext">{$NUM_PAD}</td></tr>
		<tr class="naGrid1"><td align="right" class="bodytext">&nbsp;</td></tr>
        <tr class="naGrid1">
          <td align="center">
            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
				
              {foreach from=$VIDEO_DETAILS item=video}
                <tr>
                  <td width="15%"><img src="{$smarty.const.SITE_URL}/modules/album/video/thumb/{$video.id}.jpg" border="0" class="border"></td>
                  <td width="1%">&nbsp;</td>
                  <td width="84%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="79%"><a href="" class="toplink"><u>{$video.title}</u></a></td>
                        <td width="21%"><input name="button" type="button" onClick="window.location.href='{makeLink mod=album pg=album_admin}act=editvideo&propid={$smarty.request.propid}&vid={$video.id}{/makeLink}'"  value="Edit video info" class="naBtn"></td>
                      </tr>
                      <tr>
                        <td class="tdHeight1"></td>
                        <td class="tdHeight1"></td>
                      </tr>
                      <tr>
                        <td class="normaltext" style="font-size:10px">{$video.description}</td>
                        <td align="center" class="normaltext" style="font-size:10px"></td>
                      </tr>
                      <tr>
                        <td class="normaltext" valign="middle"></td>
                        <td class="normaltext" valign="middle"></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="3" class="tdHeight1"></td>
                </tr>
                <tr>
                  <td align="center"><input name="button" type="button" onClick="if (confirm('Are you sure you want to delete this video?'))window.location.href='{makeLink mod=album pg=album_admin}act=delvdo&propid={$smarty.request.propid}&vid={$video.id}{/makeLink}'"  value="Remove Video" class="naBtn"></td>
                  <td>&nbsp;</td>
                  <td align="right" valign="top">{if $PROP_DETAILS[0].default_vdo ==  $video.id}<img src="{$smarty.const.SITE_URL}/templates/blue/images/default_video.jpg" width="50" height="50"> {else}<a href="{makeLink mod=album pg=album_admin}act=defaultvdo&vid={$video.id}&propid={$smarty.request.propid}&user_id={$smarty.request.user_id}{/makeLink}" class="footerlink"><u>Set As Default</u></a>{/if}</td>
                </tr>
                <tr>
                  <td colspan="3"><hr size="1" color="#B5B5B6"></td>
                </tr>
              {/foreach}
          </table></td>
        </tr>
		<tr><td align="right" class="bodytext">{$NUM_PAD}</td></tr>
    </table></td>
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