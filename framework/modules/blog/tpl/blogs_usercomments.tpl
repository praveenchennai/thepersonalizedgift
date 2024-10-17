<table width="100%"  border="0"  cellpadding="0" cellspacing="0">
 <tr>
    <td height="100%" valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="90%">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">             
              <tr>
                <td height="19" align="center" valign="top" class="blacktext">
				<div align="left">
                    <table width="100%" height="20"  border="0" cellpadding="0" cellspacing="2">
                      <tr>
                        <td width="89%" height="25" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=blog pg=blog_category}act=list{/makeLink}">Browse</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="blackboldtext" href="{makeLink mod=blog pg=blog_subcategory}act=list&id={$CAT_NAME.id}{/makeLink}">{$CAT_NAME.cat_name}</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="blackboldtext" href="{makeLink mod=blog pg=bloglist}act=list&id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}{/makeLink}">{$SUBCAT_NAME.cat_name}</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<!--a class="blackboldtext" href="{makeLink mod=blog pg=blog_userentry}act=list&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&id={$BLOG_ENTRY_DETAILS.id}&user_id={$BLOGUSER_ID}{/makeLink}"-->
						<a class="blackboldtext" href="{$BLOG_ENTRY_DETAILS.uname}">{$BLOG_ENTRY_DETAILS.post_title}</a></td>
                        <td width="11%" align="right" valign="middle"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
                      </tr>
                    </table>
                </div>
				</td>
              </tr>
              <tr>
                <td height="244" align="center" valign="top" class="blacktext">
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="173" valign="top"><table width="172"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="172" valign="top">
							<table width="170" height="88"  border="0" align="center" cellpadding="3" cellspacing="0" class="leftblogBorder">
                                <tr class="leftmenuheading">
                                  <td width="100%" height="22" valign="top" bgcolor="#EEEEEE" class="leftmenuheading">User Menu </td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top"><a href="#" class="leftmenulink">Your profile</a></td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top"></td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top">&nbsp;</td>
                                </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td valign="top">
							<table width="170" height="132"  border="0" align="center" cellpadding="3" cellspacing="0" class="leftblogBorder">
                                <tr class="leftmenuheading">
                                  <td width="100%" height="22" valign="top">Read</td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top"><a href="{makeLink mod=blog pg=blog_subscribe}act=list{/makeLink}" class="leftmenulink">Subscribed Blogs</a></td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top">
									{if $SUBSCRIBE_BLOG!= 0}
									<a href="{makeLink mod=blog pg=blog_subscribe}act=unsubscribe&blog_id={$BLOG_ENTRY_DETAILS.blog_id}&blog_userid={$BLOGUSER_ID}{/makeLink}" class="leftmenulink" onclick="javascript: return confirm('Do you realy want to Un subscribe the blog?')">Unsubscribe</a>
									{else}
									<a href="{makeLink mod=blog pg=blog_subscribe}act=subscribe&blog_id={$BLOG_ENTRY_DETAILS.blog_id}&blog_userid={$BLOGUSER_ID}{/makeLink}" class="leftmenulink" onclick="javascript: return confirm('Do you realy want to subscribe?')">Subscribe</a>
									{/if}	
								  </td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top">&nbsp;</td>
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
                      </table>
					  </td>
                      <td valign="top">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="2" align="center">
							{if count($BLOG_ENTRY_DETAILS) > 0}
								<table width="95%"  border="0" align="right" cellpadding="0" cellspacing="0">
								  <tr>
									<td height="25"><span class="bodyheadingtext">{$BLOG_ENTRY_DETAILS.post_title}</span></td>
								  </tr>
								  <tr>
									<td>
									<span class="bodytext">
									{$BLOG_ENTRY_DETAILS.create_date}&nbsp;&nbsp;{$BLOG_ENTRY_DETAILS.blogentrytime}<br><br>
									</span>
									</td>
								  </tr>
								  <tr>
									<td class="bodytext">{$BLOG_ENTRY_DETAILS.post_description}</td>
								  </tr>
								</table>
							{/if}	
							</td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="95%"  border="0" align="right" cellpadding="3" cellspacing="0">
							  <tr>
							    <td height="24" colspan="3" class="bodyheadingtext">&nbsp;</td>
							    </tr>
							  <tr>
							    <td height="24" colspan="3" class="bodyheadingtext">&nbsp;Rate this :
                                	<a href="{makeLink mod=blog pg=blog_usercomments}act=rating&id={$BLOG_ENTRY_DETAILS.id}&user_id={$BLOGUSER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=1{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a>
									<a href="{makeLink mod=blog pg=blog_usercomments}act=rating&id={$BLOG_ENTRY_DETAILS.id}&user_id={$BLOGUSER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=2{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a>
									<a href="{makeLink mod=blog pg=blog_usercomments}act=rating&id={$BLOG_ENTRY_DETAILS.id}&user_id={$BLOGUSER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=3{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a>
									<a href="{makeLink mod=blog pg=blog_usercomments}act=rating&id={$BLOG_ENTRY_DETAILS.id}&user_id={$BLOGUSER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=4{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a>
									<a href="{makeLink mod=blog pg=blog_usercomments}act=rating&id={$BLOG_ENTRY_DETAILS.id}&user_id={$BLOGUSER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=5{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a>  
								</td>
							  </tr>
							  <tr>
								  <td height="24" colspan="3" class="bodyheadingtext">{$BLOG_ENTRY_DETAILS.blogs_comments_no} Comments</td>
							   </tr>
							  {if count($BLOG_COMMENTS) > 0} 
							 	 {foreach from=$BLOG_COMMENTS item=blogcomments}
							    <tr>
									<td width="1%" valign="top" class="bodytext">&nbsp;</td>
									<td width="18%" align="left" valign="top" class="bodytext">
									<span class="bodytext">{$blogcomments->name}<br>
									{if $blogcomments->image==Y }						
										<img src="{$GLOBAL.modbase_url}/member/images/userpics/thumb/{$blogcomments->user_id}.jpg" border="0" width="75" height="75">
									{else}						
										<img src="{$smarty.const.SITE_URL}/templates/default/images/nophoto.jpg" border="0" width="75" height="75">
									{/if}
									</span>
									</td>
									<td align="left" valign="top" class="bodytext">{$blogcomments->body}</td>
								</tr>
							  	{/foreach}
								  <tr>
									<td height="43" colspan="3" class="bodytext">{$COMMENTS_NUMPAD}</td>
								  </tr>
								{/if}
                            </table>
							</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">
							<form name="formComments" method="post" action="">
							  <table width="95%" align="right" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td align="left" class="bodyheadingtext">Post Comments</td>
                                  </tr>
                                <tr>
                                  <td width="86%">								
								   <textarea name="body" cols="60" rows="6" id="body" class="input"> </textarea>
									<input name="blog_entry_id" type="hidden" id="blog_entry_id" value="{$BLOG_ENTRY_DETAILS.id}">
									<input name="post_date" type="hidden" id="post_date" value="{$CUR_DATE}">
									<input type="hidden" name="subcat_id" value="{$SUBCAT_ID}">
									<input type="hidden" name="parent_id" value="{$PARENT_ID}">
                                    <input name="user_id" type="hidden" id="user_id" value="{$USER_ID}">                                    
                                    <input type="hidden" name="id" value="{$BLOG_ENTRY_DETAILS.id}">
                                    <input name="act" type="hidden" id="act" value="form">									
                                    <input name="blog_user_id" type="hidden" id="blog_user_id" value="{$BLOGUSER_ID}">
									</td>							
								</tr>
								 <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td><input type="submit" name="addcomments" value="Add Comments" class="btnBg"  {if ($USER_ID)== ''} onclick="javascript: return confirm('Login Required?')"{/if}></td>
                                </tr>
                              </table>
							</form>
							</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td height="18" align="center" valign="top" class="blacktext">&nbsp;</td>
              </tr>
          </table></td>
          <td width="5%">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>
