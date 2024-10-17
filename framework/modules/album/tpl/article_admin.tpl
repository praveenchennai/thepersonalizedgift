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
<table width="95%"  cellspacing="0" cellpadding="0" >
                <tr valign="middle">
                  <td width="79%" height="39" class="naH1">Articles List </td>
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
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext">{if ($CRT==$catg.id)}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}
								  <a href="{makeLink mod=album pg=album_admin}act=articles&cat_id={$catg.id}&link=Y{if (isset($ALB_ID))}&alb_id={$ALB_ID}{/if}{/makeLink}" class="blacktext">{$catg.cat_name}</a></td>
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
                            
							<tr bgcolor="#F6F5F5">
                              
							<tr><td colspan="5" valign="top"><table width="100%" cellpadding="0" cellspacing="0">
							
							<tr>
                                    <td width="2%" class="naGridTitle">&nbsp;</td>
									<td width="25%" class="naGridTitle" align="left" height="22" >&nbsp;&nbsp;<a href="{makeLink mod=album pg=album_admin}act=articles&orderBy=album_name&link=Y{if $CRT}&cat_id={$CRT}{/if}{/makeLink}" class="whiteboltext">Title</a></td>
                                    <td width="15%" class="naGridTitle" align="left" ><a href="#" class="whiteboltext">Published</a></td>
                                    <td width="20%"class="naGridTitle" align="center"> <a href="#" class="whiteboltext">Date</a></td>
                              </tr>
							  
							{if ($ARTICLE_LIST)}
                            {foreach from=$ARTICLE_LIST item=article}
                            
                           		<tr class="{cycle values="naGrid1,naGrid2"}">
								 <td width="2%"><input type="checkbox" name="chk[]" value="{$article.id}"></td>
                                    <td align="center" height="25" class="blacktext" width="25%"><div align="left"><strong class="blacktext" style="padding-left:15px"><a href="{makeLink mod=album pg=album_admin}act=article_det&id={$article.id}&link=Y{/makeLink}" class="toplink">{$article.album_name}</a> </strong></div></td>
                                    <td align="left" class="blacktext" width="15%" style="padding-left:18px">{if $article.published=='conference'}Conference Paper {elseif $article.published=='journal'}Journal Paper{elseif $article.published=='book'}Paper as a book Chapter{elseif $article.published=='report'}Report{/if}                               
                                  </td>
                                    <td  align="center" class="blacktext" width="20%">{if $article.published=='conference'}{$article.conference_id.conference_year} {elseif $article.published=='journal'}{$article.journal_id.journal_year}{elseif $article.published=='book'}{$article.book_id.book_year}{elseif $article.published=='report'}{$article.report_year}{/if}</td>
                              </tr>
                                  <tr>
                            {/foreach}
							</table></td></tr>
                            {if count($ARTICLE_LIST)>0}
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
							{/if}
							 <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="5" bgcolor="#F6F5F5" class="blacktext">
							  </td>
                          </tr>
                            <tr bgcolor="#DEDEDE">
                              <td height="20" colspan="5" >&nbsp;<span class="smalltext">{$NUMPAD}</span></td>
                            </tr>
							{else}
							  <tr bgcolor="#DEDEDE">
                              <td height="25" colspan="5" align="center"class="smalltext">&nbsp;<span style="color:#FF0000"><b>No article foumd.</b></span><br></td>
                            </tr>
							{/if}
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