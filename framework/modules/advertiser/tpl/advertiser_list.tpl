<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function statusChange() 
{	{/literal}
	var	id	=	document.frm_flyer.status_id.value;
	document.frm_flyer.action="{makeLink mod=flyer pg=flyer}act=list{/makeLink}&status_id="+id; 
	document.frm_flyer.submit();
	{literal}
}
function pageSearch()
{
	cat_search	=	document.getElementById("flyer_search").value;
	document.frm_flyer.keysearch.value='Y';
	document.frm_flyer.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
	document.frm_flyer.submit();
}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_flyer.elements.length;i++)
			{
			var e = frm_flyer.elements[i];
					
					if(e.name=='category_id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one {/literal}{$smarty.request.sId}{literal}");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal}?'))
	{
		document.frm_flyer.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_flyer.submit();
	}
}
</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_flyer" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink">&nbsp;
	</td> 
        </tr> 
      </table></td> 
  </tr> 

   <tr>
     <td class="naGridTitle" height="24">&nbsp;Advertisement</td>
   </tr>

   <tr> 
     <td><table border="0" cellspacing="0" cellpadding="2" align="right">
       <tr>
         <td align="left" valign=middle><!--Show:&nbsp;--></td>
         <td align="left" valign=middle>
           <!--<select name="status_id" style="width:120px" onchange="javascript: statusChange();">
             <option value="all">All</option>
           </select>-->
         </td>
         <td align="left" valign=middle>&nbsp;</td>
         <td align="left" valign=middle><!--Search:--></td>
         <td><!--<input type="text" id="adv_search" name="adv_search" value="{$ADVERTISE_SEARCH_TAG}">--></td>
         <td><!--<input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();">--></td>
         <td>{if $ADVERTISE_LIMIT} No of Item In a Page:{$ADVERTISE_LIMIT} {/if}</td>
       </tr>
     </table></td>
   </tr>
   <tr>
    <td>



<table border=0 width=100% cellpadding="5" cellspacing="0">
  {if count($ADVERTISE_LIST) > 0}
  <tr>
    <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td>
    <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td>
    <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="advertiser_master.adv_title" display="Title"}act=list&sId={$smarty.request.sId}{/makeLink}</td>
    <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="member_master.username" display="User Name"}act=list&sId={$smarty.request.sId}{/makeLink}</td>
    <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="advertiser_master.active" display="Active"}act=list&sId={$smarty.request.sId}{/makeLink}</td>
  </tr>
  {foreach from=$ADVERTISE_LIST item=section}
  <tr class="{cycle values="naGrid1,naGrid2"}" >
    <td valign="middle" height="24" align="center">
		{if $section->flyer_id>0}
		<a class="linkOneActive" href="{makeLink mod=flyer pg=flyer}act=list&flyid={$section->flyer_id}&sId=Properties&fId=63{/makeLink}">
		{else}
		<a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=form&id={$section->aID}{/makeLink}">
		{/if}
		<img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0">
		</a>
	</td>
    <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&id={$section->aID}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
    <td valign="middle" height="24" align="left"> 

		{if $section->flyer_id ne "0"}
		<a class="linkOneActive" href="{makeLink mod=flyer pg=flyer}act=property_view&propid={$section->flyer_id}&flyer_id={$section->fddb_fid}&sId=Properties&fId=63{/makeLink}">{$section->adv_title}</a>
		{else}
		<a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=form&id={$section->aID}{/makeLink}">{$section->adv_title}</a>
		{/if}
	</td>
    <td valign="middle" height="24" align="left">{$section->username}</td>
    <td valign="middle" height="24" align="left">{if $section->active=='Y'}Yes{else}No{/if}</td>
  </tr>
  {/foreach}
  <tr>
    <td colspan="5" class="msg" align="center" height="30">{$ADVERTISE_NUMPAD}</td>
  </tr>
  {else}
  <tr class="naGrid2">
    <td colspan="5" class="naError" align="center" height="30">No Records</td>
  </tr>
  {/if}
</table>




	</td>
  </tr>
</table>
<input type="hidden" name="keysearch" value="N">
</form>