<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>

{literal}
<script language="javascript">
function showPopup(static) {
window.open( "https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside", "", 
"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )

}

function popup()
{

	acc_id= document.getElementById("art_id").value;
	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=700,height=500,scrollbars=yes');
	w.focus();	

}

function  showMatPopup()
{
	acc_id= document.getElementById("mat_id").value;
	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=700,height=500,scrollbars=yes');
	w.focus();	

}
function  showFramePopup()
{
	acc_id= document.getElementById("frame_id").value;
	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=700,height=500,scrollbars=yes');
	w.focus();	

}

function  showPoemPopup()
{
	acc_id= document.getElementById("poem_id").value;
	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=700,height=500,scrollbars=yes');
	w.focus();	
	

}

function showAccessory(product_id)
{
	
	
	if(product_id==501)
	{
		document.getElementById("poem_id").value='';
		document.getElementById("poem_select").style.display='none';
		document.getElementById("art_select").style.display='none';
	}
	else
	{
		document.getElementById("art_select").style.display='block';
	}
	
	
	
	if(product_id==496)
	{
		document.getElementById("poem_select").style.display='block';
	}
	else
	{
		document.getElementById("poem_select").style.display='none';
	}
	
	
	
	if(product_id==494 || product_id==495 )
	{
	
		//showFieldsByProduct(product_id,'');
		//document.frmGift.submit();
		// return true;
		document.getElementById("poem_id").value='';

	}
	else
		document.getElementById("show_fields").style.display='none';	
	
}

	var fields=new Array('category','product_id','product_title','product_description','product_basic_price','product_sale_price');
	var msgs=new Array('Gift Category','Gift Type','Product Title','Product Description','Basic price','Sale price');
	

	var nums=new Array('product_basic_price','product_sale_price');
	var nums_msgs=new Array('Basic price should be a number','Sale price should be a number');
	

function frmSubmit()
{
	if (chk(document.frmGift))
		return true;
	else
		return false;
	
}

function CancelArt()
{
	document.getElementById("art_selected").innerHTML = '';
	document.frmGift.art_id.value = '';
	document.getElementById("art_preview").style.visibility= 'hidden';
	document.getElementById("art_sel_btn").style.display='block';
	document.getElementById("art_cal_btn").style.display='none';
}
function CancelMat()
{
		document.getElementById("mat_selected").innerHTML = '';
		document.frmGift.mat_id.value = '';
		document.getElementById("mat_preview").style.visibility= 'hidden';
		document.getElementById("mat_sel_btn").style.display='block';
		document.getElementById("mat_cal_btn").style.display='none';
}

function CanceLFrame()
{
		document.getElementById("frame_selected").innerHTML = '';
		document.frmGift.frame_id.value = '';
		document.getElementById("frame_preview").style.visibility= 'hidden';
		document.getElementById("frame_sel_btn").style.display='block';
		document.getElementById("frame_cal_btn").style.display='none';
}


function CanceLPoem()
{
		document.getElementById("poem_selected").innerHTML = '';
		document.frmGift.poem_id.value = '';
		document.getElementById("poem_preview").style.visibility= 'hidden';
		document.getElementById("poem_sel_btn").style.display='block';
		document.getElementById("poem_cal_btn").style.display='none';
		document.getElementById("show_fields").style.display='none';
}


/*function showFieldsByProduct(product_id,acc_id)
{
	var req1  = newXMLHttpRequest();
	
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese3);
	str	 = "product_id="+product_id+"&acc_id="+acc_id;
	//prompt("s",str);
	{/literal}
	req1.open("POST", "{makeLink mod=product pg=index}act=predef_inc_fields{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverRese3(_var) {
		
		document.getElementById('show_fields').innerHTML=_var;
		document.getElementById("show_fields").style.display='block';
	
}*/

function showFieldsByProduct(product_id,acc_id)
{
	
	window.location.href='{/literal}{makeLink mod=store pg=product_index}act=predef_gift&id={$smarty.request.id}{/makeLink}{literal}&product_id='+frmGift.product_id.value+'&category='+document.frmGift.category.value+'&poem_id='+document.frmGift.poem_id.value+'&art_id='+document.frmGift.art_id.value+'&mat_id='+document.frmGift.mat_id.value+'&frame_id='+document.frmGift.frame_id.value;

}

function frmSub()
{
	var str,reurl;
	var url;	
	var s1='';
	var s2='';
	var k=1;
	var m=1;
	if(document.frmGift.opt){
	
	
		var len=document.getElementsByName("opt").length;
		
		for(var i=0;i< len;i++)
		{
			str =encodeURIComponent(document.getElementById("opt"+i).value);
			s1=s1+"&op"+k+"="+str;
			k++;
		}
		document.getElementById("hdOpLine").value=s1;
	}
	if(document.frmGift.col){
		var len2=document.getElementsByName("col").length;
		for(var i=0;i< len2;i++)
		{
			str =encodeURIComponent(document.getElementById("col"+i).value);
			s2=s2+"&cl"+m+"="+str;
			m++;
		}
	}
	if(s1 && s2)
	{
		var opt_val=s1+s2;
		//alert(opt_val);
	}
	else if(s1){
		var opt_val=s1;
	}
	else if	(s2){
		var opt_val=s2;
	}
	alert(opt_val);
	
}

	

</script>
{/literal}
<table width="820" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">

    <td valign="top">
<table width=80% border=0 align="center" cellpadding="0" cellspacing="1" class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frmGift" >

	<input type="hidden" name="art_id" id="art_id" value="{$smarty.request.art_id}" />
	<input type="hidden" name="mat_id" id="mat_id" value="{$smarty.request.mat_id}" />
	<input type="hidden" name="frame_id" id="frame_id" value="{$smarty.request.frame_id}" />
	<input type="hidden" name="poem_id" id="poem_id" value="{$smarty.request.poem_id}" />

    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">Predefined Gift</td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;<a href="{makeLink mod=product pg=index}act=predef_gift_list{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr valign="middle" >
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift</strong></td>
    </tr>
	  <tr valign="middle"  class="naGrid1">
      <td width="308" height="25"  align="right"><span class="fieldname">Gift Category:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="category"  style="width:200px">
	 	<option value="">-Select-</option>
		{foreach from=$CATEGORY item=giftcat}
			<option value="{$giftcat->category_id}" {if $smarty.request.category eq $giftcat->category_id} selected="selected"{/if} >{$giftcat->category_name}</option>
		{/foreach}
	</select>
      </span></td>
    </tr>
    <tr valign="middle" class="naGrid2">
      <td width="308" height="25"  align="right"><span class="fieldname">Gift Type:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="product_id"  style="width:200px" onchange=" showAccessory(this.value);window.location.href='{makeLink mod=store pg=product_index}act=predef_gift&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}&product_id='+this.value+'&category='+document.frmGift.category.value+'&art_id='+document.frmGift.art_id.value+'&mat_id='+document.frmGift.mat_id.value+'&frame_id='+document.frmGift.frame_id.value+'&poem_id='+document.frmGift.poem_id.value+'&product_title='+document.frmGift.product_title.value+'&product_description='+document.frmGift.product_description.value+'&product_basic_price='+document.frmGift.product_basic_price.value+'&product_sale_price='+document.frmGift.product_sale_price.value{if $smarty.request.id}+'&id='+{$smarty.request.id}{/if};">
	 	<option value="">-Select-</option>
		{foreach from=$PRODUCTS item=temp}
			<option value="{$temp->id}" {if $smarty.request.product_id eq $temp->id} selected="selected"{/if}>{$temp->name}</option>
		{/foreach}
	</select>
      </span></td>
	  <tr valign="middle" >
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Sub Products</strong></td>
    </tr>
	 <tr  class="{cycle values="naGrid1,naGrid2"}">
	 		<td colspan="3"  ><div style="display:{if $POEM_DET}block{elseif $smarty.request.product_id eq 496}block{else}none{/if}" id="poem_select"><table border="0" cellpadding="0" cellspacing="0" width="100%">
			 <tr valign="middle">
      <td width="325" height="25"  align="right"><span class="fieldname">Select a Poem:&nbsp;</span></td>
      <td width="10" height="25">&nbsp;</td>
      <td width="470" height="25" align="left">
	  
	  <table width="90%" border="0" cellpadding="0"  cellspacing="1" >
  <tr>
    <td width="400">
	 <div id="poem_selected" style="float:left">&nbsp;{$POEM_DET.name}</div></td>
    <td><a href="#" onclick="showPoemPopup();" id="poem_preview" style="visibility:{if $POEM_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input type="button" class="naBtn" value="Select"  id="poem_sel_btn" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=174{/makeLink}', 'w', 'width=700,height=500,scrollbars=yes');w.focus();" style="display:{if $POEM_DET}none{else}block{/if}; width:62px"  />
	 <input type="button" id="poem_cal_btn"  class="naBtn" value="Cancel " onclick="CanceLPoem();"   style="display:{if $POEM_DET}block{else}none{/if}"   />
	
	</td>
  </tr>
</table>

	 </td>
    </tr>
			</table></div></td>
	 </tr> 
	  
	<tr>
		<td  colspan="3"><div style="display:block" id="art_select"><table border="0" width="100%" cellpadding="0" cellspacing="1"  >
		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}" >
      <td width="325" height="25"  align="right"><span class="fieldname">Select an Art Background:&nbsp;</span></td>
      <td width="16" height="25" >&nbsp;</td>
      <td width="470" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="1"  >
  <tr>
    <td width="400">
	 <div id="art_selected" style="float:left">&nbsp;{$ART_DET.name}</div></td>
    <td><a href="#" onclick="popup();" id="art_preview" style="visibility:{if $ART_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="art_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=203{/makeLink}', 'w', 'width=700,height=500,scrollbars=yes');w.focus();" style="display:{if $ART_DET}none{else}block{/if}; width:62px"   />
	<input id="art_cal_btn" style="display:{if $ART_DET}block{else}none{/if}" type="button" class="naBtn" value="Cancel "  onclick="CancelArt();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div></td>	
	</tr>
	
	
	 <tr >
		<td  colspan="3"  ><div style="display:block" id="art_select"><table border="0" width="100%" cellpadding="0" cellspacing="1">
		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="325" height="25"  align="right"><span class="fieldname">Select a Mat:</span></td>
      <td width="16px" height="25"  >&nbsp;</td>
      <td width="470" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="0" >
  <tr>
    <td width="400"> <div id="mat_selected" style="float:left">&nbsp;{$MAT_DET.name}</div></td>
    <td><a href="#" onclick="showMatPopup();" id="mat_preview" style="visibility:{if $MAT_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="mat_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=235{/makeLink}', 'w', 'width=700,height=500,scrollbars=yes');w.focus();" style="display:{if $MAT_DET}none{else}block{/if}; width:62px"   />
	
	<input id="mat_cal_btn" style="display:{if $MAT_DET}block{else}none{/if}"  type="button" class="naBtn" value="Cancel " onclick="CancelMat();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div></td>	
	</tr>
	 </tr>
	 <tr>
		<td  colspan="3"  ><div style="display:block" id="art_select"><table border="0" width="100%" cellpadding="0" cellspacing="1">
		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}" >
      <td width="325" height="25"  align="right"><span class="fieldname">Select a Frame:</span></td>
      <td width="16" height="25"  >&nbsp;</td>
      <td width="470" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="0" >
  <tr>
    <td width="400"> <div id="frame_selected" style="float:left">&nbsp;{$FRAME_DET.name}</div></td>
    <td><a href="#" onclick="showFramePopup();" id="frame_preview" style="visibility:{if $FRAME_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="frame_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=245{/makeLink}', 'w', 'width=700,height=500,scrollbars=yes');w.focus();"  style="display:{if $FRAME_DET}none{else}block{/if}; width:62px"   />
	<input id="frame_cal_btn"  style="display:{if $FRAME_DET}block{else}none{/if}"  type="button" class="naBtn" value="Cancel " onclick="CanceLFrame();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div></td>	
	</tr>
	
	
	
	<tr class="naGrid2">
		<td  colspan="3"  ><div style="display:block" id="show_fields">
		{if $PRODUCT_NAME eq 'Single Name Gift'}		
		
	<table border="0" cellpadding="0" cellspacing="1" width="100%">
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Name|Gender|Language</strong></td>
    </tr>
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="306" height="25"  align="right"><span class="fieldname">Enter Name:</span></td>
      <td width="18" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="first_name" id="first_name" value="{$smarty.request.first_name}" size="40"  />
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Gender:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="gender" id="gender" style="width:200px">
	 	<option value="">-Select-</option>
	 	<option value="M" {if $smarty.request.gender eq 'M' or $smarty.request.gender eq ''} selected="selected"{/if}>Male</option>
		<option value="F"  {if $smarty.request.gender eq 'F'} selected="selected"{/if}>Female</option>
	 </select>
      </span></td>
    </tr>
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Language:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="lang" id="lang" style="width:200px">
	 	<option value="1" {if $smarty.request.lang eq 1 or $smarty.request.lang eq ''} selected="selected"{/if}>English</option>
		<option value="2" {if $smarty.request.lang eq 2 } selected="selected"{/if}>Spanish</option>
	 </select>
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Sentiments</strong></td>
    </tr>
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 1:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="sentiment1" id="sentiment1" value="{$smarty.request.sentiment1}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 2:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input   type="text" name="sentiment2" id="sentiment2" value="{$smarty.request.sentiment2}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	
		</table>
		
	
		{elseif $PRODUCT_NAME eq 'Double Name Gift'}
		<table cellpadding="0" cellspacing="1" border="0" width="100%">
		<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Name|Gender|Language</strong></td>
    </tr>
