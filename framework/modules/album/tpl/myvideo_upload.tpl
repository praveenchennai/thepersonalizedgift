{literal}
<script language="javascript">
	function check()
	{
		var obj=document.frmmyalbum.elements;
		for(var i=0;i<obj.length;i++)
		{
			if(obj[i].type=="checkbox")
			{
				if (obj[i].checked==true)
				{
					return true;
				}
				
			}
		}
		return false;
	}
	function chkbox(flg)
	{
		var obj=document.frmmyalbum.elements;
		for(var i=0;i<obj.length;i++)
		{
			if(obj[i].type=="checkbox")
			{
				if(flg==1)
				{
					obj[i].checked=true;
				}
				else
				{
					obj[i].checked=false
				}	
			}
		}
	}
	function doAction1()
	{
		if (!check())
		{
			alert("Please select at least one file");
			document.frmmyalbum.action1.selectedIndex=0
			return false;
		}
		else
		{
			if(document.frmmyalbum.action1.selectedIndex==1)
			{
				document.getElementById('alb1').style.display="none";
				if(confirm("Do you want to delete all the selected files?"))
				{
					document.frmmyalbum.submit();
				}
				else
				{
					document.frmmyalbum.action1.selectedIndex=0;
				}
			}
			else if(document.frmmyalbum.action1.selectedIndex==2)
			{
					document.frmmyalbum.action2.selectedIndex=0;
					document.getElementById('alb2').style.display="none";
					document.getElementById('alb1').style.display="inline"; 
			}
			else
			{
				document.getElementById('alb1').style.display="none";
			}
		}		
	}
	function doAction2()
	{
		if (!check())
		{
			alert("Please select at least one file");
			document.frmmyalbum.action2.selectedIndex=0
			return false;
		}
		else
		{

			if(document.frmmyalbum.action2.selectedIndex==1)
			{
				document.getElementById('alb2').style.display="none";
				if(confirm("Do you want to delete all the selected files?"))
				{
					document.frmmyalbum.submit();
				}
				else
				{
					document.frmmyalbum.action2.selectedIndex=0;
				}
			}
			else if(document.frmmyalbum.action2.selectedIndex==2)
			{
					document.frmmyalbum.action1.selectedIndex=0;
					document.getElementById('alb1').style.display="none";
					document.getElementById('alb2').style.display="inline"; 
			}
			else
			{
				document.getElementById('alb2').style.display="none";
			}
		}		
	}
</script>
{/literal}
<form name="frmmyalbum"  action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="CRT" value="{$smarty.request.CRT}" />
<table width="95%"  cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td width="79%" height="39" class="naH1">Video List </td>
                  <td width="21%" align="right" class="blackboldtext">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" align="left" valign="top" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span>                    <div align="left">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
                      <tr>
                        <td width="24%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="naBrdr">
                                <tr>
                                  <td width="4%" height="20" class="naGridTitle">&nbsp;</td>
                                  <td width="96%" class="naGridTitle">&nbsp;</td>
                                </tr>
                                <tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext">{if (!isset($CRT))}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if} <a href="{makeLink mod=album pg=video_admin}act=myvideo{/makeLink}" class="blacktext">Health&nbsp;</a></td>
                                </tr>
								<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">{if ($CRT=='M1')}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=album pg=video_admin}act=myvideo&CRT=M1{/makeLink}" class="blacktext">Wealth&nbsp;</a></td>
                                </tr>
                                
                               	<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">&nbsp;</td>
                                </tr>
								
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </table></td>
                        <td width="3%" align="right">&nbsp;</td>
                        <td align="right" valign="top"><span class="smalltext">
                        </span>                          <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="naBrdr">
                            <tr>
                              <td width="30%" height="20" class="naGridTitle">{if (!isset($CRT))}Health Video{else}Wealth Video{/if} </td>
                              <td colspan=""  class="naGridTitle">&nbsp;</td>
                              <td width="56%"class="naGridTitle"><a href="{makeLink mod=album pg=video_admin}act=myvideo&CRT={$smarty.request.crt}{/makeLink}" title="Edit Video">Edit Video</a></td>
                            </tr>
							   <tr align="left">
                            <td  colspan="3" align="center"><span class="smalltext"><strong style="color:#FF0000;">{messageBox}</strong> </span></td>
                          </tr>
							<tr>
                            <td colspan="3"><div align="justify"><span class="smalltext">Uploading a Video is simple, you'll be able to choose your Video file and set the privacy settings. Generally, uploading will take 1-5 minutes for every 1MB with a high-speed Internet connection. You'll be taken to the Video page when it's done.</span><br>
                            </div></td>
                          </tr>
							 <tr>
                            <td align="center" style="padding-bottom:20px; padding-top:20px  " colspan="3">
							<input name="videoFile" type="file" size="40">
                            </td>
                          </tr>
                                  
                            <tr bgcolor="#DEDEDE">
                              <td height="20" colspan="5"  align="center"  style="padding-bottom:10px;"><input type="submit"   value="Update"    class="button_class"></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" class="blacktext">&nbsp;</td>
                </tr>
              </table>
			  </form>