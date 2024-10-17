{literal}
<script language="javascript" type="text/javascript">

function changeStaticCombo(staticfield_id)
{
	if(staticfield_id != '') {
		document.nodeform.dynamicfield_id.value		=	'';
		document.nodeform.dynamicfield_id.disabled	=	'disabled';
	} else {
		document.nodeform.dynamicfield_id.disabled	=	'';
	}
			
}

function changeDynamicCombo(dynamicfield_id)
{
	if(dynamicfield_id != '') {
		document.nodeform.staticfield_id.value		=	'';
		document.nodeform.staticfield_id.disabled	=	'disabled';
	} else {
		document.nodeform.staticfield_id.disabled	=	'';
	}	
}

function childNodeChange(haschildnodes)
{	
	
	if(haschildnodes == 'Y') {
		document.nodeform.staticfield_id.value		=	'';
		document.nodeform.dynamicfield_id.value		=	'';
		document.nodeform.staticfield_id.disabled	=	'disabled';
		document.nodeform.dynamicfield_id.disabled	=	'disabled';
	} else if(haschildnodes == 'N') {
		document.nodeform.staticfield_id.disabled	=	'';
		document.nodeform.dynamicfield_id.disabled	=	'';
	}	

}

</script>

{/literal}

<form action="" method="POST" enctype="multipart/form-data" name="nodeform" style="margin: 0px;"> 
<table width=80% border=0 align="center" cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
	<input type="hidden" name="feed_id" value="{$smarty.request.feed_id}">
	<input type="hidden" name="node_id" value="{$smarty.request.node_id}">
	
  
    <tr align="left">
      <td colspan=3 valign=top>
	  <table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"><!--Categories-->{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=node_list&feed_id={$smarty.request.feed_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&sId={$smarty.request.sId}{/makeLink}">Node List</a></td>
        </tr>
      </table>
	  	  {messageBox}	  </td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">&nbsp;</td>
    </tr>

    <tr> 
      <td height="25" colspan=3 class="naGridTitle"><span class="group_style">Node Details </span></td> 
    </tr> 
  
	<tr class=naGrid1>
	  <td colspan="3" valign=top>&nbsp;</td>
    </tr>
	<tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Node Name </div></td> 
      <td width="3%" align="center" valign=top>:</td> 
      <td  width="57%"><input name="node_name" type="text" class="formText" id="node_name" value="{$NODE_VALUE.node_name}" size="40" maxlength="50" > </td> 
    </tr>
	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Has Child Nodes </div></td> 
      <td width=3% align="center" valign=top>:</td> 
      <td>
<select name="haschild_nodes" id="haschild_nodes" onChange="javascript: childNodeChange(this.value);" >
	<option value="N" {if $NODE_VALUE.haschild_nodes eq 'N'} selected="selected"{/if}  >No</option>
	<option value="Y" {if $NODE_VALUE.haschild_nodes eq 'Y'} selected="selected"{/if}>Yes</option>
</select>	</td> 
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=middle>Is List Container? </td>
	   <td align="center" valign=top>:</td>
	   <td>
<select name="listing_container" id="listing_container" >
	<option value="N" {if $NODE_VALUE.listing_container eq 'N'} selected="selected"{/if}  >No</option>
	<option value="Y" {if $NODE_VALUE.listing_container eq 'Y'} selected="selected"{/if}>Yes</option>
</select>	   
	   
	   </td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>Parent Node </td>
	   <td align="center" valign=top>:</td>
	   <td>
<select name="parent_nodeid" id="parent_nodeid" >
	<option value="">Select Parent Node </option>
	{html_options values=$PARENT_NODES.node_id output=$PARENT_NODES.node_name selected=$NODE_VALUE.parent_nodeid}
</select>	   </td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>Corresponding Flyer Field </td>
	   <td align="center" valign=top>:</td>
	   <td>
<select name="staticfield_id" id="staticfield_id" onChange="javascript: changeStaticCombo(this.value);">
	<option value="">Select Feature</option>
	{html_options values=$STATIC_FEATURES.id output=$STATIC_FEATURES.fieldname selected=$NODE_VALUE.staticfield_id}
</select>	   </td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>&nbsp;</td>
	   <td align="center" valign=top>&nbsp;</td>
	   <td>
<select name="dynamicfield_id" id="dynamicfield_id" onChange="javascript:changeDynamicCombo(this.value)" >
	<option value="">Select Feature</option>
	{html_options values=$DYNAMIC_FEATURES.feature_id output=$DYNAMIC_FEATURES.label selected=$NODE_VALUE.dynamicfield_id}
</select>	</td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>&nbsp;</td>
	   <td align="center" valign=top>&nbsp;</td>
	   <td>&nbsp;</td>
    </tr>
		
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
		   <input type="submit" value="Submit" name="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
</form>