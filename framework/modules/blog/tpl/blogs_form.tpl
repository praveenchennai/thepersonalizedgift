<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
	<td width="10%" height="39" class="naH1">&nbsp;</td>
    <td width="90%" class="naH1">Blog Details</td>
 </tr>  
  <tr>
    <td colspan="2" align="center">
      <table width="80%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="80%" height="169" align="left" valign="top" bgcolor="#EEEEEE">
          	<table width=100% border=0 align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td valign=top colspan=4 ></td>
              </tr>
              	<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;">
				  <tr>
					<td height="15" colspan=4 valign=top>&nbsp;</td>
				  </tr>
				  <tr>
					<td valign=top colspan=4>&nbsp;</td>
				  </tr>
				  {if isset($MESSAGE)}
				  <tr>
					<td valign=top colspan=4><div align=center class="element_style"><span class="naError">{$MESSAGE}</span></div></td>
				  </tr>
   				 {/if}
				<tr>
				  <td colspan=4></td>
				</tr>
				<tr>
				  <td width="104"></td>
				  <td colspan="3" ></td>
				</tr>
				<tr>
				  <td colspan=4></td>
				</tr>
				<tr>
				  <td colspan="2" align="right" valign=middle ><span class="blackboldtext">Category  </span>&nbsp; </td>
				  <td valign=middle><span class="blackboldtext">:</span></td>
				  <td valign="middle">				 
					<select name=cat_id onchange="window.location.href='{makeLink mod=blog pg=blog}act=form{/makeLink}&cat_id='+this.value" class="input">
						<option value="">-- SELECT A CATEGORY --</option>
						{html_options values=$SECTION_LIST.id output=$SECTION_LIST.cat_name selected=$CAT_ID}
					</select>				  
				  </td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign=middle>&nbsp;</td>
				  <td valign="middle">&nbsp;</td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle ><span class="blackboldtext">Subcategory</span> </td>
				  <td valign=middle align="center"><span class="blackboldtext">:</span></td>
				  <td valign="middle">
				  <select name=subcat_id class="input">
          			<option value="">-- SELECT A SUBCATEGORY --</option>
          				{html_options values=$SUBCATLIST.id output=$SUBCATLIST.cat_name selected=$BLOG_DETAILS.subcat_id}
					</select>
				  </td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign=middle>&nbsp;</td>
				  <td valign="middle">&nbsp;</td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle ><span class="blackboldtext">Blog Name  </span>&nbsp;</td>
				  <td width=6 valign=middle><span class="blackboldtext">:</span></td>
				  <td valign="middle">
					<input name="blog_name" type="text" class="input" id="blog_name"  size="30" maxlength="25" value="{$BLOG_DETAILS.blog_name}">
					<input name="create_date" type="hidden" id="create_date" value="{$CUR_DATE}">
				  </td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="2" align="right" valign=middle> <span class="blackboldtext">Body </span>&nbsp;</td>
				  <td valign=middle><span class="blackboldtext">:</span></td>
				  <td valign="middle">
					<textarea name="blog_description" cols="40" rows="5" class="input" id="blog_description">{$BLOG_DETAILS.blog_description}</textarea>
					<input type="hidden" name="active" value="y">
					<input type="hidden" name="user_id" value="{$USER_ID}">
					<input type="hidden" name="id" value="{$BLOG_DETAILS.id}">
				  </td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td valign=top>&nbsp;</td>
				  <td><span class="blackboldtext">Select Template</span></td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td height="67" colspan="2" valign=top><div align=right class="element_style"> </div></td>
				  <td valign=top>&nbsp;</td>
				  <td>       
				   <table width="75%" border="0" cellspacing="0" cellpadding="0">
					<tr> 
						{if count($TEMPLATE_LIST) > 0} 
							{foreach from=$TEMPLATE_LIST item=template name=template}
								<td align="center"> 
								<img  src="{$GLOBAL.mod_url}/images/template/{$template->temp_image}" border="0" height="108" width="108"><br>
								<input name="temp_id" type="radio"  value="{$template->id}"{if $template->id==$BLOG_DETAILS.temp_id} checked {/if}>
								</td>
								{if $smarty.foreach.template.index % 2 == 1} 
					  </tr><tr>
								 {/if} 
							 {/foreach} 
						 {/if} 
						 </tr>
				  </table>
				  </td>
				</tr>
				<tr align="center" valign="middle">
				  <td colspan=4>
					<input name="submit" type=submit class="btnBg" value="Submit" {if $BLOG_DETAILS.id} onClick="return confirm('Do you realy want to change the settings')"{/if}>
					&nbsp;
				  <input name="reset" type=reset class="btnBg" value="Reset"></td>
				</tr>
				<tr align="center">
				  <td colspan=4 valign=center>&nbsp;</td>
				</tr>
				<tr align="center">
				  <td colspan=4 valign=center>&nbsp;</td>
				</tr>
  			</form>
		 		</table>
		  </td>
  			</tr>
  		  </table>
		</td>
 	 </tr>
</table>
