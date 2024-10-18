<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>


{literal}
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		function submit_form() {
			console.log('Document Testing', document.getElementById('frmReg').action);
			console.log('Get Elements', document.getElementById('frmReg'));

			if (checkLength()) {

				document.frmReg.submit();
			}
		}

		function show_state(opt_name, country_id, state_name) {

			document.getElementById('div_state').innerHTML = "{/literal}{$MOD_VARIABLES.MOD_LABELS.LBL_LOADIND}{literal}";
			//alert(document.getElementById('country').label)
			//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
			str = "opt_name=" + opt_name + "&country_id=" + country_id + "&state_name=" + state_name + "&classname=ajaxinput";
			//alert(str);
		{/literal}
		req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}&" + Math.random());
		{literal}
			req1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			req1.send(str);
		}

		function serverRese(_var) {
			_var = _var.split('|');
			//alert(_var);
			document.getElementById('div_state').innerHTML = _var[0];
		}
		/*
	show search details 
	*/

		function checkStore(mem_type) {
		{/literal}
		var MemberType = new Array({$MEMB_TYPE|@count});

		{foreach from=$MEMB_TYPE item=memebr name=foo}
			MemberType[{$smarty.foreach.foo.index}] = new Array();
			MemberType[{$smarty.foreach.foo.index}][0] = '{$memebr.id}';
			MemberType[{$smarty.foreach.foo.index}][1] = '{$memebr.PackageExists}';
		{/foreach}

		{literal}

			for (i = 0; i < MemberType.length; i++) {
				if (MemberType[i][0] == mem_type && MemberType[i][1] == 'No') {
					document.getElementById('div_reg').style.display = 'none';
					document.getElementById('div_sub').style.display = 'none';
					return false;
				}
			}

			if (document.frmReg.mem_type.selectedIndex == 2) {
				//document.getElementById("store_div").style.display="inline";
			} else {
				document.getElementById("store_div").style.display = "none";
			}
			if (document.frmReg.mem_type.selectedIndex == 0) {
				document.getElementById("div_reg").style.display = "none";
				document.getElementById("div_sub").style.display = "none";
			} else {
				document.getElementById("div_reg").style.display = "block";
				document.getElementById("div_sub").style.display = "block";
			}
			//mem_type
			// document.getElementById('reg_div').innerHTML = "<strong class='lbClass'>Loading...</strong>";

			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverResponse4);

		{/literal}
		str = "mem_type=" + mem_type + "&selected={$smarty.request.reg_pack}";
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=reg_pack{/makeLink}&" + Math.random());
		{literal}
			req1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			req1.send(str);
		}

		function serverResponse4(result) {
			result = result.split('~');
			document.getElementById('reg_div').innerHTML = result[0];
		{/literal}
		var reg_pack_id = "{$smarty.request.reg_pack}";
		{literal}

			if (result[1] == 0) {
				document.getElementById("div_reg").style.display = "none";
				document.getElementById("div_sub").style.display = "none";
				document.frmReg.btn_save.value = "{/literal}{$MOD_VARIABLES.MOD_LABELS.LBL_REGISTER}{literal}";
				document.frmReg.txt_payment.value = "N";
			} else {
				document.getElementById('sub_div').innerHTML =
					"<select name='sub_pack' id='sub_type' class='input' style='width:180px'></select>";
				document.frmReg.btn_save.value = "{$MOD_VARIABLES.MOD_LABELS.LBL_CONTINUE}";
				document.frmReg.txt_payment.value = "Y";
			}
			if (reg_pack_id != "") {
				loadSub(reg_pack_id);
			}
		}

		function loadSub(reg_pack) {
			//mem_type
			document.getElementById("div_sub").style.display = "block";
			document.getElementById('sub_div').innerHTML =
				"<strong class='lbClass'>{/literal}{$MOD_VARIABLES.MOD_LABELS.LBL_LOADIND}{literal}</strong>";
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverResponse5);

		{/literal}
		str = "reg_pack=" + reg_pack + "&selected={$smarty.request.sub_pack}";;
		//alert("{$smarty.request.sub_pack}")
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=sub_pack{/makeLink}&" + Math.random());
		{literal}
			req1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			req1.send(str);
		}

		function serverResponse5(result) {

			result = result.split('~');
			document.getElementById('sub_div').innerHTML = result[0];
			if (result[1] == 0) {
				document.getElementById("div_sub").style.display = "none";
			}

		}

		function validStore(store_name) {
			var str1 = document.frmReg.store_name.value
			if (str1.length >= 5) {
			{/literal}
			document.getElementById("msg_div").innerHTML =
				"<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>{$MOD_VARIABLES.MOD_LABELS.LBL_CHECKING}</strong>";
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverResponse1);
			str = "store_name=" + store_name;

			req1.open("POST", "{makeLink mod=member pg=ajax_store}act=valid_store{/makeLink}&" + Math.random());
			{literal}
				req1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				req1.send(str);
			} else {
				document.getElementById("msg_div").innerHTML =
					"<strong class='lbClass'>{/literal}{$MOD_VARIABLES.MOD_LABELS.LBL_MINIMUM_STORE_CHAR}{literal}</strong>";
				document.frmReg.valStr.value = "false";
			}
		}

		function serverResponse1(_var) {
			document.getElementById('msg_div').innerHTML = _var;
		}



		function validEmail(email) {
			if (emailCheck(email)) {
			{/literal}
			document.getElementById("email_div").innerHTML =
				"<strong style='color:#990000'>{$MOD_VARIABLES.MOD_LABELS.LBL_CHECKING}</strong>";
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverResponse2);

			str = "email=" + email;
			{if $GLOBAL.store_id ne ''}
				str = str + "&store_id=" + {$GLOBAL.store_id};
			{else}
				str = str + "&store_id=0";
			{/if}
			//prompt("s",str);
			req1.open("POST", "{makeLink mod=member pg=ajax_store}act=valid_email_with_store{/makeLink}&" + Math.random());
			{literal}
				req1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				req1.send(str);
			} else {
			{/literal}
			document.getElementById("email_div").innerHTML =
				"<font color='red'><strong class='redClass'>{$MOD_VARIABLES.MOD_LABELS.LBL_INVALID_EMAIL}</strong></font>";
			{literal}
			}
		}

		function serverResponse2(_var) {
			_var = _var.split('|');
			document.getElementById('email_div').innerHTML = _var[0];
			if (_var[1] == '1') {
				document.getElementById("email_main").style.height = "40px";
			} else {
				document.getElementById("email_main").style.height = "15px";
			}
		}

		function validUname(uname) {
			if (uname.length > 3) {
			{/literal}
			document.getElementById("uname_div").innerHTML =
				"<strong style='color:#990000'>{$MOD_VARIABLES.MOD_LABELS.LBL_CHECKING}</strong>";
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverResponse3);
			str = "uname=" + uname;
			{if $GLOBAL.store_id ne ''}
				str = str + "&store_id=" + {$GLOBAL.store_id};
			{else}
				str = str + "&store_id=0";
			{/if}

			req1.open("POST", "{makeLink mod=member pg=ajax_store}act=valid_uname{/makeLink}&" + Math.random());
			{literal}
				req1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				req1.send(str);
			} else {
			{/literal}
			document.getElementById("uname_div").innerHTML =
				"<font color=''><strong class='lbClass'>({$MOD_VARIABLES.MOD_HINTS.HT_MINIMUM_CHAR1})</strong></font>";
			{literal}
			}
		}

		function serverResponse3(_var) {
			document.getElementById('uname_div').innerHTML = _var;
		}
	</SCRIPT>
{/literal}

