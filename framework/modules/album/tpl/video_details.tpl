<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields=new Array('title','cat_id');
		var msgs=new Array('Video title','Choose Category');
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
	<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class="naBrdr"> 
  <tr> 
    <td class="naH1">Watch Video here</td>
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
              <td colspan="3" class="normaltext">&nbsp;</td>
            </tr>
            
            
            <tr class="naGridTitle">
              <td width="0%">&nbsp;</td>
              <td width="26%">Video Details</td>
              <td width="74%">&nbsp;</td>
            </tr>
            
            <tr class="naGrid1">
              <td colspan="3"><table width="50%"  border="0" align="left" cellpadding="1" cellspacing="0">
                                  <tr class="smalltext">
                                    <td height="15">&nbsp;</td>
                                    <td height="15" colspan="3">&nbsp;</td>
                                  </tr>
                                  <tr class="smalltext">
                                    <td width="2%" height="15">&nbsp;</td>
                                    <td height="15" colspan="3"><strong>{$PHDET.title}</strong></td>
                                  </tr>
                                  <tr class="smalltext">
                                    <td>&nbsp;</td>
                                    <td width="26%" height="15">Description</td>
                                    <td width="1%">:</td>
                                    <td width="71%" height="15">{$PHDET.description}</td>
                                  </tr>
                                  
                                  <tr class="smalltext">
                                    <td>&nbsp;</td>
                                    <td height="15">Posted</td>
                                    <td>:</td>
                                    <td height="15">{$PHDET.postdate|date_format:"%A, %B %e, %Y"}</td>
                                  </tr>
                                  <tr class="smalltext">
                                    <td>&nbsp;</td>
                                    <td height="15">Owner</td>
                                    <td>:</td>
                                    <td height="15">{$PHDET.username}</td>
                                  </tr>
                                  <tr class="smalltext">
                                    <td>&nbsp;</td>
                                    <td height="15">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td height="15">&nbsp;</td>
                                  </tr>
								 
                                </table></td>
              </tr>
			
            <tr class="naGrid1">
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
   
	<tr class="naGrid1">
	  <td height="22" colspan="3"><div align="center">{capture name=fullscreenURL}{makeLink mod=album pg=video}act=fullscreen&video_id={$PHDET.id}{/makeLink}{/capture} {capture name=fullscreenEscapeURL}{$smarty.capture.fullscreenURL|escape:"url"}{/capture} {flashPlayer movie="`$smarty.const.SITE_URL`/includes/flashPlayer/flvplayer.swf?`$smarty.now`" width="400" height="300" majorversion="7" build="0" bgcolor="#FFFFFF" flashvars="showdigits=true&autostart=true&showfsbutton=true&fsreturnpage=&fsbuttonlink=`$smarty.capture.fullscreenEscapeURL`&file=`$smarty.const.SITE_URL`/modules/album/video/`$PHDET.id`.flv"}{/flashPlayer}</div></td>
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