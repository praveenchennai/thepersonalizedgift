{if $smarty.session.adminSess->username eq "admin"}
	<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="module"}act=list{/makeLink}">Modules</a> |
	<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="admin"}act=list{/makeLink}">Admin Users</a> |
{/if}
<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="change_password"}{/makeLink}">Change Password</a> |
<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="set_email"}{/makeLink}">Set Email Address</a>