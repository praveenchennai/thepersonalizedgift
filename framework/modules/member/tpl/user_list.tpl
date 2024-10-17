{literal}
<script language="javascript">

function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<fruser.elements.length;i++)
			{
			var e = fruser.elements[i];
					
					if(e.name=='user_id[]')
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
		document.fruser.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=user_delete&&pageNo={$smarty.request.pageNo}&sId={$smarty.request.sId}&limit={$smarty.request.limit}&mem_type={$smarty.request.mem_type}&mem_type={$smarty.request.mem_type}{/makeLink}{literal}'; 
		document.fruser.submit();
	}
}

function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=850,height=200,screenX=150,screenY=150,top=150,left=150')
}
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="100%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{if $smarty.request.mem_type eq 2}Stower Owners{else}Registered Users{/if}<!--{$SUBNAME}--></td> 
		  <td nowrap  align="right">Search by username: <input type="text" name="txtsearch" value="{$smarty.request.txtsearch}">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" ><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&mem_type={$smarty.request.mem_type}{/makeLink}&sId={$SUBNAME}&mId={$MID}&txtsearch={$smarty.request.txtsearch}">View All</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		{if $SECTION_LIST}
			<tr>
		  		<td colspan="2"  align="center" class="naGrid1">{if count($USER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td> 
          		<td colspan="4"  align="right" class="naGrid1">Member Type : 
		  			<select name=section_id onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=user}act=list{/makeLink}&mem_type='+this.value">
							{html_options values=$SECTION_LIST.id output=$SECTION_LIST.type selected=`$smarty.request.mem_type`}
           			</select>		  
		  		</td>
		  		<td colspan="2"  align="right" class="naGrid1" >Items per Page: {$LIMIT_LIST}</td>
          	</tr>
		{else}
			<tr>
		  		<td colspan="2"  align="center" class="naGrid1">{if count($USER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td> 
          		<td colspan="10"  align="right" class="naGrid1">Items per Page: {$LIMIT_LIST}</td>
          </tr>
		  {/if}
        <tr>
			 <td width="27" nowrap class="naGridTitle" align="center"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.fruser,'user_id[]')"></td>
          <td width="32" nowrap class="naGridTitle" align="center"></td>
          <td width="40" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="active" display="Active"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
		  {if $SCREENAME==""}
          <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="username" display="User Name"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
	{/if}  	  
	{if $SCREENAME== 1}
	
	 <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="screen_name" display="Screen Name"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 	  
		  
		{/if}  
		  
		  <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="first_name" display="First Name"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
		  {if $NO_LNAME eq 'Y'}
		  <td width="150" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="joindate" display="Member Since"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
		  {else}
		   <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="last_name" display="Last Name"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
		  {/if}
		  <td nowrap class="naGridTitle" height="24" align="left" width="100">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="email" display="Email"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
		  	{if $smarty.request.mem_type eq 2}
		    <td width="32" nowrap class="naGridTitle" align="center">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="name" display="Store URL"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td>
			{/if}
			
		  <td nowrap class="naGridTitle" height="24" align="left" width="100">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="telephone" display="Phone"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
		  {if $smarty.request.mem_type eq 2}
		    <td nowrap class="naGridTitle" height="24" align="left" width="100">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="enddate" display="Subscription"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}</td> 
			    <td nowrap class="naGridTitle" height="24" align="left" width="100">IPN Messaage</td>
			{/if}
		 {if $CLAS_PRODUCT eq 'Y'}
		 <!--<td nowrap class="naGridTitle" height="24" align="left" width="24">Seller</td> 
         <td nowrap class="naGridTitle" height="24" align="left" width="24">Buyer</td> 
         <td nowrap class="naGridTitle" height="24" align="left" width="24">Broker</td> 
         <td nowrap class="naGridTitle" height="24" align="left" width="24">Manager</td> 
         <td nowrap class="naGridTitle" height="24" align="left" width="24">Advertiser</td> -->
		{/if}
		  
			  	{if $HEALTH_CARE eq '1'}
				<td nowrap class="naGridTitle" height="24" align="left" width="60">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="dob" display="Date of Birth"}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}</td> 
			{/if}
		  {if $ARB=='Y'}	
	          <td nowrap class="naGridTitle" align="center" width="134">&nbsp;</td>
			  {/if}
        </tr>
        {if count($USER_LIST) > 0}
			
		
        {foreach from=$USER_LIST item=user}
			
			{* <script>
				console.log({$user});
			</script> *}
        <tr class="{cycle values="naGrid1,naGrid2"}">
		  <td valign="middle" align="center"><input type="checkbox" name="user_id[]" value="{$user->id}" ></td> 
          <td valign="middle" align="center"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=user_modify&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center">{if $user->active eq 'Y' } 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$user->active}.gif"/>
			<a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=active&stat={$user->active}&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$user->active}.gif"/></a>
		{else}
			<a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=active&stat={$user->active}&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$user->active}.gif"/></a>
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$user->active}.gif"/>
		{/if}</td>
		  
		   {if $SCREENAME==""} 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=view&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&pageNo={$smarty.request.pageNo}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}">{$user->username}</a></td> 
		 {/if} 
		  {if $SCREENAME== 1}
		    <td valign="middle" height="24" align="left">{$user->screen_name}</td> 
			{/if}
		  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=view&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&pageNo={$smarty.request.pageNo}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}">{$user->first_name}</a></td> 
		   {if $NO_LNAME eq 'Y'}
		  <td valign="middle" height="24" align="left">{$user->joindate|date_format}</td> 
		  {else}
		   <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=view&id={$user->id}&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&pageNo={$smarty.request.pageNo}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}">{$user->last_name}</a></td> 
		   {/if}
		  <td width="50%" height="24" align="left" valign="middle">{$user->emailaddress}</td> 
		   
		   {if $smarty.request.mem_type eq 2}
		   <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=store pg=index}act=form&id={$user->sid}&sId=Retail Shop&fId=25{/makeLink}">{$user->storename}</a></td> 
		   {/if}
		   <td width="50%" height="24" align="left" valign="middle">{$user->phone}</td> 
		     {if $smarty.request.mem_type eq 2}
		    <td width="50%" height="24" align="left" valign="middle">{$user->subs_status}</td> 
			 <td width="50%" height="24" align="left" valign="middle"><a class="linkOneActive" href="javascript:void(0);" onclick="popupWindow('{makeLink mod=store pg=view_ipn_msg}sid={$user->id}&txn_id={$user->txn_id}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}')"  >view</a> </td> 
			{/if}
		   {if $CLAS_PRODUCT eq 'Y'}
		  <!--Commented by Salim:: This was for bayard
		  <td height="24" align="center" valign="middle">{if $user->mem_type==2} <img src="{$GLOBAL.tpl_url}/images/mem.gif">{else}<img src="{$GLOBAL.tpl_url}/images/nomember.gif">{/if}</td>
		   <td height="24" align="center" valign="middle">{if $user->mem_type==1} <img src="{$GLOBAL.tpl_url}/images/mem.gif">{else}<img src="{$GLOBAL.tpl_url}/images/nomember.gif">{/if}</td>
		   <td height="24" align="center" valign="middle">{if $user->mem_type==4 || $user->isbroker==Y } <img src="{$GLOBAL.tpl_url}/images/mem.gif">{else}<img src="{$GLOBAL.tpl_url}/images/nomember.gif">{/if}</td>
		   <td height="24" align="center" valign="middle">{if $user->mem_type==6 || $user->ispopertymanager==Y} <img src="{$GLOBAL.tpl_url}/images/mem.gif">{else}<img src="{$GLOBAL.tpl_url}/images/nomember.gif">{/if}</td>
		    <td height="24" align="center" valign="middle">{if $user->mem_type==5} <img src="{$GLOBAL.tpl_url}/images/mem.gif">{else}<img src="{$GLOBAL.tpl_url}/images/nomember.gif">{/if}</td>-->
			 {/if}
		  {if $HEALTH_CARE eq '1'}
				<td height="24" align="left" valign="middle">{$user->dob|date_format:"%m-%d-%Y"}</td>
			{/if} 
		{if $ARB=='Y'}	
          <td height="24" align="center" valign="middle">{if $user->arb_id ne ''}<a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=arb_delete&id={$user->id}&sub_id={$user->arb_id}&txtsearch={$smarty.request.txtsearch}&limit={$smarty.request.limit}{/makeLink}"onclick="javascript: return confirm('Are you sure to Unsubscribe the ARB account of this member?')">Unsubscribe ARB</a>{else}No ARB Subscription{/if}</td>
		  {/if}
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="13" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="13" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>