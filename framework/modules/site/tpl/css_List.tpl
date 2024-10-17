<script language="javascript">
{literal}
	function showContent(css_id){
	{/literal}	
		document.getElementById('if_css').src="{makeLink mod=site pg=css}act=showimage{/makeLink}&css_id="+css_id;

{literal}
	}
{/literal}	
</script>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class=naBrdr>
       <tr>
        <td width="450"   align="right" valign="top">		
		 <div  STYLE=" width:450px; height:400px; overflow: auto;">
	      <table width="80%"  border="0" align="center" cellpadding="0" cellspacing="0">
		    {if count($CSS_LIST) > 0}
				<tr>
					 <td colspan="2" height="22"> </td>
				</tr>		
					{foreach from=$CSS_LIST item=css}										
					 <tr>
					   <td width="8%" align="left" valign="top"><img src="{$smarty.const.SITE_URL}/templates/template/images/thumb/temp_{$css->id}.gif" border="0"></td>
					   <td width="92%" align="left" valign="top">
					   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td height="102" align="center" valign="top"><br>
                           {$css->css_caption}						  
						   </td>
                         </tr>
                         <tr>
                           <td align="center" valign="baseline">
						    <a href="#" onClick="return showContent({$css->temp_id})"><img src="{$GLOBAL.tpl_url}/images/btn_preview.gif">&nbsp;</a>
						   	<a class="linkOneActive" href="{makeLink mod=site pg=css}act=assign_css&css_id={$css->temp_id}&site_id={$SITE_ID}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/btn_select.gif"></a>
						   </td>
                         </tr>
                       </table>
					   </td>
					 </tr>
					 <tr>
					 <td colspan="2" height="22">
					 </td>
					</tr>
				{/foreach} 
			{/if}
          </table>			 	
	    </div>		
		</td>
        <td width="10" align="center" valign="top">&nbsp;&nbsp;<iframe  id="if_css"  height="400" width="350" src="{makeLink mod=site pg=css}act=showimage{/makeLink}&css_id={$SITE_DETAILS.template_id}" frameborder="0"></iframe></td>			
		<td width="245" align="left" valign="top"></td>
      </tr>
</table>
