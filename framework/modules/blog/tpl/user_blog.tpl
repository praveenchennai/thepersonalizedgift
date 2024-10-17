<table  height="100%"  border="0"  cellpadding="0" cellspacing="0">
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
                                  <td width="100%" height="22" valign="top" bgcolor="#EEEEEE" class="leftmenuheading">User Menu</td>
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
                      </table></td>
                      <td width="721" valign="top">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="4" align="center">
							  <table width="95%"  border="0" align="right" cellpadding="3" cellspacing="0">
 								 {if count($BLOG_LIST) > 0} 
								  {foreach from=$BLOG_LIST item=bloglist}
								  <tr>
									<td width="100%" height="24" class="bodyheadingtext">{$bloglist->post_title}</td>
								  </tr>
								  <tr>
									<td valign="top" class="bodytext">{$bloglist->create_date}&nbsp;{$bloglist->blogentrytime}<br><br>
									  {$bloglist->post_description}
									</td>
								  </tr>
								  <tr>
								    <td><table border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td><a href="{makeLink mod=blog pg=blog_usercomments}act=list&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}{/makeLink}" class="blogMiddlelink"><span class="bodytext">
                                          </span></a></td>
                                        <td>
                                        <a href="{makeLink mod=blog pg=blog_comments}act=list{/makeLink}&id={$bloglist->id}" class="blogMiddlelink">{$bloglist->blogs_comments_no} Comments</a></td>
                                        <td width="30">&nbsp;</td>
                                        <td><span class="bodytext"><a href="{makeLink mod=blog pg=blog_entry}act=form&id={$bloglist->id}{/makeLink}" class="blogMiddlelink">Edit It</a></span></td>
                                        <td width="30">&nbsp;</td>
                                        <td><span class="bodytext"><a href="{makeLink mod=blog pg=blog_email}act=form&subcat_id={$BLOG_DETAILS.subcat_id}&parent_id={$BLOG_DETAILS.cat_id}&user_id={$BLOG_DETAILS.user_id}{/makeLink}" class="blogMiddlelink">Email It</a> </span></td>
                                      </tr>
                                    </table></td>
							    </tr>
								<tr>
									<td><hr size="1"></td>
								</tr>
								{/foreach}
								  <tr>
									<td height="43" valign="top" class="bodytext">{$BLOG_NUMPAD}</td>
								  </tr>
  							{/if}
                            </table>
							</td>
                          </tr>
                          
                          <tr>
                            <td colspan="4">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="4">&nbsp;</td>
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
