<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('groupname','tags','description');
	var msgs=new Array('Group Name','Tags','Decription');
</script>
<form name="frmGr" enctype="multipart/form-data" action="" method="post" onSubmit="return chk(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="5%">&nbsp;</td>
              <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                 <tr>
                  <td height="5" class="blackboldtext" valign="bottom" align="center"> <span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></td>
                </tr>
				<tr valign="middle">
                  <td height="39" class="blackboldtext"> Create a Group</td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span>                    <div align="left">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
                      <tr>
                        <td width="24%" align="right"><span class="blackboldtext">Group Name: </span></td>
                        <td width="3%" align="right">&nbsp;</td>
                        <td><span class="smalltext">
                          <input name="groupname" type="text" class="input" id="groupname">
                        </span></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right"><span class="blackboldtext">Tags:</span></td>
                        <td align="right">&nbsp;</td>
                        <td><span class="smalltext">
                          <input name="tags" type="text" class="input" id="tags">
                        </span></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td valign="top"> <strong>Enter one or more tags, separated by spaces.</strong> <br>
Tags are keywords used to describe your group so it can be easily found by other users. For example, if you have a group for surfers, you might tag it: surfing, beach, waves . </td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" valign="top"><span class="blackboldtext">Description:</span></td>
                        <td align="right">&nbsp;</td>
                        <td><textarea name="description" cols="45" rows="5" class="input" id="description"></textarea></td>
                      </tr>
                      <tr>
                        <td align="right" class="blackboldtext">&nbsp; </td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right"><span class="blackboldtext">Group Categories: </span></td>
                        <td align="right">&nbsp;</td>
                        <td><select name="category_id" class="input" id="category_id">
						{html_options values=$CAT_LIST.id output=$CAT_LIST.cat_name selected=4}
                        
                        </select></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" valign="top"><span class="blackboldtext">Type</span></td>
                        <td align="right">&nbsp;</td>
                        <td height="22" valign="top"><table width="464" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="31" height="22"><div align="left"><span class="smalltext">
                                <input name="type" type="radio" value="public" checked>
                            </span></div></td>
                            <td width="433" height="22" class="blacktext"> Public, anyone can join. </td>
                          </tr>
                          <tr>
                            <td height="22"><span class="smalltext">
                              <input name="type" type="radio" value="protected">
                            </span></td>
                            <td height="22" class="blacktext"> Protected, requires founder approval to join. </td>
                          </tr>
                          <tr>
                            <td height="22"><span class="smalltext">
                              <input name="type" type="radio" value="private">
                            </span></td>
                            <td height="22" class="blacktext"> Private, by founder invite only, only members can view group details. </td>
                          </tr>
                        </table></td>
                        </tr>
						
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
					  <tr>
                        <td align="right"><span class="blackboldtext">Group Image:</span></td>
                        <td align="right">&nbsp;</td>
                        <td><span class="smalltext">
                        <span class="field">
                        <input name="image" type="file">
                        </span> </span></td>
                      </tr>
					  <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
					   <tr>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td ><table width="100%"  border="0">
                          <tr>
                            <td width="29%"><input type="image" src="{$GLOBAL.tpl_url}/images/creategroup.jpg"></td>
                            <td width="71%"><a href="{makeLink mod=member pg=group}act=list{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg"  border="0"></a></td>
                          </tr>
                        </table>
						
						</td>
						<td>&nbsp;</td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
                
              </table></td>
              <td width="5%">&nbsp;</td>
            </tr>
          </table>
		  </form>