{if ($smarty.request.id=="")}

	{literal}
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
			var fields = new Array('first_name', 'last_name', 'email', 'username', 'password', 'confirm_pass', 'address1', 'city',
				'country', 'postalcode', 'telephone');
			var msgs = new Array('First Name', 'Last Name', 'Email', 'Username', 'Password', 'Confirm Password', 'Address', 'City',
				'Country', 'Zip Code', 'Telephone');

			var emails = new Array('email');
			var email_msgs = new Array('Invalid Email')


			function checkLength() {

				if (chk(document.frmReg)) {
					var str1 = document.frmReg.username.value;
					var str2 = document.frmReg.password.value;
					var str3 = document.frmReg.confirm_pass.value;
					if (str1.length < 4) {
					{/literal}
					alert("{$MOD_VARIABLES.MOD_JS.JS_USERNAME_SHORT}");
					{literal}
						return false;
					} else if (str2.length < 6) {
					{/literal}
					alert("{$MOD_VARIABLES.MOD_JS.JS_PASSWORD_LENGTH_SHORT}");
					{literal}
						return false;
					} else if (str2 != str3) {
					{/literal}
					alert("{$MOD_VARIABLES.MOD_JS.JS_NEW_PASSWORD_NOT_MATCH}");
					{literal}
						return false;
					} else {
						return true;
					}
				} else {
					return false;
				}
			}
		</SCRIPT>
	{/literal}

