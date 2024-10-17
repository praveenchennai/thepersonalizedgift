<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields=new Array('camp_id','plan_name','duration','plan_price');
		var msgs=new Array('Campaign','Plan Name','Duration','Plan Price');
	</script>
	<script language="javascript">
	{literal}
	function loadFile(){
			 document.admFrm.submit();
			 return true;
		 }
	 {/literal}
	</script>
	
<table width=84% border=0 align="center" cellpadding=5 cellspacing=1> 
  <form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk()">    
   <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center" >
        <tr>
          <td nowrap class="naH1">Plans</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=banner pg=banner_plans}act=list{/makeLink}">Plan List</a></td>
        </tr>
      </table>
	</td>
    </tr>
	
    <tr>
      <td height="378" colspan=3 valign=top>
	  <table width="100%"  border="0" bgcolor="#EEEEEE" class="border">
	  {if isset($MESSAGE)}
        <tr>
    	<td colspan=4 valign=top>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=4><span class="group_style"><strong>Plan Details </strong></span></td> 
    </tr> 
    <tr>
      <td   valign=middle class="smalltext"> <div align="right">Campaigns</div></td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"> 
		<select name=camp_id>
			<option value="">-- SELECT A CAMPAIGNS --</option>
			{html_options values=$SECTION_LIST.id output=$SECTION_LIST.camp_name  selected=$smarty.request.camp_id}
		</select>
	</td>
    </tr>
    <tr> 
      <td  align="right" valign=middle class="smalltext">Plan Name</td> 
      <td width=3 align="center" valign=middle class="smalltext">:</td> 
      <td colspan="2" class="smalltext"><input type="text" name="plan_name" value="{$smarty.request.plan_name}" class="formText" size="30" maxlength="25" > </td> 
    </tr> 
    <tr>
      <td align="right" valign=middle class="smalltext">Description</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><textarea name="plan_desc" cols="30" rows="4" class="formText">{$smarty.request.plan_desc}</textarea></td>
    </tr>
    <tr>
      <td align="right" valign=middle class="smalltext">File Types</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"> JPEG 	  
		<input type="checkbox" name="jpg" value="jpg" {if ($smarty.request.jpg=='jpg'|| $FILE_TYPES[0]=='jpg')} checked {/if}>
		GIF
		<input type="checkbox" name="gif" value="gif" {if ($smarty.request.gif=='gif'|| $FILE_TYPES[1]=='gif')} checked {/if}>
		SWF
		<input type="checkbox" name="swf" value="swf" {if ($smarty.request.swf=='swf'|| $file_types[2]=='swf')} checked {/if}>
		PNG
		<input type="checkbox" name="png" value="png" {if ($smarty.request.png=='png'|| $FILE_TYPES[3]=='png')} checked {/if}>
		<br>Animated GIF 
		<input type="checkbox" name="agif" value="agif" {if ($smarty.request.agif=='agif'|| $FILE_TYPES[4]=='agif')} checked {/if}>
		TEXT
		<input type="checkbox" name="txt" value="txt" {if ($smarty.request.txt=='txt'|| $FILE_TYPES[5]=='txt')} checked {/if}>
		HTML
	  <input type="checkbox" name="html" value="html" {if ($smarty.request.html=='html'|| $FILE_TYPES[6]=='html')} checked {/if}>
	  FLASH
	  <input type="checkbox" name="flv" value="flv" {if ($smarty.request.flv=='flv'|| $FILE_TYPES[7]=='flv')} checked {/if}>
	  </td>
    </tr>
    <tr>
      <td align="right" valign=middle class="smalltext">Duration</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext">
		<input name="duration" type="text" id="duration" size="4" value="{$smarty.request.duration}">
		 <select name="duration_type">		
		   <option value="D"{if $smarty.request.duration_type=='D'}selected{/if}>Day</option>
		   <option value="M"{if $smarty.request.duration_type=='M'}selected{/if}>Month</option>
         </select>
		</td>
    </tr>
    <tr> 
      <td align="right" valign=middle class="smalltext">Plan Price</td> 
      <td width=3 align="center" valign=middle class="smalltext">:</td> 
      <td colspan="2"><input name="plan_price" type="text" size="10" value="{$smarty.request.plan_price}"></td> 
    </tr>
    <tr>
      <td height="33" align="left" valign=middle class="smalltext" width="225"><span class="group_style"><strong>For Demo Ads </strong></span> </td>
      <td align="center" valign=middle class="smalltext">&nbsp;</td>
      <td colspan="2" class="smalltext">&nbsp;</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">dmo File Type </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext">
	   <select name=dmo_filetype onChange="return loadFile()">
			<option value="">-- SELECT A FILE TYPE --</option>			
				{html_options values=$FILE_LIST.type_val output=$FILE_LIST.type_display selected=$smarty.request.dmo_filetype}
		</select>
		</td>
    </tr>
	{if ($smarty.request.dmo_filetype=='txt' || $smarty.request.dmo_filetype=='html')}
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Content</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><textarea name="dmo_content" cols="30" class="formText">{if $smarty.request.dmo_content!=""}{$smarty.request.dmo_content}{else}{$DEMOCONTENT}{/if}</textarea></td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Bg Color</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><p>{colorPicker name="dmo_bg" 
        formName="document.admFrm" value=$smarty.request.dmo_bg}{/colorPicker}</p>        </td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Border Size</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><input name="dmo_border" type="text" class="formText" id="dmo_border" value="{$smarty.request.dmo_border}" size="2" maxlength="2"></td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Border Color</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext">{colorPicker name="dmo_borderclr" formName="document.admFrm" value=$smarty.request.dmo_borderclr}{/colorPicker}</td>
    </tr>
    <tr>
      <td width="40%" height="33" align="right" valign=middle class="smalltext">Text Font</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><select name=dmo_font style="margin-top: 2px;width:175" class="input">
        <option value="">-- SELECT A FONT --</option>                
			{html_options values=$FONT_LIST.font_name output=$FONT_LIST.font_name  selected=$smarty.request.dmo_font}		
     </select>
	  </td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Font Color</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext">{colorPicker name="dmo_fontclr" formName="document.admFrm" value=$smarty.request.dmo_fontclr}{/colorPicker}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Font Size</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><select name="dmo_fontsize" id="dmo_fontsize" style="margin-top: 2px;" class="input">
        <option value="+1" {if $smarty.request.dmo_fontsize =="+1"} selected{/if}>+1 </option>
        <option value="+2" {if $smarty.request.dmo_fontsize =="+2"} selected{/if}>+2</option>
        <option value="+3" {if $smarty.request.dmo_fontsize =="+3"} selected{/if}>+3 </option>
        <option value="+4" {if $smarty.request.dmo_fontsize =="+4"} selected{/if}>+4</option>
        <option value="+5" {if $smarty.request.dmo_fontsize =="+5"} selected{/if}>+5</option>
        <option value="+6" {if $smarty.request.dmo_fontsize =="+6"} selected{/if}>+6</option>
        <option value="-1" {if $smarty.request.dmo_fontsize =="-1"} selected{/if}>-1 </option>
        <option value="-2" {if $smarty.request.dmo_fontsize =="-2"} selected{/if}>-2</option>
        <option value="-3" {if $smarty.request.dmo_fontsize =="-3"} selected{/if}>-3 </option>
        <option value="-4" {if $smarty.request.dmo_fontsize =="-4"} selected{/if}>-4</option>
        <option value="-5" {if $smarty.request.dmo_fontsize =="-5"} selected{/if}>-5</option>
        <option value="-6" {if $smarty.request.dmo_fontsize =="-6"} selected{/if}>-6</option>
        <option value="1" {if $smarty.request.dmo_fontsize =="1"} selected{/if}>1 </option>
        <option value="2" {if $smarty.request.dmo_fontsize =="2"} selected{/if}>2</option>
        <option value="3" {if $smarty.request.dmo_fontsize =="3"} selected{/if}>3 </option>
        <option value="4" {if $smarty.request.dmo_fontsize =="4"} selected{/if}>4</option>
        <option value="5" {if $smarty.request.dmo_fontsize =="5"} selected{/if}>5</option>
        <option value="6" {if $smarty.request.dmo_fontsize =="6"} selected{/if}>6</option>
        <option value="7" {if $smarty.request.dmo_fontsize =="7"} selected{/if}>7</option>
      </select></td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Link Color</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td width="324" class="smalltext">{colorPicker name="dmo_linkcolor" formName="document.admFrm" value=$smarty.request.dmo_linkcolor}{/colorPicker}</td>
      <td width="68" class="smalltext"><input type="checkbox" name="dmo_uline" value="Y" {if $smarty.request.dmo_uline =='Y'} checked{/if}>
Underline</td>
    </tr>
{else}
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">dmo file </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext">
	  	<input name="image" type="file" size="20" class="input">
        <input name="content" type="hidden" class="formText" id="content" value="{$smarty.request.plan_example}" size="30" maxlength="25" >
	  </td>
    </tr>
{/if}
	<tr>
      <td height="33" align="right" valign=middle class="smalltext">URL </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" class="smalltext"><input type="text" name="dmo_url" value="{$smarty.request.dmo_url}" class="formText" size="30" maxlength="25" >
	  </td>
    </tr>
    <tr align="center"> 
      <td height="56" colspan=4 valign=center class="smalltext"> 
          <input name="btn_save" type=submit  class="input" id="btn_save" value="Submit">&nbsp; 
          <input type=reset value="Reset"  class="input">	  
	  </td> 
    </tr> 
      </table>	  
	  </td>
    </tr>
  </form> 
</table>
