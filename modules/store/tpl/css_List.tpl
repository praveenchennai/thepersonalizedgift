<script language="javascript">
{literal}

function submitForm(cssId)		
{
{/literal}	
document.cssForm.action="{makeLink mod=$MOD pg=$PG}act=assign_css&sId={$SID}&fId={$FID}&store_id={$STORE_ID}{/makeLink}&css_id="+cssId;
{literal}
document.cssForm.submit();
}

function submitFormavtar(avatarid)	
{
	{/literal}
	document.cssForm.action="{makeLink mod=$MOD pg=$PG}act=assign_css&sId={$SID}&fId={$FID}&store_id={$STORE_ID}{/makeLink}";
	{literal}
	document.cssForm.hdn_avtor.vale='Y';
	document.cssForm.avtar.value=avatarid;
	document.cssForm.submit();
}

	function showContent(css_id){
	
	{/literal}	
			//document.getElementById('if_css').src="{makeLink mod=store pg=index}act=showimage{/makeLink}&css_id="+css_id;
			//var preview_css="<img src='{$smarty.const.SITE_URL}/templates/template/images/large_";
			var preview_img="{$smarty.const.SITE_URL}/templates/template/images/large_";
	{literal}
		//preview_css=preview_css+css_id+".gif'>";
		preview_img=preview_img+css_id+".gif";
		//prompt("s",preview_img);
		document.cssForm.css.value=css_id;
			//document.getElementById('css_div').innerHTML=preview_css;
		document.getElementById('css_img').src=preview_img;
		//alert(document.getElementById('css_img').src);
	}
	function showContent_avatar(avatar_id){
			//document.getElementById('if_avatar').src="{makeLink mod=store pg=index}act=showavatar{/makeLink}&avatar_id="+avatar_id;
			{/literal}
			var preview_avatar="<img src='{$smarty.const.SITE_URL}/templates/template/images/lady";
			{literal}
			preview_avatar=preview_avatar+avatar_id+".gif'>";
			document.cssForm.avtar.value=avatar_id;
			document.getElementById('avatar_div').innerHTML=preview_avatar;
	
	}
	
	function submitIDs(){
		//var avatar = window.if_avatar.document.getElementById('avatar_div').innerHTML;
		//var css = window.if_css.document.getElementById('css_div').innerHTML;
		{/literal}	
		
		
		document.cssForm.action="{makeLink mod=store pg=home_css}act=assign_css_ptp&sId={$SID}&fId={$FID}&store_id={$STORE_ID}{/makeLink}";
		
		{literal}
		document.cssForm.submit();
	}
{/literal}	

</script>
<form name="cssForm" action="" method="post">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="80%"  border="0" cellspacing="0" cellpadding="0" class=naBrdr>
  <tr height="30">
    <td width="49%" colspan="2">&nbsp;&nbsp;<strong>Store Template</strong></td>
    <td width="2%">&nbsp;</td>
    <td width="49%">&nbsp;</td>
  </tr>
  <tr>
    <td height="100%" colspan="2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		    {if count($CSS_LIST) > 0}
						 <tr>{foreach from=$CSS_LIST item=css name=foo}	
					 {if  $smarty.foreach.foo.index ne "0"  && $smarty.foreach.foo.index is div by 2}
			</tr><tr>
						{/if}
					   <td width="50%" align="center" valign="top">
					   <img src="{$smarty.const.SITE_URL}/templates/template/images/thumb/temp_{$css->id}.gif" border="0"><img src="{$smarty.const.SITE_URL}/templates/template/images/large_{$css->id}.gif" border="0" style="display:none; ">
					   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                           <td height="10" align="center"></td>
                         </tr> 
                         <tr>
                           <td align="center" valign="baseline">
						    <a href="javascript:void(0);" onClick="return showContent({$css->temp_id})"><img src="{$GLOBAL.tpl_url}/images/btn_preview.jpg"></a> 
                            <!-- <a class="linkOneActive" href="#" onClick="submitForm({$css->temp_id});"><img src="{$GLOBAL.tpl_url}/images/btn_preview.jpg"></a>--> </td>
                         </tr>
						 <tr>
						 <td height="20"></td></tr>
                       </table>
					   </td>
					   
					   {/foreach} 
					 </tr>
					 <tr>
					 <td colspan="2" height="22">
					 </td>
					</tr>
				
			{/if}
			 
			
          </table></td>
    <td width="1" height="100%" rowspan="8" valign="top" align="center"><table width="1" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="1" height="100%" bgcolor="#999999"><img src="{$smarty.const.SITE_URL}/templates/template/images/spacer.gif"></td>
          </tr>
        </table></td>
    <td align="center"><div id="css_div"><img id="css_img" src="{$smarty.const.SITE_URL}/templates/template/images/large_{$STORE_DETAILS.template_id}.gif"   ></div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
    <td colspan="2">&nbsp;&nbsp;<strong>Store Avator</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="100%" align="center"><img src="{$smarty.const.SITE_URL}/templates/template/images/lady1.gif" ></td>
   <td align="center"><img src="{$smarty.const.SITE_URL}/templates/template/images/lady2.gif" ></td>
    <td rowspan="4" align="center"><div id="avatar_div"><img src="{$smarty.const.SITE_URL}/templates/template/images/lady{$STORE_DETAILS.avator}.gif" ></div></td>
  </tr>
  <tr height="60">
    <td align="center"><a class="linkOneActive" href="javascript:void(0);" onClick="showContent_avatar('1');"><img src="{$GLOBAL.tpl_url}/images/btn_preview.jpg"></a></td>
   <td align="center"><a class="linkOneActive" href="javascript:void(0);" onClick="showContent_avatar('2');"><img src="{$GLOBAL.tpl_url}/images/btn_preview.jpg"></a></td>
    </tr>
  <tr>
    <td align="center"><img src="{$smarty.const.SITE_URL}/templates/template/images/lady3.gif"></td>
  <td align="center"><img src="{$smarty.const.SITE_URL}/templates/template/images/lady4.gif"></td>
    </tr>
  <tr height="60">
    <td align="center"><a class="linkOneActive" href="javascript:void(0);" onClick="showContent_avatar('3');"><img src="{$GLOBAL.tpl_url}/images/btn_preview.jpg"></a></td>
    <td align="center"><a class="linkOneActive" href="javascript:void(0);" onClick="showContent_avatar('4');"><img src="{$GLOBAL.tpl_url}/images/btn_preview.jpg"></a></td>
    </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td colspan="2" align="right"><a class="linkOneActive" href="#" onClick="submitIDs();"><img src="{$GLOBAL.tpl_url}/images/btn_submit.gif"></a></td>
    <td>&nbsp;</td>
    <td align="left"><a class="linkOneActive" href="#" onClick="javascript:history.go(-1);"><img src="{$GLOBAL.tpl_url}/images/btn_back.gif"></a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="hdn_avtor" id="hdn_avtor" value="">
<input type="hidden" name="avtar" id="avtar" value="">
<input type="hidden" name="css" id="css" value="{$STORE_DETAILS.template_id}">
</form>