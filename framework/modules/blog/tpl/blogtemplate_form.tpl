{literal}
<script language="javascript">
function displayAppropriateHeader(theSelect) {	
	var headerChoice= theSelect;	
		switch(headerChoice) {
		case "0": {
			document.getElementById('hideHeader').style.display="inline"; 
			document.getElementById('customHeader').style.display="none";
			document.getElementById('inputHeader').style.display="none";
			break;
		}
	    case "1": {
			document.getElementById('hideHeader').style.display="none"; 
			document.getElementById('customHeader').style.display="inline";
			document.getElementById('inputHeader').style.display="none";
			break;
		}
		case "2": {
			document.getElementById('hideHeader').style.display="none"; 
			document.getElementById('customHeader').style.display="none";
			document.getElementById('inputHeader').style.display="inline";
			break;
		}
	}
}
function displaySearchbar(theSelect) {
	var headerChoice= theSelect;
		switch(headerChoice) {
		case "N": {
			document.getElementById('hideSearch').style.display="inline"; 
			document.getElementById('showSearch').style.display="none";
			break;
		}
	    case "Y": {
			document.getElementById('showSearch').style.display="inline"; 
			document.getElementById('hideSearch').style.display="none";
			break;
		}
	}
}
function checkWidth(pageWidth)
{
if(pageWidth >800)
 { 	 
 	document.getElementById('ShowError').style.display="inline";
 }
 else
 {
	 document.getElementById('ShowError').style.display="none";
 }
}
function setWin()
{
 var mywindow=window.open ("",
"mywindow","menubar=0,toolbar=0,height="+(screen.height-100)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=10,top=10");
}
function setParam()
{
	document.blogFrm.target="mywindow";
	setWin();
}
function unsetParam()
{
	document.blogFrm.target="";
}

</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class=naBrdr> 
  <tr align="left">
      <td colspan=3 valign=top>
	  <table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">Look & Feel </td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=blog pg=blogtemplate}act=list{/makeLink}">Template List</a></td>
        </tr>
      </table></td>
    </tr>
  <tr>
    <td height="23" colspan="2" align="left" valign="middle" class="blacktext"><span class="smalltext"><strong class="greyboldtext style1">Page Width &amp; Alignment </strong></span><span class="smalltext"> </span></td>
  </tr>
  <tr>
    <td height="19" colspan="2" align="center" valign="top" class="blacktext"><div align="left"></div></td>
  </tr>
  <tr>
    <td height="244" colspan="2" align="center" valign="top" class="blacktext">	
	<form action="" method="POST" enctype="multipart/form-data"  name="blogFrm" style="margin: 0px;" >
      <table width="100%"  border="0" cellpadding="0" cellspacing="0">
        <tr class="naGrid1">
          <td colspan="2">		
		    <table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class=naBrDr>
              <tr>
                <td width="571" height="136" align="left" valign="top" >
				<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
					  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="5">&nbsp;</td>
                          </tr>
                          <tr>
                            <td width="103" align="left"><span class="group_style">Width: </span></td>
                            <td width="4">&nbsp;</td>
                            <td colspan="2" align="left">
							 <input name="blog_id" type="hidden" id="blog_id" value="{$BLOG_ID}">
							<span class="smalltext">
                              <input name="page_width" type="text" id="page_width" value="{$TEMPLATE.temp_width}" class="input" onKeyUp="checkWidth(this.value)">
                            </span>
							</td>
                            <td width="85"><span class="smalltext"></span></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="2" align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left"><span class="style9">Alignment: </span> </td>
                            <td>&nbsp;</td>
                            <td colspan="2" align="left"><span class="smalltext">
                              <select name="page_align" id="page_align" class="input">
								<option value="left" {if $TEMPLATE.temp_align=='left'} selected{/if}>left</option>
								<option value="center" {if $TEMPLATE.temp_align=='center'} selected{/if}>center</option>
							 </select>
                            </span>
							</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="2" align="left">&nbsp;</td>
                            <td><span class="smalltext"> </span></td>
                          </tr>
                          <tr>
                            <td colspan="5" align="right"><div align="left"><span class="style9"></span> <span class="smalltext"> </span>
                                    <table width="91%"  border="0" cellspacing="0" cellpadding="0">
                                      <tr align="left" valign="middle">
                                        <td width="21%"><span class="smalltext"> </span></td>
                                        <td width="7%">
										<span class="smalltext"><input name="page_type" type="radio" value="P" {if $TEMPLATE.temp_type=='P'} checked{/if}></span>
                                       </td>
                                        <td width="21%"><span class="smalltext"><span class="style9">Percent</span></span></td>
                                        <td width="7%">
										<span class="smalltext"><input name="page_type" type="radio" value="X" {if $TEMPLATE.temp_type=='X'} checked{/if}></span>
                                        </td>
                                        <td width="19%"><span class="smalltext"><span class="style9">Pixels</span></span></td>
                                        <td width="27%"><span class="footerlink"> </span></td>
                                      </tr>
                                  </table>
                                    <span class="smalltext"></span></div>
							</td>
                          </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="2" align="left">&nbsp;</td>
                            <td>&nbsp;</td>
						   </tr>
						 <tr>
							<td colspan="5" align="right">
						  	<div id="ShowError" style="display:{if $TEMPLATE.temp_width>800}inline{else}none{/if}">	
								Warning: The width of your page is more than 800 pixels; some of your readers will have to scroll horizontally to read it.
							</div>
							  </td>
							</tr>
							
						</table>
						</td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td width="1%" align="left" valign="top">
		</td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr align="left">
          <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Header</strong></span><span class="smalltext"></span></td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid2">
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="571">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>					
					 <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0"  class="smalltext">
                       <tr>
                         <td width="2%">&nbsp;</td>
                         <td width="98%">&nbsp; </td>
                         </tr>
                       <tr>
                         <td colspan="2">
						 <select name="header_type" id="header_type"   class="input" onChange="displayAppropriateHeader(this.value);">
                             <option value="0" {if $TEMPLATE.header_type==0 } selected{/if}>Hide Header</option>
                             <option  value="1" {if $TEMPLATE.header_type ==1 } selected{/if}>Create a Custom Header</option>
                             <option value="2" {if $TEMPLATE.header_type==2 } selected{/if}>Input your own Header HTML</option>
                         </select> 
						 </td>
                         </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp; </td>
                         </tr>
                       <tr>
                         <td colspan="2" class="smalltext">
						{if $TEMPLATE.header_type==1}  
							<div id="customHeader" style="display:inline">
						{else}
							<div id="customHeader" style="display:none">
						{/if}
						 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                             <tr>
								 <td width="16%" class="smalltext">Site Name:</td>
								 <td width="1%">&nbsp;</td>
								 <td width="30%" class="smalltext" ><input name="site_txt" type="text" id="site_txt" value="{$TEMPLATE.site_text}" class="input"></td>
								 <td width="1%">&nbsp;</td>
								 <td width="15%" class="smalltext">&nbsp;</td>
                      		 </tr>
                             <tr>
                               <td class="smalltext">&nbsp;</td>
                               <td>&nbsp;</td>
                               <td class="smalltext" >&nbsp;</td>
                               <td>&nbsp;</td>
                               <td class="smalltext">&nbsp;</td>
                             </tr>
                             <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >{colorPicker name="stxt_clr" formName="document.blogFrm" value=$TEMPLATE.site_txtcolor}{/colorPicker} </td>
                         <td>&nbsp;</td>
                         <td align="left" class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" ><select name="stxt_font" id="stxt_font" style="margin-top: 2px;" class="input">
                           <option value="">-- SELECT FONT --</option>
                           {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$TEMPLATE.site_font}
							</select>
							</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >
						 <select name="stxt_fontsize" id="stxt_fontsize" style="margin-top: 2px;" class="input">
                           <option value="8" {if $TEMPLATE.site_fontsize==8} selected{/if}>1 (smallest)</option>
                           <option value="10" {if $TEMPLATE.site_fontsize==10} selected{/if}>2</option>
                           <option value="12" {if $TEMPLATE.site_fontsize==12} selected{/if}>3 (standard)</option>
                           <option value="14" {if $TEMPLATE.site_fontsize==14} selected{/if}>4</option>
                           <option value="18" {if $TEMPLATE.site_fontsize==18} selected{/if}>5</option>
                           <option value="24" {if $TEMPLATE.site_fontsize==24} selected{/if}>6</option>
                           <option value="36" {if $TEMPLATE.site_fontsize==36} selected{/if}>7 (largest)</option>
                         </select></td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">Border:</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >{colorPicker name="border_clr" formName="document.blogFrm" value=$TEMPLATE.header_bordercolor}{/colorPicker}</td>
                         <td>&nbsp;</td>
                         <td align="left" class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">Interior:</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >{colorPicker name="inter_clr" formName="document.blogFrm" value=$TEMPLATE.header_interior}{/colorPicker}</td>
                         <td>&nbsp;</td>
                         <td align="left" class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></td>
                             </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td width="10%" height="25">Tag Name:</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" ><input name="tag_txt" type="text" id="tag_txt" value="{$TEMPLATE.header_tagtext}" class="input"></td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >{colorPicker name="tag_clr" formName="document.blogFrm" value=$TEMPLATE.header_tagcolor}{/colorPicker}</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >
						 <select name="tag_font" id="tag_font" style="margin-top: 2px;" class="input">
                           <option value="">-- SELECT FONT --</option>
                           {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$TEMPLATE.header_tagfont}
                         </select>
						 </td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >
						 <select name="tag_fontsize" id="tag_fontsize" style="margin-top: 2px;" class="input">
                           <option value="8" {if $TEMPLATE.header_tagfontsize==8} selected{/if}>1 (smallest)</option>
                           <option value="10" {if $TEMPLATE.header_tagfontsize==10} selected{/if}>2</option>
                           <option value="12" {if $TEMPLATE.header_tagfontsize==12} selected{/if}>3 (standard)</option>
                           <option value="14" {if $TEMPLATE.header_tagfontsize==14} selected{/if}>4</option>
                           <option value="18" {if $TEMPLATE.header_tagfontsize==18} selected{/if}>5</option>
                           <option value="24" {if $TEMPLATE.header_tagfontsize==24} selected{/if}>6</option>
                           <option value="36" {if $TEMPLATE.header_tagfontsize==36} selected{/if}>7 (largest)</option>
                         </select>
						 </td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext" >&nbsp;</td>
                         <td>&nbsp;</td>
                         <td class="smalltext">&nbsp;</td>
                       </tr>
                       </table>
					   </div>
					    {if $TEMPLATE.header_type==0}
							<div id="hideHeader" style="display:inline">
						{else}
							<div id="hideHeader" style="display:none">
						{/if}	
                           <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                             <tr align="left" valign="middle">
                              <td width="62%" colspan="2">No header will appear No header will appear </td>
                            </tr>
                             <tr align="left" valign="middle">
                               <td colspan="2">&nbsp;</td>
                             </tr>
                           </table>
						   </div>
						 {if $TEMPLATE.header_type==2}
								<div id="inputHeader" style="display:inline">
						{else}
								<div id="inputHeader" style="display:none">
						{/if}	
							   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
								 <tr>
								   <td width="26%">Module:</td>
								   <td width="74%"> <textarea name="own_header" cols="45" rows="10" class="input">{$TEMPLATE.own_header}</textarea></td>
								 </tr>
							   </table>
						  	 </div>
						   </td>
                         </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         </tr>
                     </table>					
				 	</td>
                  </tr>
                </table>				
				</td>
              </tr>
          </table>
		  </td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr align="left">
          <td height="22" colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Background</strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid1"> 
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top"><table width="600"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="left" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="smalltext">
                              <tr>
                                <td width="20%" height="23"class="smalltext" >&nbsp;</td>
                                <td width="5%">&nbsp;</td>
                                <td width="35%">&nbsp;</td>
                                <td width="5%">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="23"class="smalltext" >Color:</td>
                                <td width="5%">&nbsp;</td>
                                <td>{colorPicker name="bgcolor" formName="document.blogFrm" value=$TEMPLATE.bg_color}{/colorPicker}</td>
                                <td>&nbsp;</td>
                                <td width="30%" class="footerlink"><a href="#" class="footerlink">transparent</a></td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp; </td>
                                <td width="5%">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
							 <tr>
								<td class="smalltext">Pic: </td>
								<td>&nbsp;</td>								
								<td colspan="3" class="smalltext">
								  <input type="file" name="image" class="input" value="{$GLOBAL.mod_url}/images/{$TEMPLATE.temp_image}">    
								</td>
                             </tr>
								{if $TEMPLATE.temp_image }
								<tr>
								  <td class="smalltext">&nbsp;</td>
								  <td>&nbsp;</td>
								  <td class="smalltext">&nbsp;</td>
								  <td colspan="2" class="smalltext">&nbsp;</td>
								  </tr>
								<tr>
									<td class="smalltext">&nbsp;</td>
									<td>&nbsp;</td>
									<td class="smalltext"><img src="{$GLOBAL.mod_url}/images/template/{$TEMPLATE.temp_image}" border="0" height="75" width="75">
                                      <input name="picture" type="hidden" id="picture"  value="{$TEMPLATE.temp_image}">
									</td>
									<td colspan="2" class="smalltext">&nbsp;</td>
								</tr>
							   {/if}
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >
								<select name="position" id="position" class="input">
                                    <option value="top left" {if $TEMPLATE.bg_position=='top left'} selected{/if}>top left</option>
                                    <option value="top center"{if $TEMPLATE.bg_position=='top center'} selected{/if}>top center</option>
                                    <option value="top right" {if $TEMPLATE.bg_position=='top right'} selected{/if}>top right</option>
                                    <option value="center left"{if $TEMPLATE.bg_position=='center left'} selected{/if}>center left</option>
                                    <option value="center center"{if $TEMPLATE.bg_position=='center center'} selected{/if}>centered</option>
                                    <option value="center right" {if $TEMPLATE.bg_position=='center right'} selected{/if}>center right</option>
                                    <option value="bottom left" {if $TEMPLATE.bg_position=='bottom left'} selected{/if}>bottom left</option>
                                    <option value="bottom center"{if $TEMPLATE.bg_position=='bottom center'} selected{/if}>bottom center</option>
                                    <option value="bottom right" {if $TEMPLATE.bg_position=='bottom right'} selected{/if}>bottom right</option>
                                </select></td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" ><select name="repeat" id="repeat" class="input">
                                    <option value="repeat" {if $TEMPLATE.repeat=='repeat'} selected{/if}>repeat in all directions</option>
                                    <option value="repeat-x"{if $TEMPLATE.repeat=='repeat-x'} selected{/if}>repeat horizontally</option>
                                    <option value="repeat-y"{if $TEMPLATE.repeat=='repeat-y'} selected{/if}>repeat vertically</option>
                                    <option value="no-repeat"{if $TEMPLATE.repeat=='no-repeat'} selected{/if}>don't repeat</option>
                                </select></td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >
								<select name="scroll" id="scroll"  class="input">
                                    <option value="scroll" {if $TEMPLATE.scroll=='scroll'} selected{/if}>scroll with page</option>
                                    <option value="fixed"  {if $TEMPLATE.scroll=='fixed'} selected{/if}>keep it fixed</option>
                                </select></td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
              </tr>
          </table></td>
          <td align="left" valign="top">		
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr align="left">
          <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Text &amp; Links</strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid2">
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table  class="smalltext" width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="20%" height="23"class="smalltext" >&nbsp;</td>
                          <td width="5%">&nbsp;</td>
                          <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="20"class="smalltext" >Text:</td>
                          <td width="5%">&nbsp;</td>
                          <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="58%">
                                  <select name=txtfont class="input">
                                    <option value="">-- SELECT FONT --</option>
                                    {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$TEMPLATE.txt_font}			
                              		</select>
                                </td>
                                <td width="42%">								
                                  <select name="txtfontsize" id="txtfontsize" style="margin-top: 2px;" class="input">
                                    <option value="8" {if $TEMPLATE.txt_font_size==8} selected{/if}>1 (smallest)</option>
                                    <option value="10" {if $TEMPLATE.txt_font_size==10} selected{/if}>2</option>
                                    <option value="12" {if $TEMPLATE.txt_font_size==12} selected{/if}>3 (standard)</option>
                                    <option value="14" {if $TEMPLATE.txt_font_size==14} selected{/if}>4</option>
                                    <option value="18" {if $TEMPLATE.txt_font_size==18} selected{/if}>5</option>
                                    <option value="24" {if $TEMPLATE.txt_font_size==24} selected{/if}>6</option>
                                    <option value="36" {if $TEMPLATE.txt_font_size==36} selected{/if}>7 (largest)</option>
                                  </select>
                                </td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td width="38%">&nbsp; </td>
                          <td width="5%">&nbsp;</td>
                          <td width="30%">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">{colorPicker name="txtclr" formName="document.blogFrm" value=$TEMPLATE.txt_fontcolor}{/colorPicker}</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext"><span class="blackboldtext "  >Links:</span></td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">Normal :</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >{colorPicker name="nrm_clr" formName="document.blogFrm" value=$TEMPLATE.txt_linknormal}{/colorPicker}</td>
                          <td><input name="nrm_uline" type="checkbox" id="nrm_uline" value="Y" {if $TEMPLATE.txt_normaluline=='Y'} checked{/if}></td>
                          <td class="smalltext"><span class="footerlink">underline</span></td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">Active :</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >{colorPicker name="act_clr" formName="document.blogFrm" value=$TEMPLATE.txt_linkactive}{/colorPicker}</td>
                          <td><input name="act_uline" type="checkbox" id="act_uline" value="Y" {if $TEMPLATE.txt_activeuline=='Y'} checked{/if}></td>
                          <td class="smalltext"><span class="footerlink">underline</span></td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">Visited :</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >{colorPicker name="visit_clr" formName="document.blogFrm" value=$TEMPLATE.txt_linkvisit}{/colorPicker}</td>
                          <td><input name="visit_uline" type="checkbox" id="visit_uline" value="Y" {if $TEMPLATE.txt_vistuline=='Y'} checked{/if}></td>
                          <td class="smalltext"><span class="footerlink">underline</span></td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="smalltext">Hover :</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >{colorPicker name="hover_clr" formName="document.blogFrm" value=$TEMPLATE.txt_linkhover}{/colorPicker}</td>
                          <td><input name="hover_uline" type="checkbox" id="hover_uline" value="Y" {if $TEMPLATE.txt_hoveruline=='Y'} checked{/if}></td>
                          <td class="smalltext"><span class="footerlink">underline</span></td>
                        </tr>
                        <tr>
                          <td class="smalltext">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext" >&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="smalltext">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                </table>                  
                  </td>
              </tr>
          </table></td>
          <td align="left" valign="top">
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr align="left">
          <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Left module</strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr  class="naGrid1">
          <td colspan="2"><table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="136" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle"><span class="style11">Border</span><span class="smalltext"> </span><span class="smalltext"> </span></td>
                            </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            </tr>
                          <tr>
                            <td align="right">
							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr align="left" valign="middle">
                                  <td width="17%"><p class="smalltext">Color: </p></td>
                                  <td width="5%"><span class="smalltext"> </span></td>
                                  <td width="35%">
									  <span class="smalltext">
										{colorPicker name="brd_clr" formName="document.blogFrm" value=$TEMPLATE.left_bordercolor}{/colorPicker}
									  </span>
								  </td>
                                  <td width="30%"><span class="footerlink"><span class="smalltext"><a href="#" class="footerlink">transparent</a></span></span></td>
                                </tr>
                                <tr align="left" valign="middle">
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr align="left" valign="middle">
                                  <td><span class="smalltext">Style: </span></td>
                                  <td>&nbsp;</td>
                                  <td><span class="smalltext"></span>
									<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="45%" valign="top" class="smalltext">
                                          <select name="brd_style" id="brd_style" class="input">
                                            <option value="none"  {if $TEMPLATE.left_brd_style=='none'} selected{/if}>none</option>
                                            <option value="solid" {if $TEMPLATE.left_brd_style=='solid'} selected{/if}>solid</option>
                                            <option value="dotted"{if $TEMPLATE.left_brd_style=='dotted'} selected{/if}>dotted</option>
                                            <option value="dashed"{if $TEMPLATE.left_brd_style=='dashed'} selected{/if}>dashed</option>
                                            <option value="double"{if $TEMPLATE.left_brd_style=='double'} selected{/if}>double</option>
                                            <option value="groove"{if $TEMPLATE.left_brd_style=='groove'} selected{/if}>groove</option>
                                            <option value="ridge" {if $TEMPLATE.left_brd_style=='ridge'} selected{/if}>ridge</option>
                                            <option value="inset" {if $TEMPLATE.left_brd_style=='inset'} selected{/if}>inset</option>
                                            <option value="outset"{if $TEMPLATE.left_brd_style=='outset'} selected{/if}>outset</option>
                                          </select>
                                       </td>
                                        <td width="55%"><input name="brd_pixel" type="text" id="brd_pixel" size="1" value="{$TEMPLATE.left_brd_pixel}" class="input"></td>
                                      </tr>
                                    </table>
                                    <span class="smalltext"> </span></td>
                                  <td><span class="footerlink"><span class="smalltext">pixel(s) wider</span></span></td>
                                </tr>
                            </table>
							</td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table>
                  </td>
              </tr>
          </table></td>
          <td>
		 
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid2">
          <td colspan="2"><table width="100%" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="131" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="right"><div align="left"><span class="smalltext"><span class="style11">Titile</span></span></div></td>
                          </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            </tr>
                          <tr>
                            <td align="right">	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="17%"><p class="smalltext">Background:</p></td>
                                <td width="5%"><span class="smalltext"> </span></td>
                                <td width="35%"> <span class="smalltext">{colorPicker name="title_bgclr" formName="document.blogFrm" value=$TEMPLATE.left_titlebgcolor}{/colorPicker} </span> </td>
                                <td width="30%"><span class="footerlink"><span class="smalltext"><a href="#" class="footerlink">transparent</a></span></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td><span class="smalltext">Text: </span></td>
                                <td>&nbsp;</td>
                                <td><span class="smalltext"> </span> <span class="smalltext">{colorPicker name="title_txt_clr" formName="document.blogFrm" value=$TEMPLATE.left_titlecolor}{/colorPicker} </span></td>
                                <td><span class="footerlink"></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td><span class="smalltext">Alignment:</span></td>
                                <td>&nbsp;</td>
                                <td><span class="smalltext">
                                  <select name="title_align" id="title_align" class="input">
                                    <option value="left" {if $TEMPLATE.left_title_align=='left'} selected{/if}>Left</option>
                                    <option value="center" {if $TEMPLATE.left_title_align=='center'} selected{/if}>Center</option>
                                    <option value="right" {if $TEMPLATE.left_title_align=='right'} selected{/if}>Right</option>
                                  </select>
                                </span></td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                            </table></td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid1">
          <td colspan="2"><table width="100%" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="131" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="right"><div align="left"><span class="smalltext"><span class="style11">Interior</span></span></div></td>
                        </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          </tr>
                        <tr>
                          <td align="right"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="17%" class="smalltext">Background:</td>
                                <td width="5%" class="smalltext"> </td>
                                <td width="35%" class="smalltext"> {colorPicker name="intr_bgclr" formName="document.blogFrm" value=$TEMPLATE.left_interiorcolor}{/colorPicker} </span> </td>
                                <td width="30%"><span class="footerlink"><span class="smalltext"><a href="#" class="footerlink">transparent</a></span></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td class="smalltext">&nbsp;</td>
                                <td class="smalltext"></td>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td class="smalltext">Text:</td>
                                <td class="smalltext"></td>
                                <td class="smalltext">{colorPicker name="intr_txt_clr" formName="document.blogFrm" value=$TEMPLATE.interior_txt_color}{/colorPicker}</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td class="smalltext">&nbsp;</td>
                                <td class="smalltext"></td>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td class="smalltext">Alignment:</td>
                                <td class="smalltext"></td>
                                <td class="smalltext">
								<select name="intr_align" id="intr_align" class="input">
                                  <option value="left"  {if $TEMPLATE.left_align=='left'} selected{/if}>Left</option>
                                  <option value="center"{if $TEMPLATE.left_align=='center'} selected{/if}>Center</option>
                                  <option value="right" {if $TEMPLATE.left_align=='right'} selected{/if}>Right</option>
                                </select>
								</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td class="smalltext">&nbsp;</td>
                                <td class="smalltext"></td>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid2">
          <td colspan="2">
		  <table width="100%" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="131" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="right"><div align="left"><span class="smalltext"><span class="style11">Music</span></span></div></td>
                          </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            </tr>
                          <tr>
                            <td align="right">
							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="96"><p class="smalltext">Music: </p></td>
                                <td width="22"><span class="smalltext"> </span></td>
                                <td width="191"> <span class="smalltext"> 
                                  <input name="filepath" type="text" id="filepath" value="{$TEMPLATE.music_path}" class="input">
                                </span>							 
								 </td>
                                <td width="92"><span class="footerlink"><span class="smalltext"> </span></span></td>
                                <td width="49"><span class="footerlink"></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td valign="top"><span class="smalltext">Alignment:</span></td>
                                <td>&nbsp;</td>
                                <td valign="top">
								<span class="smalltext">
                                  <select name="loop_music" id="loop_music" class="input">
                                    <option value="Y" {if $TEMPLATE.music_loop=='Y'} selected{/if}>yes, loop this music</option>
                                    <option value="N" {if $TEMPLATE.music_loop=='N'} selected{/if}>no, play it just once</option>
                                  </select> 
                                </span>								
								</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                          </table>							
						  </td>
                          </tr>
                          <tr>
                            <td align="left" valign="top">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td>
		
		  </td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr class="naGrid1">
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top">
				<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><span class="smalltext"><span class="style11">Search Bar and Trim</span></span></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>
					  <select name="view" id="view" onchange="displaySearchbar(this.value);" class="input">
						<option value="N" {if $TEMPLATE.search_view=='N'} selected{/if}>Hide Search Bar</option>
						<option value="Y" {if $TEMPLATE.search_view=='Y'} selected{/if}>Show Search Bar</option>
					 </select>
					 </td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="392">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="left" valign="top">
							{if $TEMPLATE.view=='Y'}
								<div id="showSearch" style="display:inline">
							{else}
								<div id="showSearch" style="display:none">
							{/if} 
						  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="26%"><span class="smalltext">Border:</span></td>
                                <td>
									<span class="smalltext">
									  {colorPicker name="searchbrd_clr" formName="document.blogFrm" value=$TEMPLATE.search_bordercolor}{/colorPicker}
									</span>								</td>
                                <td width="5%"><span class="smalltext"> </span></td>
                                <td width="30%"><span class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td><span class="smalltext">Active</span></td>
                                <td>
								<span class="smalltext">
                                  {colorPicker name="searchintr_bgclr" formName="document.blogFrm" value=$TEMPLATE.search_interiorcolor}{/colorPicker}
                                 </span>								 </td>
                                <td><span class="smalltext"> </span></td>
                                <td><span class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td><span class="smalltext">Visited</span></td>
                                <td>
								<span class="smalltext">
                                  {colorPicker name="trim_clr" formName="document.blogFrm" value=$TEMPLATE.search_trimcolor}{/colorPicker}
                                 </span>								 </td>
                                <td><span class="smalltext"> </span></td>
                                <td><span class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                          </table>
						 </div> 
							{if $SEARCHBAR.view=='N'}
							<div id="hideSearch" style="display:inline">
							{else}
								<div id="hideSearch" style="display:none">
							{/if}	
						  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="26%"><span class="smalltext">Trim:</span></td>
                                <td>									
									  {colorPicker name="hidetrim_clr" formName="document.blogFrm" value=$TEMPLATE.search_trimcolor}{/colorPicker}
								</td>
                                <td width="5%"><span class="smalltext"> </span></td>
                                <td width="30%"><span class="smalltext"><span class="footerlink"><a href="#" class="footerlink">transparent</a></span></span></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                          </table>
						  	</div>					
						</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td width="1%">&nbsp;</td>
        </tr>
        <tr>
          <td width="35%">&nbsp;</td>
          <td width="50%">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center">
			<input name="submit" type=submit class="btnBg" value="Submit" onClick="unsetParam()" >
			<input name="act" type=submit id="Submit" value="preview" class="btnBg" onClick="setParam()" >  
			<input name="reset" type=reset class="btnBg" value="  Reset ">		
		 </td>
		 <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
    </table>
	</form>	
	</td>
  </tr>
</table>

