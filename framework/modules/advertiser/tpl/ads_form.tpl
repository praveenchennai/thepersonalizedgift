<script language="javascript">
	{literal}
	function validateExtension(){
		
		var extension;
		var content;
		//var ext=document.admFrm.file_type.value;	
		var ext=document.admFrm.file_type.options[document.admFrm.file_type.selectedIndex].value;	
			if(ext=="jpeg"){
				 extension="jpg";
			}else if(ext=="agif"){
				extension="gif";
			}else{
				extension=ext;
			}	
			var val=document.admFrm.image.value;
			var hidcont=document.admFrm.content.value;
			var company_name=document.admFrm.company_name.value;
			var len=val.length;		
			if(len>0){				
				content=val;	
			}else{
				var len2=hidcont.length;
				var str=hidcont.substring(len2-15,len2);	
				content=str;
			}
				if(ext=="")
				{
					alert ("Please select the file type");
					return false;
				}
				if(company_name=="")
				{
					alert ("Please specify the title");
					return false;
				}
				
				var val1=content.indexOf('.');
				if(content.indexOf('.')==-1)
				{
					alert ("Select the file with the type  as same as type selected in file Type");
					return false;
				}
				var subVal=content.substring(val1+1);	
				if(subVal!=extension && subVal!=""){
				alert ("Select the file with the type  as same as type selected in file Type");
				return false;
			}
	} 
  
	{/literal}
	</script>
	<script language="javascript">
	{literal}
		function planWin(plan_id){	
	{/literal}		
			 var mywindow=window.open ("{makeLink mod=banner pg=banner_plans}act=plan{/makeLink}&plan_id="+plan_id,
			"test","menubar=0,toolbar=0,height="+(screen.height-200)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
	  {literal} 
		 }
		 function loadFile(){
			 document.admFrm.submit();
			 return true;
		 }
	{/literal}
		
	</script><SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields	=	new Array('camp_id');
		var msgs	=	new Array('Campaign');	
	</script>	
  <form action="" method="POST" enctype="multipart/form-data" name="admFrm" onSubmit="return chk()">    
 <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
 <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1" align="left">&nbsp;&nbsp;&nbsp;&nbsp;Advertisement</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=advertiser pg=banner_ads}act=list{/makeLink}">List Ads</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  >
              <tr>
                <td ><table width="100%" height="24" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                          <td width="1%" class="naGridTitle">&nbsp;</td>
                          <td width="99%" class="naGridTitle">&nbsp;</td>
                        </tr>
                    </table></td>
              </tr>
              <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td align="center" valign="top"><table width="100%"  border="0" bgcolor="#EEEEEE" class="border" >
	  {if isset($MESSAGE)}
       <tr>
     <td colspan=4 valign=top align="center">		
      	<font color="#FF0000" class="smalltext"><b>{$MESSAGE}</b></font> 
      </td>
    </tr>
    {/if}
    <tr>
      <td width="40%" height="35" align="right" valign=middle class="smalltext">Campaign</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext">
	  <select name=camp_id {if ($CHKURL !='Y')} onChange="return loadFile()"{/if}>
        	{html_options values=$SELECTION_LIST.id output=$SELECTION_LIST.camp_name selected=$smarty.request.camp_id}
	  </select>
	  <input type="hidden" name="id" value="{$smarty.request.id}">	
	  <input type="hidden" name="admin_banner" value="{$ADMIN_USER}"> 
	  {if ($CHKURL =='Y')} 
	  <input name="plan_id" type="hidden"  value="2">
	   <input name="user_id" type="hidden"  value="2">
	  {/if}
	</td>
    </tr>
    <tr>
      <td height="32" align="right" valign=middle class="smalltext">File Type </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext">	  
	  <select name=file_type {if ($CHKURL !='Y')} onChange="return loadFile()" {/if}>
			<option value="">-- SELECT A FILE TYPE --</option>
			{if $smarty.request.id}	
			 	{html_options values=$FILE_TYPES.type_val output=$FILE_TYPES.type_display selected=$smarty.request.file_type}
			{else}
				{html_options values=$FILE_LIST.type_val output=$FILE_LIST.type_display selected=$smarty.request.file_type}
			{/if}
		</select>
	</td>
    </tr>
	{if ($CHKURL !='Y')}
    <tr> 
      <td width=40% height="37" align="right" valign=middle class="smalltext">Plan Name</td> 
      <td width=21 align="center" valign=middle class="smalltext">:</td> 
      <td colspan="2" align="left" class="smalltext">
	  {if $smarty.request.id}
	 	 {$smarty.request.plan_name}&nbsp;&nbsp;<a class="linkOneActive" href="#" onClick=" return planWin({$smarty.request.plan_id})">Preview</a>
		 	<input name="plan_id" type="hidden" class="formText" value="{$smarty.request.plan_id}">
	  {else}	  	
		  <select name=plan_id onChange="return loadFile()">
			<option value="">-- SELECT A PLAN --</option>        
				{html_options values=$PLAN_LIST.id output=$PLAN_LIST.plan_name selected=$smarty.request.plan_id}
		  </select> 
		  {if $smarty.request.plan_id}&nbsp;&nbsp;<a class="linkOneActive" href="#" onClick=" return planWin({$smarty.request.plan_id})">Preview</a>{/if}
	  {/if}	 
	  </td> 
    </tr>
	{/if} 
    <tr>
      <td height="30" align="right" valign=middle class="smalltext">Title</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" valign="top" class="smalltext"><input name="company_name" type="text" class="formText" value="{$smarty.request.company_name}" size="30"></td>
    </tr> 
	{if ($smarty.request.file_type=='txt' || $smarty.request.file_type=='html')} 
    <tr>
      <td align="right" valign=middle class="smalltext">Content</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext"><input name="content" type="text" class="formText" value="{$smarty.request.content}" size="30"> 
		</td>
    </tr>
    <tr> 
      <td width=40% align="right" valign=middle class="smalltext">Bg Color </td> 
      <td width=21 align="center" valign=middle class="smalltext">:</td> 
      <td colspan="2" align="left">{colorPicker name="bg_color" formName="document.admFrm" value=$smarty.request.bg_color}{/colorPicker}</td> 
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Border Size </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext"><input name="border" type="text" class="formText" value="{$smarty.request.border}" size="2" maxlength="2"></td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Border Color </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext">{colorPicker name="border_color" formName="document.admFrm" value=$smarty.request.border_color}{/colorPicker}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Text Font </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext">
	  <select name=text_font  style="margin-top: 2px;width:175" class="input">
        <option value="">-- SELECT A FONT --</option>        
			{html_options values=$FONT_LIST.font_name output=$FONT_LIST.font_name  selected=$smarty.request.text_font}		
      </select>
	  </td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Font Color </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext">{colorPicker name="text_color" formName="document.admFrm" value=$smarty.request.text_color}{/colorPicker}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Font Size </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext">
	  <select name="text_font_size" id="text_font_size" style="margin-top: 2px;" class="input">
		<option value="+1" {if $smarty.request.text_font_size=="+1"} selected{/if}>+1 </option>
		<option value="+2" {if $smarty.request.text_font_size=="+2"} selected{/if}>+2</option>
		<option value="+3" {if $smarty.request.text_font_size=="+3"} selected{/if}>+3 </option>
		<option value="+4" {if $smarty.request.text_font_size=="+4"} selected{/if}>+4</option>
		<option value="+5" {if $smarty.request.text_font_size=="+5"} selected{/if}>+5</option>
		<option value="+6" {if $smarty.request.text_font_size=="+6"} selected{/if}>+6</option>
		<option value="-1" {if $smarty.request.text_font_size=="-1"} selected{/if}>-1 </option>
		<option value="-2" {if $smarty.request.text_font_size=="-2"} selected{/if}>-2</option>
		<option value="-3" {if $smarty.request.text_font_size=="-3"} selected{/if}>-3 </option>
		<option value="-4" {if $smarty.request.text_font_size=="-4"} selected{/if}>-4</option>
		<option value="-5" {if $smarty.request.text_font_size=="-5"} selected{/if}>-5</option>
		<option value="-6" {if $smarty.request.text_font_size=="-6"} selected{/if}>-6</option>
		<option value="1" {if $smarty.request.text_font_size=="1"} selected{/if}>1 </option>
		<option value="2" {if $smarty.request.text_font_size=="2"} selected{/if}>2</option>
		<option value="3" {if $smarty.request.text_font_size=="3"} selected{/if}>3 </option>
		<option value="4" {if $smarty.request.text_font_size=="4"} selected{/if}>4</option>
		<option value="5" {if $smarty.request.text_font_size=="5"} selected{/if}>5</option>
		<option value="6" {if $smarty.request.text_font_size=="6"} selected{/if}>6</option>
		<option value="7" {if $smarty.request.text_font_size=="7"} selected{/if}>7</option>		
      </select>
	  </td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Link Color </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td width="278" align="left" class="smalltext">{colorPicker name="txtlink_nrl" formName="document.admFrm" value=$smarty.request.txtlink_nrl}{/colorPicker}</td>
      <td width="145" align="left" class="smalltext"><input type="checkbox" name="txtlink_nrl_uline" value="Y" {if $smarty.request.txtlink_nrl_uline=='Y'} checked{/if}>
        Underline</td>
    </tr>
	{else}
	 <tr>
      <td align="right" valign=middle class="smalltext">Content</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext"><input name="image" type="file" size="20" class="input"></td>
    </tr>
	 <tr align="center">
	   <td colspan="4" valign=middle class="smalltext">OR</td>
	   </tr>
	 <tr>
      <td align="right" valign=middle class="smalltext">Ads Path </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext"> <input type="text" name="content" value="{$smarty.request.content}"></td>
    </tr>
	{/if}
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Url</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td colspan="2" align="left" class="smalltext"><input type="text" name="url" value="{$smarty.request.url}">
        <br>
        (http://www.xyz.com)</td>
    </tr> 	
    <tr align="center"> 
      <td height="56" colspan=4 valign=center class="smalltext"> 
          <input name="btn_save" type=submit  class="naBtn" id="btn_save" onClick="return validateExtension();" value="Submit">
          &nbsp; 
          <input type=reset value="Reset"  class="naBtn">	 
	  </td> 
    </tr> 
      </table></td>
                  </tr>
                </table>
				</td>
              </tr>
   </table></td> 
  
  </tr> 
  
</table>
<table height="138">
	<tr>
		<td>&nbsp;</td>
	
	</tr>
</table>
</form>			