{else}

	{literal}
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
			var fields = new Array('first_name', 'last_name', 'email', 'address1', 'city', 'country', 'postalcode', 'telephone');
			var msgs = new Array('First Name', 'Last Name', 'Email', 'Address', 'City', 'Country', 'Zip Code', 'Telephone');

			var emails = new Array('email');
			var email_msgs = new Array('Invalid Email')

			var nums = new Array('postalcode');
			var nums_msgs = new Array('Postal Code should be a number');

			function checkLength() {

				if (chk(document.frmReg)) {
					if (document.frmReg.id.value == '') {
						var str1 = document.frmReg.username.value;
						var str2 = document.frmReg.password.value;
						var str3 = document.frmReg.confirm_pass.value;
						if (str1.length < 4) {
						{/literal}
						alert("{$MOD_VARIABLES.MOD_JS.JS_USERNAME_SHORT}");
						{literal}
							return false;
						} else if (str2.length < 6) {
						{/literal}
						alert("{$MOD_VARIABLES.MOD_JS.JS_PASSWORD_LENGTH_SHORT}");
						{literal}
							return false;
						} else if (str2 != str3) {
						{/literal}
						alert("{$MOD_VARIABLES.MOD_JS.JS_NEW_PASSWORD_NOT_MATCHs}");
						{literal}
							return false;
						} else {
							return true;
						}
					} else {
						return true;
					}
				} else {
					return false;
				}
			}
		</SCRIPT>
	{/literal}
{/if}

