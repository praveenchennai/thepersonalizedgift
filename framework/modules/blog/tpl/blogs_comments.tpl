<table  height="100%"  border="0"  cellpadding="0" cellspacing="0">
 <tr>
    <td height="100%" valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="95%">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">             
              <tr>
                <td height="19" align="center" valign="top" class="blacktext">
				<div align="left">
                    <table width="100%" height="20"  border="0" cellpadding="0" cellspacing="2">
                      <tr>
                        <td align="right" valign="middle">
							<a href="{makeLink mod=blog pg=blog_entry}act=form{/makeLink}" class="navigationlink">New Blog Entry</a>
							<span class="navigationlink">|</span> 
							<a href="{makeLink mod=album pg=photo}act=list{/makeLink}" class="navigationlink">Photos</a>
							<span class="navigationlink">|</span>
							<a href="{makeLink mod=album pg=video}act=list{/makeLink}" class="navigationlink">Videos</a>
							<span class="navigationlink">|</span>
							<a href="{makeLink mod=member pg=home}act=form{/makeLink}" class="navigationlink">Profile</a>
						</td>
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
                                  <td height="22" valign="top"><a href="{makeLink mod=member pg=home}act=form{/makeLink}" class="leftmenulink">Your profile</a></td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top"><a href="{makeLink mod=blog pg=blogSettings}act=form{/makeLink}" class="leftmenulink">Look & Feel</a></td>
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
                            <td valign="top">&nbsp;							</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;							</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                      </table>
					  </td>
                      <td width="721" valign="top">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="4" align="center">
							{if count($BLOG_ENTRY_DETAILS) > 0}
								<table width="95%"  border="0" align="right" cellpadding="0" cellspacing="0">
								  <tr>
									<td><span class="bodyheadingtext">{$BLOG_ENTRY_DETAILS.post_title}</span></td>
								  </tr>
								  <tr>
									<td width="94%">
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
						  {if count($BLOG_COMMENTS) > 0} 
                          <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                          </tr>
						   <tr>
							 <td height="24" class="bodyheadingtext">&nbsp;</td>
						     <td height="24" colspan="3" class="bodyheadingtext">Comments :
					         {$BLOG_ENTRY_DETAILS.blogs_comments_no}</td>
					      </tr>						
							 {foreach from=$BLOG_COMMENTS item=blogcomments}
                             <tr>
                               <td valign="top" class="bodytext">&nbsp;</td>
                               <td align="left" valign="top" class="bodytext">&nbsp;</td>
                               <td align="left" valign="top" class="bodytext">&nbsp;</td>
                               <td align="left" valign="top" class="bodytext">&nbsp;</td>
                             </tr>
                          <tr>
							<td width="6%" valign="top" class="bodytext">&nbsp;</td>
							<td width="24%" align="left" valign="top" class="bodytext">
							<span class="bodytext">{$blogcomments->name}<br>
							{if $blogcomments->image==Y }						
										<img src="{$GLOBAL.modbase_url}/member/images/userpics/thumb/{$blogcomments->user_id}.jpg" border="0" width="75" height="75">
									{else}						
										<img src="{$smarty.const.SITE_URL}/templates/default/images/nophoto.jpg" border="0" width="75" height="75">
									{/if}
							
							<td width="59%" align="left" valign="top" class="bodytext">{$blogcomments->body}</td>
							<td width="11%" align="left" valign="top" class="bodytext"><a class="blogMiddlelink" href="{makeLink mod=blog pg=blog_comments}act=delete&id={$blogcomments->id}&blog_entry_id={$blogcomments->blog_entry_id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')">Delete</a></td>
						  </tr>
							  	{/foreach}
								  <tr>
									<td height="43" valign="top" class="bodytext">&nbsp;</td>
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
							<form name="formComments" method="post" action="" style="margin:0px; ">
							  <table width="100%%"  border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="16%">&nbsp;</td>
                                  <td align="left" class="bodyheadingtext">&nbsp;</td>
                                  <td align="left" class="bodyheadingtext">Post Comments</td>
                                  <td align="left" class="bodyheadingtext">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td align="center">&nbsp;</td>							
								  <td align="left"><textarea name="body" cols="60" rows="6" id="textarea" class="input"> </textarea>
								    <input name="blog_entry_id" type="hidden" id="blog_entry_id" value="{$BLOG_ENTRY_DETAILS.id}">
                                    <input name="post_date" type="hidden" id="post_date" value="{$CUR_DATE}">
                                    <input name="user_id" type="hidden" id="user_id" value="{$USER_ID}">
                                    <input type="hidden" name="id" value="{$BLOG_ENTRY_DETAILS.id}">
                                    <input name="act" type="hidden" id="act" value="form"></td>
								  <td align="center">&nbsp;</td>
                                </tr>
								 <tr>
                                  <td>&nbsp;</td>
                                  <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td width="13%" align="center">&nbsp;</td>
                                  <td width="56%" align="left"><input type="submit" name="addcomments" value="Add Comments" class="btnBg"  {if ($USER_ID)==''} onClick="javascript: return confirm('Login Required?')"{/if}></td>
                                  <td width="15%" align="center">&nbsp;</td>
                                </tr>
                              </table>
							</form>
							</td>
                          </tr>
                      </table>
					  </td>
                    </tr>
                </table>
				</td>
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