<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="300" height="25"  align="right"><span class="fieldname">Enter Name1:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="first_name" id="first_name" value="{$smarty.request.first_name}" size="40" />
      </span></td>
    </tr>
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Gender1:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="gender" id="gender" style="width:200px">
	 	<option value="">-Select-</option>
	 	<option value="M" {if $smarty.request.gender eq 'M' or $smarty.request.gender eq ''} selected="selected"{/if}>Male</option>
		<option value="F"  {if $smarty.request.gender eq 'F'} selected="selected"{/if}>Female</option>
	 </select>
      </span></td>
    </tr>
	<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="300" height="25"  align="right"><span class="fieldname">Enter Name2:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="first_name2" id="first_name2" value="{$smarty.request.first_name2}" size="40"   />
      </span></td>
    </tr>
	<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Gender2:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="gender2" id="gender2" style="width:200px" >
	 	<option value="M" {if $smarty.request.gender2 eq 'M' or $smarty.request.gender2 eq ''} selected="selected"{/if}>Male</option>
		<option value="F"  {if $smarty.request.gender2 eq 'F'} selected="selected"{/if}>Female</option>
	 </select>
      </span></td>
    </tr>
	<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Language:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="lang" id="lang" style="width:250px">
	 	<option value="">-Select-</option>
	 	<option value="1" {if $smarty.request.lang eq 1 or $smarty.request.lang eq ''} selected="selected"{/if}>English</option>
		<option value="2" {if $smarty.request.lang eq 2 } selected="selected"{/if}>Spanish</option>
	 </select>
      </span></td>
    </tr>
		  <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Sentiments</strong></td>
    </tr>
	 
	 <tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 1:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="sentiment1" id="sentiment1" value="{$smarty.request.sentiment1}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	
	 <tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 2:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="sentiment2" id="sentiment2" value="{$smarty.request.sentiment2}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	  
	
	</table>
	{elseif $PRODUCT_NAME eq 'Poetry Gift'}
		
		<table cellpadding="0" cellspacing="1" border="0" width="100%">