<form name="frmReg" id="frmReg" method="post" action="" onSubmit="return submit_form()">
	<div style="width:auto; ">
		<div class="greyboldext">
			{if $smarty.request.id}{$MOD_VARIABLES.MOD_HEADS.HD_ACCOUNT_DET}{else}{$MOD_VARIABLES.MOD_HEADS.HD_REGISTRATION}{/if}
		</div>
		<br>
		<div class="hrline"></div>
		<div><span class="smalltext" style="color:#FF0000;"><strong><br>{messageBox}&nbsp; </strong></span></div>
		<div class="smalltext"><strong> {$MOD_VARIABLES.MOD_COMM.COMM_CMN_REQ_FIELD}</strong></div>
		<br>
		<div class="bodytext">{messageBox}</div><br>

		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_FIRST_NAME} *</span>
			<span id="sub_div" class="formw">
				<input name="first_name" type="text" class="input" id="first_name" value="{$smarty.request.first_name}"
					size="30" />
				<input type="hidden" name="store_id" value="{$STORE_ID}">
			</span>
		</div><br>
		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_LAST_NAME} *</span>
			<span id="sub_div" class="formw">
				<input name="last_name" type="text" class="input" id="last_name" value="{$smarty.request.last_name}"
					size="30" />

			</span>
		</div><br>
		<div class="row" id="email_main">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_EMAIL_ADDRESS} *</span>
			<span id="sub_div" class="formw" style="width:80%">
				<input name="email" type="text" class="input" id="email" value="{$smarty.request.email}" size="30"
					{if ($smarty.request.id!="")} readonly {else} onBlur="validEmail(this.value)" {/if} /> &nbsp;
				<span id="email_div">&nbsp;</span>

			</span>
		</div><br>
		{if ($smarty.request.id=="")}
			<div class="row">
				<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_USER_NAME}: *</span>
				<span id="sub_div" class="formw">
					<input name="username" type="text" class="input" id="username" value="{$smarty.request.username}"
						size="30" onBlur="validUname(this.value)" />
					<span id="uname_div"> <strong
							class="formw">({$MOD_VARIABLES.MOD_HINTS.HT_MINIMUM_CHAR1})</strong></span>
				</span>
			</div><br>

			<div class="row">
				<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_PASSWORD}: *</span>
				<span id="sub_div" class="formw">
					<input name="password" type="password" class="input" id="password" size="30" />
					<strong class="formw">({$MOD_VARIABLES.MOD_HINTS.HT_MINIMUM_CHAR2})</strong>
				</span>
			</div><br>
			<div class="row">
				<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_REPASSWORD} *</span>
				<span id="sub_div" class="formw">
					<input name="confirm_pass" type="password" class="input" id="confirm_pass" size="30" />
				</span>
			</div><br>



		{/if}
		<!--<div class="row">
		<span class="label">Secret Question: *</span>
		<span id="sub_div" class="formw">
		<select name="sec_qn" class="input" id="sec_qn" style="width:195px ">
                        <option value="">---Select a secret question---</option>                        
								{html_options values=$QN_LST.id output=$QN_LST.qn selected=$smarty.request.sec_qn}
                        </select>
  	 </span>
	</div><br>
	<div class="row">
		<span class="label">Secret Answer: *</span>
		<span id="sub_div" class="formw">
		<input name="sec_ans" type="text" class="input" id="sec_ans" value="{$smarty.request.sec_ans}" size="30" />
  	 </span>
	</div><br>

		<div class="row">
		<span class="label">Gender:</span>
		<span id="sub_div" class="label">
		<input name="gender" type="radio" class="checkbox" value="male" {if $smarty.request.gender=='male'} checked {/if} />Male
		<input name="gender" type="radio" class="checkbox" value="female" {if $smarty.request.gender=='female'} checked {/if} />Female
  	 </span>
	</div><br>-->
		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_ADDRESS} *</span>
			<span id="sub_div" class="formw">
				<input type="text" name="address1" class="input" id="address1" size="30"
					value="{$smarty.request.address1}" />
			</span>
		</div><br>
		<div class="row">
			<span class="label">&nbsp;</span>
			<span id="sub_div" class="formw">
				<input type="text" name="address2" class="input" id="address2" size="30"
					value="{$smarty.request.address2}" />
			</span>
		</div><br>
		<div class="row">
			<span class="label">&nbsp;</span>
			<span id="sub_div" class="formw">
				<input type="text" name="address3" class="input" id="address3" size="30"
					value="{$smarty.request.address3}" />
			</span>
		</div><br>
		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_CITY} *</span>
			<span id="sub_div" class="formw">
				<input name="city" type="text" class="input" id="city" value="{$smarty.request.city}" size="30" />
			</span>
		</div><br>
		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_COUNTRY} *</span>
			<span id="sub_div" class="formw"><span id="sub_div" class="formw">
					<select name="country" class="ajaxinput" id="country"
						onChange="javascript:show_state('state',this.value,'');">
						{if $smarty.request.country==""}
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=840}
						{else}
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
						{/if}
					</select>
				</span> </span>
		</div>
		<br>


		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_STATE}</span>
			<span id="sub_div" class="formw">
				<div id="div_state" class="bodytext"><input name="state" type="text" class="ajaxinput" id="state"
						value="{$smarty.request.state}" /></div>
			</span>

		</div><br>

		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_ZIP} *</span>
			<span id="sub_div" class="formw">
				<input name="postalcode" type="text" class="input" id="postalcode" value="{$smarty.request.postalcode}"
					size="30" />
			</span>

		</div><br>

		<div class="row">
			<span class="label">{$MOD_VARIABLES.MOD_LABELS.LBL_PHONE} * </span>
			<span id="sub_div" class="formw">
				<input name="telephone" type="text" class="input" id="telephone" value="{$smarty.request.telephone}"
					size="30" />
			</span>

		</div><br>

		<!--<div class="row">
		<span class="label">Mobile:</span>
		<span id="sub_div" class="formw">
		<input name="mobile" type="text" class="input" id="mobile" value="{$smarty.request.mobile}" size="30" />
  	 </span>

	</div><br>-->


	</div><br>
	<div class="row">
		<span class="label">&nbsp;</span>
		<span id="sub_div" class="formw">
			{* <input type="submit" class="button_class" value="Submit" style="height:22;width:80" />
			<input type="button" class="button_class" value="Cancel" style="height:22;width:80"
				onClick="javascript: history.go(-1)" /> *}
			{$SUBMITBUTTON1} <div class="space_div">&nbsp;</div>{$CANCEL}
		</span>

	</div><br>


	</div>
	<input name="id" type="hidden" class="hidden" id="id" value="{$smarty.request.id}" size="30" />
	{if $smarty.request.country==""}
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
			show_state('state', document.frmReg.country.value, '{$smarty.request.state}');
		</SCRIPT>
	{/if}
</form>