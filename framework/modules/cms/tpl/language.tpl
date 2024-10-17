<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<style type="text/css">
{literal}
.handle {
	cursor:move;
}
.ascdescClass {
	text-decoration:none;
}

.ascClass {
	text-decoration:none;
	cursor:pointer;
	background-image:url("{/literal}{$GLOBAL.tpl_url}{literal}/images/grid/grid.upArrow.gif");
	background-position:30%;
	background-repeat:no-repeat;
	
}
.descClass {
	text-decoration:none;
	cursor:pointer;
	background-image:url("{/literal}{$GLOBAL.tpl_url}{literal}/images/grid/grid.downArrow.gif");
	background-position:30%;
	background-repeat:no-repeat;
}
{/literal}
</style>
{literal}
<script language="javascript">
function getResults (orderElem,cPge,perPage) {

	document.getElementById('errMsg').style.display		= "none";
	document.getElementById('cont_load').style.display	= "inline";
	
	var req1  = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	
	/* FOR ORDER BY  */
	var elemOrder="";
	if(orderElem !=null) {
		if(orderElem.id == "CONST_ORDER") {
			elemOrder = 'CONSTANTS';
		}else{
			elemOrder = 'CAPTIONS';
		}
		
		if(orderElem.className == "ascdescClass") {
			orderElem.className = 'descClass';
			elemOrder = elemOrder + ":ASC";
		}else if(orderElem.className == "descClass"){
			orderElem.className = 'ascClass';
			elemOrder = elemOrder + ":DESC";
		}else if(orderElem.className == "ascClass"){
			orderElem.className = 'descClass';
			elemOrder = elemOrder + ":ASC";
		}
	}
	/* FOR ORDER BY  */
	
	
	var module_id = document.getElementById("module_id").value;
	var content_id= document.getElementById("content_id").value;
	
		
	
	document.getElementById("moduleid").value = module_id;
	document.getElementById("contentid").value = content_id;
	
	
	str	 = "module_id="+module_id+"&content_id="+content_id+"&orderPos="+elemOrder+"&cPge="+cPge+"&perPage="+perPage;
	
	
	
	{/literal}
	req1.open("POST", "{makeLink mod=cms pg=language}act=ajaxDefault{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}

function serverRese(_var) {
	if (_var == null) {
		document.getElementById('cont_load').style.display= "none";
	}else {
		_var = _var.split('|');
		if (_var[0]) {
		document.getElementById('cont_results').innerHTML = _var[0]; 
		document.getElementById('cont_load').style.display= "none";
		}
	}
	
}

</script>
{/literal}
<div id="errMsg">
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
</div>
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0"> 
    <tr>
      <td width="3%">&nbsp;</td>
      <td width="30%" valign="top">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">Module Area</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="5" cellspacing="0"> 
                
				
				
				
				
                <tr>
                  <td height="25" align="center" class="naGrid1">Modules :				  </td>
                  <td align="center" class="naGrid1">
				  
				  
				  <select name="module_id" id="module_id" onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}{/makeLink}&content_id={$smarty.request.content_id}&module_id='+this.value">
						  <option value="">-- SELECT A MODULE --</option>
                		 {html_options values=$MODULES_ARRAY_LIST.value output=$MODULES_ARRAY_LIST.display selected=`$smarty.request.module_id`}
                  </select>
				  
				  
				  </td>
                </tr>
				
				
				
              </table></td> 
          </tr> 
        </table>      
        <br />
		
		
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td colspan="2"><table width="98%" align="center">
              <tr>
                <td nowrap="nowrap" class="naH1">Content Area</td>
              </tr>
            </table></td>
          </tr> 
		  
		  
		  
		  
          <tr>
            <td width="30%"  align="center" valign="top" class="naGrid1">Area :</td>
            <td width="70%"  align="center" valign="top" class="naGrid1"><select name="content_id" id="content_id" onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}{/makeLink}&module_id={$smarty.request.module_id}&content_id='+this.value">
              
             			  <option value="">-- SELECT AREA --</option>
                		 {html_options values=$LANG_CONT_LIST.value output=$LANG_CONT_LIST.display selected=`$smarty.request.content_id`}
                  
            
            </select>
            </td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
        </table>		
		
        <br />
        <br /></td>
      <td width="3%">&nbsp;</td>
      <td width="61%" valign="top">
	  
	  
	  
	  
	    
			<form action="" method="post" style="margin:0px;" name="langFrm">
			
			
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
			  <tr> 
				<td height="30"><table width="98%" align="center"> 
					<tr> 
					  <td nowrap class="naH1" width="20%">Variables</td> 
				      <td nowrap  width="80%"><img id="cont_load" align="absmiddle" src="{$GLOBAL.tpl_url}/images/loading16.gif" style="display:none" /></td>
					</tr> 
				  </table></td> 
			  </tr> 
			  
			  
			  <tr>
				<td  class="naGridTitle"><table width="100%" border="0">
                  <tr nowrap="nowrap">
                    <td id="CONST_ORDER"  width="50%" class="ascdescClass">Constants</td>
                    <td id="CAPT_ORDER"  width="50%" class="ascdescClass">Captions</td>
                  </tr>
                </table></td>
			  </tr>
			  
			  
			  <tr>
				<td class="naGrid1" height="1"><div></div></td>
			  </tr>

			  
			  <tr>
			    <td>
					<div id="cont_results">
					{if count($CONTENT_VARIABLES_LIST) > 0}
					<table width="100%" border="0">
					  					  
					  
					  <tr height="20">
					  
					    <td colspan="2" width="100%">
						{foreach from=$CONTENT_VARIABLES_LIST item=variab_item name=variab_name}
						<table width="100%" border="0" class="{cycle values="naGrid1,naGrid2"}" height="15">
						  <tr>
							<td align="left" width="50%">{$variab_item.const}</td>
							<td align="left" width="50%"><input style="width:250px" name="{$variab_item.const}" type="text" class="input" value="{$variab_item.variab}" /></td>
						  </tr>
						</table>
						{/foreach}						</td>
				      </tr>
					  
					  					  
					</table>
					{/if}
					</div>
					
					
					<div align="center" style="border:1px solid silver;padding:3px"  class="naError" >
					{if count($CONTENT_VARIABLES_LIST) > 0}
					<input type="submit" name="Submit" value="Save Changes" class="naBtn" />
					{else}
					 No Records
					{/if}
				    
					</div>
					<input name="moduleid" id="moduleid" type="hidden" value="{$smarty.request.module_id}" />
					<input name="contentid" id="contentid" type="hidden" value="{$smarty.request.content_id}" />
				</td>
		      </tr>
			</table>
			
			
			</form>
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  </td>
      <td width="3%">&nbsp;</td>
    </tr>
	
	 
</table>
