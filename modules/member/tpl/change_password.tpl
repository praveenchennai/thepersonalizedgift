<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="includes/datepicker/calendar.js"></script>
{literal}
<script language="javascript">
	function check()
	{
		var str1=document.frmPass.new_pass.value;
		if(str1.length<6)
		{{/literal}
			alert("{$MOD_VARIABLES.MOD_JS.JS_PASSWORD_LENGTH_SHORT}");{literal}
			document.frmPass.new_pass.focus();
			return false;
		}
		else if(document.frmPass.new_pass.value!=document.frmPass.confirm_pass.value)
		{{/literal}
			alert("{$MOD_VARIABLES.MOD_JS.JS_NEW_PASSWORD_NOT_MATCH}");{literal}
			document.frmPass.new_pass.focus();
			return false;
		}
		else
		{document.frmPass.submit();
			return true;
		}
	}
function submit_form()
{
	document.frmPass.submit();
}

</script>
{/literal}
<form name="frmPass" id="frmPass" method="post" action="" onSubmit="return check()"> 
<div style=" auto;">
	<div align="left" class="greyboldext">{$MOD_VARIABLES.MOD_HEADS.HD_CHANGE_PASSWORD}</div>
	<br>
    <div><div class="hrline"></div></div>
	<div align="center"><span class="bodytext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}&nbsp; </strong></span></div>
    <div class="bodytext" >
		<div style="float:left; padding-left:60px"  align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_OLD_PASSWORD}</div>
		<span >&nbsp;</span>
		<span><input name="old_pass" type="password" class="input" id="old_pass" size="30" /></span>
	</div><br>
	<div class="bodytext">
		<div style="float:left; padding-left:55px"  align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_NEW_PASSWORD}</div>
		<span  >&nbsp;</span>
		<span><input name="new_pass" type="password" class="input" id="new_pass" size="30" />({$MOD_VARIABLES.MOD_HINTS.HT_MINIMUM_CHAR2})</span>
	</div><br>
	<div class="bodytext">
		<div style="float:left; padding-left:5px"  align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_CONFIRM_NEW_PASSWORD}</div>
		<span  >&nbsp;</span>
		<span><input name="confirm_pass" type="password" class="input" id="confirm_pass" size="30" /></span>
	</div><br>
	<div style="margin-left:150px" class="bodytext" >
		<!--<span><input name="submit" type="submit" class="button_class" style="height:22;width:80" value="Submit" /></span>
		<span><input name="button" type="button" class="button_class" style="height:22;width:80" onClick="javascript: history.go(-1)" value="Cancel" /></span>-->
		{$SUBMITBUTTON1}<div class="space_div">&nbsp;</div>{$CANCEL}

	</div>
</div>
</form>