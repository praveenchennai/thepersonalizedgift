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
<form name="frmmyalbum"  action="" method="post">
<table width="95%"  cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td width="79%" height="39" class="naH1">Admin Videos </td>
                  <td width="21%" align="right" class="blackboldtext">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" align="left" valign="top" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span>                    <div align="left">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
                      <tr>
                        
                        <td width="3%" align="right">&nbsp;</td>
                        <td align="right" valign="top"><span class="smalltext">
                        </span>                          <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="naBrdr">
                            
                          
							<tr bgcolor="#F6F5F5">
                              <td colspan="5" bgcolor="#F6F5F5" class="blacktext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                             
                              
                              </table></td>
                          </tr>
							
                           
                            <tr bgcolor="#F6F5F5">
                              <td width="1%" height="140" valign="top" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              
                             <td width="24%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="naBrdr">
                                <tr>
                                  <td width="4%" height="20" class="naGridTitle">&nbsp;</td>
                                  <td width="96%" class="naGridTitle">Browse</td>
                                </tr>
                                <tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext">{if (!isset($CRT))}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if} <a href="{makeLink mod=album pg=album_admin}act=myalbum{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">Music&nbsp;({$msCount})</a></td>
                                </tr>
								<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">{if ($CRT=='M1')}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=album pg=album_admin}act=myalbum&crt=M1{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">Movies&nbsp;({$mvCount})</a></td>
                                </tr>
                                
                               	<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">{if ($CRT=='M2')}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=album pg=album_admin}act=myalbum&crt=M2{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">Photos&nbsp;({$phCount})</a></td>
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
                              <td width="1%" align="center" valign="middle" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              <td width="76%" valign="middle" bgcolor="#F6F5F5" class="smalltext">  <table width="100%"  border="0">
                                  
                                 <tr>
                        <td align="left" ><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td align="left">
										
							{capture name=fullscreenURL}{makeLink mod=album pg=video}act=fullscreen&video_id={$PHDET.id}{/makeLink}{/capture} 
							{capture name=fullscreenEscapeURL}{$smarty.capture.fullscreenURL|escape:"url"}{/capture}
							{flashPlayer movie="../includes/flashPlayer/flvplayer.swf?`$smarty.now`" width="400" height="400" majorversion="7" build="0" bgcolor="#FFFFFF" flashvars="showdigits=true&autostart=true&showfsbutton=true&fsreturnpage=&fsbuttonlink=`$smarty.capture.fullscreenEscapeURL`&logo=`$GLOBAL.tpl_url`/images/link54logo4video4.png&file=`$smarty.const.SITE_URL`/modules/album/video/941199339308.flv"}{/flashPlayer}
													</td>
                          </tr>
						     <tr>
                            <td align="left">
										
							{capture name=fullscreenURL}{makeLink mod=album pg=video}act=fullscreen&video_id={$PHDET.id}{/makeLink}{/capture} 
							{capture name=fullscreenEscapeURL}{$smarty.capture.fullscreenURL|escape:"url"}{/capture}
							{flashPlayer movie="../includes/flashPlayer/flvplayer.swf?`$smarty.now`" width="400" height="400" majorversion="7" build="0" bgcolor="#FFFFFF" flashvars="showdigits=true&autostart=true&showfsbutton=true&fsreturnpage=&fsbuttonlink=`$smarty.capture.fullscreenEscapeURL`&logo=`$GLOBAL.tpl_url`/images/link54logo4video4.png&file=`$smarty.const.SITE_URL`/modules/album/video/941199339308.flv"}{/flashPlayer}
													</td>
                          </tr>
                        </table></td>
                      </tr>
                              </table></td>
                            </tr>
                          
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                            </tr>
                            
                            
                          
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">
							  <table width="89%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="5%">&nbsp;</td>
                                  <td width="37%">&nbsp;</td>
                                  <td width="53%">
								  <div id="alb2" style="display:none">
								  <table width="91%"  border="0" cellspacing="2" cellpadding="0">
                                    <tr>
                                      <td><select name="album2" id="album2" style="width:140px" onChange="">
                                        <option value="">-- Select Album --</option>
                                        
							
                                                                    
                                      </select></td>
                                      <td><input type="image" alt="" src="{$GLOBAL.tpl_url}/images/move.jpg" border="0"></td>
                                    </tr>
                                  </table>
								   </div>
								   
								  </td>
								  <div id="alb" style="display:inline">
                                  <td width="5%">&nbsp;</td>
                                  </div>
							    </tr>
                              </table>
							  </td>
                            </tr>
						                     <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">
							  </td>
                          </tr>
                            <tr bgcolor="#DEDEDE">
                              <td height="20" colspan="5" >&nbsp;</td>
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