{section name=op loop=$OP_COUNT}

<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="310" height="25"  align="right"><span class="fieldname">Opening Line {$smarty.section.op.iteration} :</span></td>
      <td width="16" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="opt[]" id="opt{$smarty.section.op.index}" value="{foreach from=$OP_ARRAY item=op name=oploop}{if $smarty.section.op.index eq $smarty.foreach.oploop.index}{$op}{/if}{/foreach}" size="40" maxlength="255"  />
      </span></td>
    </tr>
{/section}	
{section name=cl loop=$CL_COUNT}

<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="310" height="25"  align="right"><span class="fieldname">Closing Line {$smarty.section.cl.iteration} :</span></td>
      <td width="14" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="col[]" id="col{$smarty.section.cl.index}" value="{foreach from=$CL_ARRAY item=cl name=cloop}{if $smarty.section.cl.index eq $smarty.foreach.cloop.index}{$cl}{/if}{/foreach}" size="40" maxlength="255"  />
      </span></td>
    </tr>
{/section}	

	</table>
		
		
		{/if}
		
		
		
		</div></td>	
	</tr>
	
	
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Product Details</strong></td>
    </tr>
	

	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Product Title:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="product_title" id="product_title"  size="40"  value="{$smarty.request.product_title}" />
      </span></td>
    </tr>
	
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Description:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <textarea rows="10" name="product_description" id="product_description"  cols="37">{$smarty.request.product_description}</textarea>
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Basic price($):</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="product_basic_price" id="product_basic_price" maxlength="10"  size="40" value="{$smarty.request.product_basic_price}" />
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sale price($):</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="product_sale_price" id="product_sale_price" maxlength="10"  size="40" value="{$smarty.request.product_sale_price}" />
      </span></td>
    </tr>
	
			
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Active:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left" valign="middle"><span class="formfield">
	<input type="radio" name="product_active" value="Y" {if $smarty.request.product_active eq 'Y' or $smarty.request.active eq ''} checked="checked" {/if} />Yes 
	<input type="radio" name="product_active" value="N"  {if $smarty.request.product_active eq 'N'  } checked="checked" {/if} />No
        
      </span></td>
    </tr>
    
   
    <tr class="naGridTitle" height="25"> 
      <td colspan=3 valign=center ><div align=center>	  
	       <input type=submit name="submit" value="Submit" class="naBtn" >&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
</td>
  </tr>
</table>
</td>
