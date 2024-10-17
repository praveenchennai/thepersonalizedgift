<script language="javascript">
{literal}
	function setWin(user_id){	
{/literal}		
		 var mywindow=window.open ("{makeLink mod=banner pg=banner_ads}act=user{/makeLink}&user_id="+user_id,
		"test","menubar=0,toolbar=0,height="+(screen.height-200)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
  {literal} 
  	 }
{/literal}
{literal}
	function planWin(plan_id){	
{/literal}		
		 var mywindow=window.open ("{makeLink mod=banner pg=banner_plans}act=plan{/makeLink}&plan_id="+plan_id,
		"test","menubar=0,toolbar=0,height="+(screen.height-200)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
  {literal} 
  	 }
{/literal}
{literal}
	function banWin(ban_id){	
{/literal}		
		 var mywindow=window.open ("{makeLink mod=advertiser pg=banner_ads}act=banner{/makeLink}&ban_id="+ban_id,
		"test","menubar=0,toolbar=0,height="+(screen.height-200)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
 {literal} 
  	 }
{/literal}
{literal}
	function expWin(ban_id){	
{/literal}		
		 var mywindow=window.open ("{makeLink mod=banner pg=banner_ads}act=expiry{/makeLink}&ban_id="+ban_id,
		"test","menubar=0,toolbar=0,height="+(screen.height-200)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
 {literal} 
  	 }
{/literal}
</script>
<!--<div  STYLE="width:770px;  overflow: auto;"></div>-->
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Advertisement</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=advertiser pg=banner_ads}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td>
	<table border=0 width=100% cellpadding="0" cellspacing="0"> 
        {if count($ADS_LIST) >0}        <tr>
			<td width="5%" height="24" align="center" nowrap class="naGridTitle"></td> 
			<td width="5%" height="24" align="center" nowrap class="naGridTitle"></td> 
			{if ($CHKURL !='Y')}
			<td width="9%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="camp_name" display="Campaign Name"}act=list{/makeLink}</td>
			<td width="8%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="plan_name" display="Plan Name"}act=list{/makeLink}</td>  
			<td width="8%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="company_name" display="Title"}act=list{/makeLink}</td> 
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="username" display="User Name"}act=list{/makeLink}</td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="file_type" display="File Type"}act=list{/makeLink}</td>
			<td width="20%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="start_date" display="Start Date"}act=list{/makeLink}</td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="end_date" display="Expiry Date"}act=list{/makeLink}</td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy=" plan_price" display="Amount"}act=list{/makeLink}</td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_ads orderBy="click_count" display="No of Click"}act=list{/makeLink}</td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle"></td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle"></td>
			{else if($CHKURL !='Y')}
			<td width="40%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=advertiser pg=banner_ads orderBy="company_name" display="Title"}act=list{/makeLink}</td> 
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=advertiser pg=banner_ads orderBy="view_count" display="View"}act=list{/makeLink}</td>
			<td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=advertiser pg=banner_ads orderBy="click_count" display="No of Click"}act=list{/makeLink}</td>
			{/if}
		</tr>     
   		{foreach from=$ADS_LIST item=ads}
       <tr class="{cycle values="naGrid1,naGrid2"}">
	   	  <td  height="24" align="center" nowrap  ><a class="linkOneActive" href="{makeLink mod=advertiser pg=banner_ads}act=delete&id={$ads->ban_id}{/makeLink}" onClick="return confirm('Do you really want to delete the record?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
          <td width="9%" height="24" align="center" nowrap  ><a class="linkOneActive" href="{makeLink mod=advertiser pg=banner_ads}act=form&id={$ads->ban_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
		  {if ($CHKURL !='Y')}
		  <td width="9%" height="24" align="center" nowrap  >{$ads->camp_name}</td> 
          <td width="8%" height="24" align="center" nowrap ><a class="linkOneActive" href="#" onClick=" return planWin({$ads->plan_id})">{$ads->plan_name}</a></td>           
          <td width="8%" height="24" align="center" nowrap ><a class="linkOneActive" href="#" onClick=" return banWin({$ads->ban_id})">{$ads->company_name}</a></td>           
		  <td width="21%" height="24" align="center" nowrap ><a class="linkOneActive" href="#" onClick=" return setWin({$ads->user_id})">{$ads->username}</a></td>
          <td width="21%" height="24" align="center" nowrap >{$ads->file_type}</td> 
		  <td width="20%" height="24" align="left" nowrap >{$ads->start_date}</td>
		  <td width="21%" height="24" align="left" nowrap >{$ads->end_date}</td>
		  <td width="21%" height="24" align="left" nowrap >{$ads->plan_price}</td>
		  <td width="21%" height="24" align="left" nowrap >{$ads->click_count}</td>
		  <td width="21%" height="24" align="left" nowrap >{if $ads->status==I}<a class="linkOneActive" href="{makeLink mod=banner pg=banner_ads}act=sus&id={$ads->ban_id}&status=A&user_id={$REQ_USER}{/makeLink}">Activate</a>{else}<a class="linkOneActive" href="{makeLink mod=banner pg=banner_ads}act=sus&id={$ads->ban_id}&status=I&user_id={$REQ_USER}{/makeLink}">Make Inactive</a>{/if}</td>
      	 <td width="8%" height="24" align="center" nowrap ><a class="linkOneActive" href="#" onClick=" return expWin({$ads->ban_id})">Extend Expiry</a></td>
		 {else if($CHKURL !='Y')}
		  <td width="40%" height="24" align="center" nowrap ><a href="#" onClick=" return banWin({$ads->ban_id})">{$ads->company_name}</a></td> 
		  <td width="21%" height="24" align="left" nowrap >{$ads->view_count}</td>  
		  <td width="21%" height="24" align="left" nowrap >{$ads->click_count}</td>         
		 {/if}
	   </tr>   
        {/foreach}
        <tr> 
          <td colspan="11" class="msg" align="center" height="30">{$ADS_NUMPAD}</td> 
        </tr>
        {else}			
         <tr class="naGrid2"> 
          <td colspan="11" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}		
      </table>
	</td> 
  </tr> 
</table>
