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
 var mywindow=window.open ("http://192.168.1.254/industrypage/robin",
"mywindow","menubar=0,toolbar=0,height="+(screen.height-100)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
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
function setWin1(myvindow)
{
//alert(myvindow);
 window.open (myvindow,"myvindow","menubar=0,toolbar=0,height="+(screen.height-100)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
}

</script>
{/literal}
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle">
    <td width="62%" height="39" class="blackboldtext"> The Industry Page <strong>Look &amp; Feel </strong> </td>
    <td width="33%" align="right" valign="middle" class="blackboldtext"><span class="smalltext"><strong>save your changes</strong></span></td>
  </tr>
  <tr>
    <td height="23" colspan="2" align="left" valign="middle" class="blacktext"><span class="smalltext"><strong class="greyboldtext style1">Page Width &amp; Alignment </strong></span><span class="smalltext"> </span></td>
  </tr>
  <tr>
    <td height="19" colspan="2" align="center" valign="top" class="blacktext"><div align="left"></div></td>
  </tr>
  <tr>
    <td height="244" colspan="2" align="center" valign="top" class="blacktext">	
	<form action="" method="POST"  name="blogFrm" style="margin: 0px;" enctype="multipart/form-data">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2"><table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="136" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="5">&nbsp;</td>
                          </tr>
                          <tr>
                            <td width="103" align="left"><span class="style9">Width: </span></td>
                            <td width="4">&nbsp;</td>
                            <td colspan="2" align="left">
							 <input name="blog_id" type="hidden" id="blog_id" value="{$BLOG_ID}">
							<span class="smalltext">
                              <input name="page_width" type="text" id="page_width" value="{$WIDTH_VAL}" class="input" onKeyUp="checkWidth(this.value)">
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
								<option value="left" {if $PAGEWIDTH.page_align=='left'} selected{/if}>left</option>
								<option value="center" {if $PAGEWIDTH.page_align=='center'} selected{/if}>center</option>
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
										<span class="smalltext"><input name="page_type" type="radio" value="P" {if $PAGEWIDTH.page_type=='P'} checked{/if}> </span>
                                       </td>
                                        <td width="21%"><span class="smalltext"><span class="style9">Percent</span></span></td>
                                        <td width="7%">
										<span class="smalltext"><input name="page_type" type="radio" value="X" {if $PAGEWIDTH.page_type=='X'} checked{/if}></span>
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
						  
							<div id="ShowError" style="display:{if $PAGEWIDTH.page_width>800}inline{else}none{/if}">	
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
          <td width="3%">&nbsp;</td>
          <td width="33%" align="left" valign="top">
		  <!--table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="{$GLOBAL.mod_url}/images/page.jpg" border="0"></td>
              </tr>
          </table-->
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
		 <!--
        <tr align="left">
          <td colspan="4"><span class="smalltext"><strong class="greyboldtext style1">Header</strong></span><span class="smalltext"></span></td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top" bgcolor="#EEEEEE">
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
                             <option value="0" {if $PAGEHEADER.header_type==0 } selected{/if}>Hide Header</option>
                             <option  value="1" {if $PAGEHEADER.header_type ==1 } selected{/if}>Create a Custom Header</option>
                             <option value="2" {if $PAGEHEADER.header_type==2 } selected{/if}>Input your own Header HTML</option>
                         </select> 
						 </td>
                         </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp; </td>
                         </tr>
                       <tr>
                         <td colspan="2" class="smalltext">
						{if $PAGEHEADER.header_type==1}  
							<div id="customHeader" style="display:inline">
						{else}
							<div id="customHeader" style="display:none">
						{/if}
						 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                             <tr>
								 <td width="16%" class="smalltext">Site Name:</td>
								 <td width="1%">&nbsp;</td>
								 <td width="30%" class="smalltext" ><input name="site_txt" type="text" id="site_txt" value="{$PAGEHEADER.site_txt}" class="input"></td>
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
                         <td class="smalltext" >{colorPicker name="stxt_clr" formName="document.blogFrm" value=$PAGEHEADER.stxt_clr}{/colorPicker} </td>
                         <td>&nbsp;</td>
                         <td align="left" class="smalltext"></td>
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
                           {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$PAGEHEADER.stxt_font}
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
                         <td class="smalltext" ><select name="stxt_fontsize" id="stxt_fontsize" style="margin-top: 2px;" class="input">
                           <option value="8" {if $PAGEHEADER.stxt_fontsize==8} selected{/if}>1 (smallest)</option>
                           <option value="10" {if $PAGEHEADER.stxt_fontsize==10} selected{/if}>2</option>
                           <option value="12" {if $PAGEHEADER.stxt_fontsize==12} selected{/if}>3 (standard)</option>
                           <option value="14" {if $PAGEHEADER.stxt_fontsize==14} selected{/if}>4</option>
                           <option value="18" {if $PAGEHEADER.stxt_fontsize==18} selected{/if}>5</option>
                           <option value="24" {if $PAGEHEADER.stxt_fontsize==24} selected{/if}>6</option>
                           <option value="36" {if $PAGEHEADER.stxt_fontsize==36} selected{/if}>7 (largest)</option>
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
                         <td class="smalltext" >{colorPicker name="border_clr" formName="document.blogFrm" value=$PAGEHEADER.border_clr}{/colorPicker}</td>
                         <td>&nbsp;</td>
                         <td align="left" class="smalltext"></td>
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
                         <td class="smalltext" >{colorPicker name="inter_clr" formName="document.blogFrm" value=$PAGEHEADER.inter_clr}{/colorPicker}</td>
                         <td>&nbsp;</td>
                         <td align="left" class="smalltext"></td>
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
                         <td class="smalltext" ><input name="tag_txt" type="text" id="tag_txt" value="{$PAGEHEADER.tag_txt}" class="input"></td>
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
                         <td class="smalltext" >{colorPicker name="tag_clr" formName="document.blogFrm" value=$PAGEHEADER.tag_clr}{/colorPicker}</td>
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
                           {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$PAGEHEADER.tag_font}
												
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
                         <td class="smalltext" ><select name="tag_fontsize" id="tag_fontsize" style="margin-top: 2px;" class="input">
                           <option value="8" {if $PAGEHEADER.tag_fontsize==8} selected{/if}>1 (smallest)</option>
                           <option value="10" {if $PAGEHEADER.tag_fontsize==10} selected{/if}>2</option>
                           <option value="12" {if $PAGEHEADER.tag_fontsize==12} selected{/if}>3 (standard)</option>
                           <option value="14" {if $PAGEHEADER.tag_fontsize==14} selected{/if}>4</option>
                           <option value="18" {if $PAGEHEADER.tag_fontsize==18} selected{/if}>5</option>
                           <option value="24" {if $PAGEHEADER.tag_fontsize==24} selected{/if}>6</option>
                           <option value="36" {if $PAGEHEADER.tag_fontsize==36} selected{/if}>7 (largest)</option>
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
                       </table>
					   </div>
					    {if $PAGEHEADER.header_type==0}
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
						 {if $PAGEHEADER.header_type==2}
								<div id="inputHeader" style="display:inline">
						{else}
								<div id="inputHeader" style="display:none">
						{/if}	
							   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
								 <tr>
								   <td width="26%">Module:</td>
								   <td width="74%"> <textarea name="own_header" cols="45" rows="10" class="input">{$PAGEHEADER.own_header}</textarea></td>
								 </tr>
							   </table>
						  	 </div>
						   </td>
                         </tr>
                       <tr>
                         <td class="smalltext">&nbsp;</td>
                         <td>&nbsp;</td>
                         </tr>
                     </table>					 </td>
                  </tr>
                </table>				</td>
              </tr>
          </table></td>
          <td>&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>		  	  
        </tr>
		-->
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr align="left">
          <td height="22" colspan="4"><span class="smalltext"><strong class="greyboldtext style1">Background</strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top" bgcolor="#EEEEEE"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
                                <td>{colorPicker name="bgcolor" formName="document.blogFrm" value=$PAGEBACKGROUND.bgcolor}{/colorPicker}</td>
                                <td>&nbsp;</td>
                                <td width="30%" class="footerlink">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp; </td>
                                <td width="4%">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">Pic: </td>
                                <td>&nbsp;</td>
                                <td class="smalltext">
                                  <input type="file" name="image" class="input" >
                                </td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
							  {if $PAGEBACKGROUND.picture}
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext"><img src="{$GLOBAL.mod_url}/images/template/{$PAGEBACKGROUND.picture}" border="0" height="75" width="75"> 
								<input name="picture" type="hidden" id="picture"  value="{$PAGEBACKGROUND.picture}"></td>
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
                                    <option value="top left" {if $PAGEBACKGROUND.position=='top left'} selected{/if}>top left</option>
                                    <option value="top center"{if $PAGEBACKGROUND.position=='top center'} selected{/if}>top center</option>
                                    <option value="top right" {if $PAGEBACKGROUND.position=='top right'} selected{/if}>top right</option>
                                    <option value="center left"{if $PAGEBACKGROUND.position=='center left'} selected{/if}>center left</option>
                                    <option value="center center"{if $PAGEBACKGROUND.position=='center center'} selected{/if}>centered</option>
                                    <option value="center right" {if $PAGEBACKGROUND.position=='center right'} selected{/if}>center right</option>
                                    <option value="bottom left" {if $PAGEBACKGROUND.position=='bottom left'} selected{/if}>bottom left</option>
                                    <option value="bottom center"{if $PAGEBACKGROUND.position=='bottom center'} selected{/if}>bottom center</option>
                                    <option value="bottom right" {if $PAGEBACKGROUND.position=='bottom right'} selected{/if}>bottom right</option>
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
                                    <option value="repeat" {if $PAGEBACKGROUND.repeat=='repeat'} selected{/if}>repeat in all directions</option>
                                    <option value="repeat-x"{if $PAGEBACKGROUND.repeat=='repeat-x'} selected{/if}>repeat horizontally</option>
                                    <option value="repeat-y"{if $PAGEBACKGROUND.repeat=='repeat-y'} selected{/if}>repeat vertically</option>
                                    <option value="no-repeat"{if $PAGEBACKGROUND.repeat=='no-repeat'} selected{/if}>don't repeat</option>
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
                                    <option value="scroll" {if $PAGEBACKGROUND.scroll=='scroll'} selected{/if}>scroll with page</option>
                                    <option value="fixed"  {if $PAGEBACKGROUND.scroll=='fixed'} selected{/if}>keep it fixed</option>
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
          <td>&nbsp;</td>
          <td align="left" valign="top">
		  <!--table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="{$GLOBAL.mod_url}/images/page.jpg" border="0"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="style9">Note: You have not selected a background image. Note: Your background image will appear on your site, provided the the link to your online background file is working. </td>
              </tr>
          </table-->
		  </td>
        </tr>
		<tr align="left">
		  <td height="22" colspan="4">&nbsp;</td>
		  </tr>
		<tr align="left">
          <td height="22" colspan="4"><span class="smalltext"><strong class="greyboldtext style1">Main Body </strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="813" height="169" align="left" valign="top" bgcolor="#EEEEEE"><table width="111%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="left" valign="top"><table width="110%"  border="0" cellpadding="0" cellspacing="0"  class="smalltext">
                              <tr>
                                <td width="29%" height="23"class="smalltext" >&nbsp;</td>
                                <td width="1%">&nbsp;</td>
                                <td width="68%">&nbsp;</td>
                                <td width="1%">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="23"class="smalltext" >Main Body Color:</td>
                                <td width="1%">&nbsp;</td>
                                <td>{colorPicker name="mbcolor" formName="document.blogFrm" value=$MBODY.mbcolor}{/colorPicker}</td>
                                <td>&nbsp;</td>
                                <td width="1%" class="footerlink">&nbsp;</td>
                              </tr>
							      <tr>
                                <td height="23"class="smalltext" >Section box Color:</td>
                                <td width="1%">&nbsp;</td>
                                <td>{colorPicker name="sbcolor" formName="document.blogFrm" value=$MBODY.sbcolor}{/colorPicker}</td>
                                <td>&nbsp;</td>
                                <td width="1%" class="footerlink">&nbsp;</td>
                              </tr>
                                  <tr>
                                    <td  class="smalltext">Section box transperent:</td>
                                    <td>&nbsp;</td>
                                    <td><input name="transperant" type="checkbox" id="transperant" value="Y" {if $MBODY.transperant=='Y'} checked{/if}></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                <td align="right">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp; </td>
                                <td width="1%">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">Header (username display) </td>
                                <td>&nbsp;</td>
                                <td class="smalltext" ><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="42%">
                                      <select name=hfont class="input">
                                        <option value="">-- SELECT FONT --</option>
                                        
                                    {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$MBODY.txtfont}			
                              		
                                      </select>
                                    </td>
                                    <td width="58%">
                                      <select name="bfontsize" id="bfontsize" style="margin-top: 2px;" class="input">
                                        <option value="8" {if $MBODY.txtfontsize==8} selected{/if}>1 (smallest)</option>
                                        <option value="10" {if $MBODY.txtfontsize==10} selected{/if}>2</option>
                                        <option value="12" {if $MBODY.txtfontsize==12} selected{/if}>3 (standard)</option>
                                        <option value="14" {if $MBODY.txtfontsize==14} selected{/if}>4</option>
                                        <option value="18" {if $MBODY.txtfontsize==18} selected{/if}>5</option>
                                        <option value="24" {if $MBODY.txtfontsize==24} selected{/if}>6</option>
                                        <option value="36" {if $MBODY.txtfontsize==36} selected{/if}>7 (largest)</option>
                                      </select>
                                    </td>
                                  </tr>
                                </table></td>
                                <td>&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="smalltext">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="smalltext" >{colorPicker name="headercolor" formName="document.blogFrm" value=$MBODY.color}{/colorPicker}</td>
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
          <td>&nbsp;</td>
          <td align="left" valign="top">
		  <!--table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="{$GLOBAL.mod_url}/images/page.jpg" border="0"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="style9">Note: You have not selected a background image. Note: Your background image will appear on your site, provided the the link to your online background file is working. </td>
              </tr>
          </table-->
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr align="left">
          <td colspan="4"><span class="smalltext"><strong class="greyboldtext style1">Text &amp; Links</strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                                    {html_options values=$SECTION_LIST.font_name output=$SECTION_LIST.font_name selected=$TEXTLINK.txtfont}			
                              		</select>
                                </td>
                                <td width="42%">								
                                  <select name="txtfontsize" id="txtfontsize" style="margin-top: 2px;" class="input">
                                    <option value="8" {if $TEXTLINK.txtfontsize==8} selected{/if}>1 (smallest)</option>
                                    <option value="10" {if $TEXTLINK.txtfontsize==10} selected{/if}>2</option>
                                    <option value="12" {if $TEXTLINK.txtfontsize==12} selected{/if}>3 (standard)</option>
                                    <option value="14" {if $TEXTLINK.txtfontsize==14} selected{/if}>4</option>
                                    <option value="18" {if $TEXTLINK.txtfontsize==18} selected{/if}>5</option>
                                    <option value="24" {if $TEXTLINK.txtfontsize==24} selected{/if}>6</option>
                                    <option value="36" {if $TEXTLINK.txtfontsize==36} selected{/if}>7 (largest)</option>
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
                          <td class="smalltext">{colorPicker name="txtclr" formName="document.blogFrm" value=$TEXTLINK.txtclr}{/colorPicker}</td>
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
                          <td class="smalltext" >{colorPicker name="nrm_clr" formName="document.blogFrm" value=$TEXTLINK.nrm_clr}{/colorPicker}</td>
                          <td><input name="nrm_uline" type="checkbox" id="nrm_uline" value="Y" {if $TEXTLINK.nrm_uline=='Y'} checked{/if}></td>
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
                          <td class="smalltext" >{colorPicker name="act_clr" formName="document.blogFrm" value=$TEXTLINK.act_clr}{/colorPicker}</td>
                          <td><input name="act_uline" type="checkbox" id="act_uline" value="Y" {if $TEXTLINK.act_uline=='Y'} checked{/if}></td>
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
                          <td class="smalltext" >{colorPicker name="visit_clr" formName="document.blogFrm" value=$TEXTLINK.visit_clr}{/colorPicker}</td>
                          <td><input name="visit_uline" type="checkbox" id="visit_uline" value="Y" {if $TEXTLINK.visit_uline=='Y'} checked{/if}></td>
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
                          <td class="smalltext" >{colorPicker name="hover_clr" formName="document.blogFrm" value=$TEXTLINK.hover_clr}{/colorPicker}</td>
                          <td><input name="hover_uline" type="checkbox" id="hover_uline" value="Y" {if $TEXTLINK.hover_uline=='Y'} checked{/if}></td>
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
          <td>&nbsp;</td>
          <td align="left" valign="top">
		  
		  <!--table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="136" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="250">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="18" align="left" valign="middle"><span class="style9">Note: You have not selected a background image. Note: Your background image will appear on your site, provided the the link to your online background file is working. </span></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table-->
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr align="left">
          <td colspan="4"><span class="smalltext"><strong class="greyboldtext style1">Blog Left module</strong></span></td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="136" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
										{colorPicker name="brd_clr" formName="document.blogFrm" value=$LEFTMODULE.brd_clr}{/colorPicker}
									  </span>
								  </td>
                                  <td width="30%"><span class="footerlink"><span class="smalltext"></span></span></td>
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
                                  <td><span class="smalltext">
</span>
                                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="45%" valign="top" class="smalltext">
                                          <select name="brd_style" id="brd_style" class="input">
                                            <option value="none"  {if $LEFTMODULE.brd_style=='none'} selected{/if}>none</option>
                                            <option value="solid" {if $LEFTMODULE.brd_style=='solid'} selected{/if}>solid</option>
                                            <option value="dotted"{if $LEFTMODULE.brd_style=='dotted'} selected{/if}>dotted</option>
                                            <option value="dashed"{if $LEFTMODULE.brd_style=='dashed'} selected{/if}>dashed</option>
                                            <option value="double"{if $LEFTMODULE.brd_style=='double'} selected{/if}>double</option>
                                            <option value="groove"{if $LEFTMODULE.brd_style=='groove'} selected{/if}>groove</option>
                                            <option value="ridge" {if $LEFTMODULE.brd_style=='ridge'} selected{/if}>ridge</option>
                                            <option value="inset" {if $LEFTMODULE.brd_style=='inset'} selected{/if}>inset</option>
                                            <option value="outset"{if $LEFTMODULE.brd_style=='outset'} selected{/if}>outset</option>
                                          </select>
                                       </td>
                                        <td width="55%"><input name="brd_pixel" type="text" id="brd_pixel" size="1" value="{$LEFTMODULE.brd_pixel}" class="input"></td>
                                      </tr>
                                    </table>
                                    <span class="smalltext">                                  </span></td>
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
          <td>&nbsp;</td>
          <td>
		  <!--table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="136" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="250">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="18" align="left" valign="middle"><span class="style9">Note: You have not selected a background image. Note: Your background image will appear on your site, provided the the link to your online background file is working. </span></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table-->
		  </td>
        </tr>
        <tr>
          <td height="18" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="131" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                            <td align="right">							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="17%"><p class="smalltext">Background:</p></td>
                                <td width="5%"><span class="smalltext"> </span></td>
                                <td width="35%"> <span class="smalltext">{colorPicker name="title_bgclr" formName="document.blogFrm" value=$LEFTMODULE.title_bgclr}{/colorPicker} </span> </td>
                                <td width="30%"><span class="footerlink"><span class="smalltext"></span></span></td>
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
                                <td><span class="smalltext"> </span> <span class="smalltext">{colorPicker name="title_txt_clr" formName="document.blogFrm" value=$LEFTMODULE.title_txt_clr}{/colorPicker} </span></td>
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
                                    <option value="left" {if $LEFTMODULE.title_align=='left'} selected{/if}>Left</option>
                                    <option value="center" {if $LEFTMODULE.title_align=='center'} selected{/if}>Center</option>
                                    <option value="right" {if $LEFTMODULE.title_align=='right'} selected{/if}>Right</option>
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
          <td>&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="131" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                                <td width="35%" class="smalltext"> {colorPicker name="intr_bgclr" formName="document.blogFrm" value=$LEFTMODULE.intr_bgclr}{/colorPicker} </span> </td>
                                <td width="30%"><span class="footerlink"><span class="smalltext"></span></span></td>
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
                                <td class="smalltext">{colorPicker name="intr_txt_clr" formName="document.blogFrm" value=$LEFTMODULE.intr_txt_clr}{/colorPicker}</td>
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
                                <td class="smalltext"><select name="intr_align" id="intr_align" class="input">
                                  <option value="left"  {if $LEFTMODULE.intr_align=='left'} selected{/if}>Left</option>
                                  <option value="center"{if $LEFTMODULE.intr_align=='center'} selected{/if}>Center</option>
                                  <option value="right" {if $LEFTMODULE.intr_align=='right'} selected{/if}>Right</option>
                                </select></td>
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
          <td>&nbsp;</td>
        </tr>
		<!--
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="131" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                                  <input name="filepath" type="text" id="filepath" value="{$MUSIC.filepath}" class="input">
                                </span>							  </td>
                                <td width="92"><span class="footerlink"><span class="smalltext"><a href="#" class="footerlink">test music </a> </span></span></td>
                                <td width="49"><span class="footerlink"><span class="smalltext"><a href="#" class="footerlink">clear</a></span></span></td>
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
                                    <option value="Y" {if $MUSIC.loop_music=='Y'} selected{/if}>yes, loop this music</option>
                                    <option value="N" {if $MUSIC.loop_music=='N'} selected{/if}>no, play it just once</option>
                                  </select> 
                                </span>								</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                          </table>							</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          <td>&nbsp;</td>
          <td>
		  <!--table width="100%" height="138"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="136" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="250">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="18" align="left" valign="middle"><span class="style9">Note: You have not selected a background image. </span></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table>
		  </td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td width="500" height="169" align="left" valign="top" bgcolor="#EEEEEE">
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
						<option value="N" {if $SEARCHBAR.view=='N'} selected{/if}>Hide Search Bar</option>
						<option value="Y" {if $SEARCHBAR.view=='Y'} selected{/if}>Show Search Bar</option>
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
						{if $SEARCHBAR.view=='Y'}
								<div id="showSearch" style="display:inline">
							{else}
								<div id="showSearch" style="display:none">
							{/if} 
						  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr align="left" valign="middle">
                                <td width="26%"><span class="smalltext">Border:</span></td>
                                <td>
									<span class="smalltext">
									  {colorPicker name="searchbrd_clr" formName="document.blogFrm" value=$SEARCHBAR.brd_clr}{/colorPicker}
									</span>								</td>
                                <td width="5%"><span class="smalltext"> </span></td>
                                <td width="30%"></td>
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
                                  {colorPicker name="searchintr_bgclr" formName="document.blogFrm" value=$SEARCHBAR.intr_bgclr}{/colorPicker}
                                 </span>								 </td>
                                <td><span class="smalltext"> </span></td>
                                <td><span class="smalltext"></td>
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
                                  {colorPicker name="trim_clr" formName="document.blogFrm" value=$SEARCHBAR.trim_clr}{/colorPicker}
                                 </span>								 </td>
                                <td><span class="smalltext"> </span></td>
                                <td></td>
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
									  {colorPicker name="hidetrim_clr" formName="document.blogFrm" value=$SEARCHBAR.trim_clr}{/colorPicker}
								</td>
                                <td width="5%"><span class="smalltext"> </span></td>
                                <td width="30%"></td>
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
          <td>&nbsp;</td>
          <td width="33%">&nbsp;</td>
        </tr>
		
		-->
		
        <tr>
          <td width="41%">&nbsp;</td>
          <td width="23%">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center">            <input name="submit" type=submit class="btnBg" value="Submit" onClick="unsetParam()" >
             <!-- <input name="act" type=submit id="Submit" value="preview" class="btnBg" onClick="setParam()" >  -->
			 <input name="act" type=button  class="btnBg" value="preview" onClick="setWin1('{$smarty.const.SITE_URL}/{$USERNAME}')" > 
              <input name="reset" type=reset class="btnBg" value="  Reset "></td><td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
    </table>
	</form>	
	</td>
  </tr>
</table>

