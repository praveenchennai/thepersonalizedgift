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
                  <td width="79%" height="39" class="naH1">Arlicle List </td>
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
                                  <td width="96%" class="naGridTitle">Browse</td>
                                </tr>
								{foreach from=$CAT_ARR item=catg name=val}
                                <tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext">{if ($FILTER==$catg.id)}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}
								  <a href="{makeLink mod=album pg=album_admin}act=myarticle&cat_id={$catg.id}{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">{$catg.cat_name}</a></td>
                                </tr>
								{/foreach}
								<!--<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">{if ($CRT=='M1')}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=album pg=album_admin}act=myalbum&crt=M1{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">Movies&nbsp;({$mvCount})</a></td>
                                </tr>
                                
                               	<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">{if ($CRT=='M2')}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=album pg=album_admin}act=myalbum&crt=M2{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">Photos&nbsp;({$phCount})</a></td>
                                </tr>-->
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
                              <td width="1%" height="20" class="naGridTitle">&nbsp;</td>
                              <td width="29%" colspan="3"  class="naGridTitle">{$PH_HEADER}</td>
                              <td width="70%"class="naGridTitle">&nbsp;</td>
                            </tr>
							<tr bgcolor="#F6F5F5">
                              <td height="50" colspan="5" bgcolor="#F6F5F5" class="blacktext"><table height="100%" width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
								<td width="40%" align="right" class="blackboldtext">Search for  {$MEDIA}:&nbsp; </td>
                                  <td width="31%" align="right"><input name="txtSearch" type="text" id="txtSearch" value="{$STXT}"  size="36"></td>
								   <td width="1%" align="right">&nbsp;</td>
								  <td width="28%" align="left"><input type="submit" name="Submit" value="Search" class="naBtn" ></td>
                                </tr>
                               
                              </table></td>
                          </tr>
                            {if count($MUSIC_LIST)==0}
							<tr bgcolor="#F6F5F5">
                              <td colspan="5" bgcolor="#F6F5F5" class="blacktext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="1" align="center"><span class="smalltext"><span class="smalltext" style="color:#FF0000"><strong>No article Found in {$PH_HEADER}</strong></span></span></td>
                                </tr>
                                <tr>
                                  <td height="18"><div align="center"><span class="blackboldtext"><span class="smalltext" style="color:#FF0000"></span></span></div></td>
                                </tr>
                              </table></td>
                          </tr>
							 {else}
                             <tr bgcolor="#F6F5F5">
                               <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext"><div align="center"><span class="blackboldtext"><span class="smalltext" style="color:#FF0000"></span></span></div></td>
                             </tr>
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext"><table width="89%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="5%">&nbsp;</td>
                                  <td width="37%"><select name="action1" id="action1" style="width:140px" onChange="return doAction1();">
                                    <option value="">-- Choose action --</option>
                                    <option value="delete">Delete Selected</option>
                                                                    </select></td>
                                  <td width="53%">
								  <div id="alb1" style="display:none">
								  <table width="91%"  border="0" cellspacing="2" cellpadding="0">
                                    <tr>
                                      <td><select name="album1" id="album1" style="width:140px" onChange="">
                                        <option value="">-- Select Album --</option>
                                        
								{html_options values=$ALB_LST.id output=$ALB_LST.album_name}

                                                                    
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
                              </table></td>
                            </tr>
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext"><table width="89%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="smalltext">&nbsp;</td>
                                  <td class="smalltext">&nbsp;</td>
                                  <td></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="5%">&nbsp;</td>
                                  <td width="16%" class="smalltext"><a href="javascript: chkbox(1)" class="smalltext"><u>Check All</u></a></td>
                                  <td width="24%" class="smalltext"><a href="javascript: chkbox(2)" class="smalltext"><u>Clear All</u></a></td>
                                  <td width="50%">
                                  </td>
                                  
                                    <td width="5%">&nbsp;</td>
                                 
                                </tr>
                              </table></td>
                            </tr> 
							{/if}
                            {foreach from=$MUSIC_LIST item=photo}
                            <tr bgcolor="#F6F5F5">
                              <td colspan="5" bgcolor="#F6F5F5" class="blacktext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="1" bgcolor="#DEDEDE"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
                                </tr>
                                <tr>
                                  <td height="18">&nbsp;</td>
                                </tr>
                              </table></td>
                          </tr>
                            <tr bgcolor="#F6F5F5">
                              <td height="140" valign="top" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              <td align="center" valign="middle" bgcolor="#F6F5F5" class="blacktext"><input type="checkbox" name="chk[]" value="{$photo->id}"></td>
                              <td align="center" valign="middle" bgcolor="#F6F5F5" class="blacktext"><table width="138" height="131"  border="0" cellpadding="0" cellspacing="0" class="border">
                                <tr>
                                  <td width="136" height="129" align="center" valign="middle"><a target="_blank" href="../{makeLink mod=album pg=$PGFILE}act=details&{$FILE_ID}={$photo->id}{/makeLink}"><img src="{if $photo->audio_type != 'A'}{$smarty.const.SITE_URL}/modules/album/{$MPATH}{$photo->id}{else}{$GLOBAL.tpl_url}/images/play{/if}.jpg"  border="0"></a></td>
                                </tr>
                              </table></td>
                              <td align="center" valign="middle" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              <td valign="middle" bgcolor="#F6F5F5" class="smalltext">  <table width="100%"  border="0">
                                  
                                  <tr>
                                    <td valign="middle"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr class="smalltext">
                                        <td height="15" colspan="3" class="smalltext"><strong>{$photo->title}</strong></td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="33%" height="15">Tags</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$photo->tags}</td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="33%" height="15">Posted</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$photo->postdate|date_format:"%A, %B %e, %Y"}</td>
                                      </tr>
									  <tr class="smalltext">
                                        <td width="33%" height="15">From</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$photo->username}</td>
                                      </tr>
									  <tr class="smalltext">
                                        <td width="33%" height="15">Views</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$photo->views}</td>
                                      </tr>
									  <tr class="smalltext">
										<td width="33%" height="15">Comments</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$photo->cmtcnt}</td>								      
									  </tr>
									  <tr class="smalltext">
										<td width="33%" height="15">Links to Favorites</td>
                                        <td width="4%">:</td>
                                        <td width="63%" height="15">{$photo->favrcnt}</td>								      
									  </tr>
									  <tr class="smalltext">
                                        <td height="25" colspan="3">
                                          <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                            <tr class="smalltext">
											  {if ($photo->cnt>0)}
                                              <td width="75"><img alt="" src="{$GLOBAL.tpl_url}/images/star{$photo->rate}.jpg" border="0"></td>
                                              <td>{$photo->cnt} Ratings</td>
											  {else}
                                              <td>No Ratings</td>
											  {/if}
											  {if $photo->total_price <= 0 AND $CRT != 'M2'}
											<td align="right"><table width="144" border="0">
                                              <tr>
                                                <td align="right">&nbsp;</td>
                                              </tr>
                                            </table></td>
											{/if}
                                            </tr>
                                        </table></td>
								      </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                            {/foreach}
                            {if count($PHOTO_LIST)>0}
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                            </tr>
                            
                            
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="6" bgcolor="#F6F5F5" class="blacktext"><table width="89%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="5%">&nbsp;</td>
                                  <td width="16%" class="smalltext"><a href="javascript: chkbox(1)" class="smalltext"><u>Check All</u></a></td>
                                  <td width="24%" class="smalltext"><a href="javascript: chkbox(2)" class="smalltext"><u>Clear All</u></a></td>
                                  <td width="50%"> </td>
                                  <td width="5%">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="smalltext">&nbsp;</td>
                                  <td class="smalltext">&nbsp;</td>
                                  <td></td>
                                  <td>&nbsp;</td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">
							  <table width="89%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="5%">&nbsp;</td>
                                  <td width="37%"><select name="action2" id="action2" style="width:140px" onChange="return doAction2();">
                                    <option value="">-- Choose action --</option>
                                    <option value="delete">Delete Selected</option>
                                                                    </select></td>
                                  <td width="53%">
								  <div id="alb2" style="display:none">
								  <table width="91%"  border="0" cellspacing="2" cellpadding="0">
                                    <tr>
                                      <td><select name="album2" id="album2" style="width:140px" onChange="">
                                        <option value="">-- Select Album --</option>
                                        
								{html_options values=$ALB_LST.id output=$ALB_LST.album_name}

                                                                    
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
							{/if}                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">
							  </td>
                          </tr>
                            <tr bgcolor="#DEDEDE">
                              <td height="20" colspan="5" >&nbsp;<span class="smalltext">{$MUSIC_NUMPAD}</span></td>
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