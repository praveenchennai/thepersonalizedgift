{if $smarty.session.adminSess->username eq "admin"}
	<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="admin_blog"}act=list{/makeLink}">Blogs |</a>
	<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="admin_blog"}act=settings{/makeLink}">Settings |</a>
	<a class="naSubTitle" href="{makeLink mod="`$smarty.request.mod`" pg="blogtemplate"}act=list{/makeLink}">Template</a> 
{/if}
