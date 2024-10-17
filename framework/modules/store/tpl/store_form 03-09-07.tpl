<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{popup_init src="`$smarty.const.SITE_URL`/includes/overlib/overlib.js"}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript">
	var fields=new Array('username','password','confirm_pass','email2');
	var msgs=new Array('Username','Password','Confirm password','email2');
	
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')
	
{literal}
function getKeyCode(e) {
 if (window.event)
    return window.event.keyCode;
 else if (e)
    return e.which;
 else
    return null;
}
function _keyCheck(e) {
	key = getKeyCode(e);
	if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
		return true;
	else if ((key > 96 && key < 123) || (key > 47 && key < 58))
		return true;
	else
		return false;
}
function _interchange(frm, _all, flag) {
	if(flag == 'add') {
		var _from = document.getElementById("all_categories");
		var _to   = document.getElementById("store_categories");
	} else {
		var _to   = document.getElementById("all_categories");
		var _from = document.getElementById("store_categories");
	}
	if(_all) {
		for(var i = 0;i < _from.length;i++){
			_from.options[i].selected = true;
		}
	}
	for(var i = 0;i < _from.length;i++){
		if(_from.options[i].selected == true) {
			_to.options[_to.length] = new Option(_from.options[i].text, _from.options[i].value);
			_from.options[i] = null;
			i--;
		}
	}
}
function checkLength()
	{
	mem_id=document.subFrm.member_id.value;
		if(!mem_id)
		{
		if (chk(document.subFrm))
		{	 			

			var str1=document.subFrm.username.value;
			var str2=document.subFrm.password.value;
			var str3=document.subFrm.confirm_pass.value;
			if (str1.length<4)
			{
				alert("Username length is too short");
				return false;		
			}
			else if (str2.length<6)
			{
				alert("Password length is too short");
				return false;		
			}
			else if (str2!=str3)
			{
				alert("Passwords are not matching");
				return false;		
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}}else
		{return true;
		}
	}
function _submit(frm) {

	cat = document.getElementById("store_categories");
	for(var i=0; i<cat.options.length; i++) {
		cat.options[i].selected = true;
	}
	return true;
	

}
function divEnable()
{
	if(document.getElementById("newuser").style.display=='none')
	{
	 document.getElementById("newuser").style.display='block';
	 }else
	 {
	  document.getElementById("newuser").style.display='none';
	 }
}
{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
<form action="" method="POST" enctype="multipart/form-data" name="subFrm" style="margin: 0px;" onsubmit="return _submit();"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
    <tr> 
      <td nowrap class="naH1" colspan="2"><!--Store Management-->{$smarty.request.sId}</td> 
	  <td width="54%" align="right"><a href="{$smarty.const.SITE_URL}/{$STORE.name}"></a><a href="{makeLink mod="store" pg="index"}act=list{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">List {$smarty.request.sId}</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center"><span class="naError">{messageBox}</span></td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">{$smarty.request.sId} Details</span></td> 
    </tr> 
<!--
	{if $FIELDS.0==Y}
    <tr class=naGrid2>
      <td align="right" valign=top>Payment Receiver </td>
      <td width="2" valign=top>:</td>
      <td class="naGrid2">
	  <select name="payment_receiver">
        <option value="A" {if $STORE.payment_receiver=='A'} selected {/if}>Admin</option>
        <option value="S" {if $STORE.payment_receiver=='S'} selected {/if}>Store</option>
      </select>	  </td>
    </tr>
	{/if}	
	-->
	{if $FIELDS.1==Y}
    <tr class=naGrid2>
      <td align="right" valign=top>Owner</td>
      <td valign=top>:</td>
      <td class="naGrid2"><div style="float:left;" id="memberName">{$OWNER_NAME}</div>
        <input type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=newsletter pg=memberSearch}act=list{/makeLink}', 'w', 'width=700,height=500,scrollbars=yes');w.focus();" {popup text='Click to search and select the Store Owner' fgcolor='#eeddff'} />
      <input type="hidden" name="member_id" value="{$STORE.user_id|default:$STORE.member_id}" /><input type="hidden" name="email" value="" /></td>
    </tr>
	{/if}
	{if !isset($STORE.user_id)}
	 <tr class=naGrid2>
      <td colspan="3" align="center" valign=top><a href="JavaScript:w=window.open('{makeLink mod=store pg=newuser}act=list{/makeLink}', 'w', 'width=500,height=300,scrollbars=yes');w.focus();">Or Click here to create new user</a></td>
     </tr>
	{/if}
	
	{if $FIELDS.2==Y}	 <tr class=naGrid2>
      <td valign=top align="right">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top align="right">Store Name</td>
      <td valign=top>:</td>
      <td><input type="text" name="heading" value="{$STORE.heading}" class="formText" size="30" maxlength="255"></td>
    </tr>
	{/if}
	
	{if $FIELDS.3==Y}
    <tr class=naGrid2> 
      <td width=44% align="right" valign=top>{$smarty.request.sId} URL </td> 
      <td valign=top>:</td> 
      <td>{$smarty.const.SITE_URL}/
        <input type="text" name="name" value="{$STORE.name}" class="formText" size="30" maxlength="50" oncontextmenu="return false;" autocomplete="off" onkeypress="return _keyCheck(event);" />
        <br />[Accepts a-z and 0-9 only]
      (This is the name which come in the url of the store)</td> 
    </tr>
	{/if}
	 
   {if $FIELDS.4==Y}
    <tr class=naGrid2>
      <td valign=top align="right">Redirect URL </td>
      <td valign=top>:</td>
      <td><input type="text" name="redirect_url" value="{$STORE.redirect_url}" class="formText" size="30" maxlength="50" oncontextmenu="return false;" autocomplete="off" /></td>
    </tr>
	{/if}
	 {if $FIELDS.8==Y}
    <tr class=naGrid2>
      <td valign=top align="right">Category</td>
      <td valign=top>:</td>
      <td><select name="category[]" size="10" multiple style="width:200" tabindex="7">
        
        
	 {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name selected=$SELECTEDIDS.category_id}
	    
      
      </select></td>
    </tr>
	{/if}
	{if $FIELDS.9==Y}
	
	 <tr class=naGrid2>
      <td valign=top align="right">Root Store</td>
      <td valign=top>:</td>
	  <td><input type="checkbox" name="root_store" value="Y" {if $STORE.root_store != 'N'}checked{/if} /></td>
	</tr>
	
	{/if}
	
	
	{if $FIELDS.5==Y}
    <tr class=naGrid2> 
      <td valign=top align="right">Description</td> 
      <td valign=top>:</td> 
      <td><textarea name="description" cols="60" rows="7">{$STORE.description}</textarea></td> 
    </tr>
	{/if}
	
	
	{if $FIELDS.6==Y}
    <tr class=naGrid2>
      <td valign=top align="right">{$smarty.request.sId} Logo </td>
      <td valign=top>:</td>
      <td><input type="file" name="image" />
      (size= width:201 height:92)</td>
    </tr>
	{/if}
	{if $FIELDS.9==Y}
	<tr class=naGrid2>
      <td valign=top align="right">Home Page Title Name</td>
      <td valign=top>:</td>
      <td><input type="text" name="title" value="{$STORE.title}" class="formText" size="30" maxlength="255"></td>
    </tr>
	{/if}
	{if $FIELDS.10==Y}
	   <tr class=naGrid2>
      <td align="right" valign=top>Home Page Content</td>
      <td valign=top>:</td>
      <td><textarea id="html_desc" name="html_desc" rows="20" cols="50">{$STORE.html_desc}</textarea></td>
    </tr>
	{/if}
	{if file_exists("`$smarty.const.SITE_PATH`/modules/store/images/thumb/`$STORE.id`.jpg")}
    <tr class=naGrid2>
      <td valign=top align="right">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td><img src="{$GLOBAL.mod_url}/images/thumb/{$STORE.id}.jpg" /></td>
    </tr>
	{/if}
    
	{if $FIELDS.7==Y}
	<tr class=naGrid2>
      <td valign=top align="right">Active</td>
      <td valign=top>:</td>
      <td><input type="checkbox" name="active" value="Y" {if $STORE.active != 'N'}checked{/if} /></td>
    </tr>
	{/if}
	
	
	<!--
	{if $FIELDS.8==Y}
    <tr>
      <td colspan="3" class="naGridTitle"><span class="group_style">Assign {$smarty.request.sId} Categories </span></td>
    </tr>
    <tr class=naGrid2>
      <td valign=top align="right"><strong>UnAssigned Categories </strong></td>
      <td valign=top>&nbsp;</td>
      <td><strong>Assigned Categories </strong></td>
    </tr>
    <tr class="naGrid2">
      <td valign="top" align="right"><select name="all_categories[]" id="all_categories" size="7" multiple="multiple" style="width:250px;">
	  		{html_options values=$ALL_CATEGORIES.category_id output=$ALL_CATEGORIES.category_name}
        </select></td>
      <td valign="top">&nbsp;</td>
      <td><select name="store_categories[]" id="store_categories" size="7" multiple="multiple" style="width:250px;">
	  		{html_options values=$STORE_CATEGORIES.category_id output=$STORE_CATEGORIES.category_name}
      </select></td>
    </tr>
    <tr class=naGrid2>
      <td valign=top align="right"><input type="button" class="naBtn" value="Add All &raquo;" onclick="_interchange(this.form, 1, 'add')" />
      <input type="button" class="naBtn" value="Add &raquo;" onclick="_interchange(this.form, 0, 'add')" /></td>
      <td valign=top>&nbsp;</td>
      <td><input type="button" class="naBtn" value="&laquo; Remove" onclick="_interchange(this.form, 0, 'remove')" />
      <input type="button" class="naBtn" value="&laquo; Remove All" onclick="_interchange(this.form, 1, 'remove')" /></td>
    </tr>
	{/if}
	-->
	
    <tr class=naGrid2>
      <td valign=top align="right">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td>&nbsp;</td>
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> <tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>
