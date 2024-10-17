<script src="{$smarty.const.SITE_URL}/includes/DragDrop/prototype.js" type="text/javascript"></script>
<script src="{$smarty.const.SITE_URL}/includes/DragDrop/scriptaculous.js" type="text/javascript"></script>
<style type="text/css">
{literal}
.handle {
	cursor:move;
}
{/literal}
</style>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0"> 
    <tr>
      <td width="3%">&nbsp;</td>
      <td width="30%" valign="top"><br /><table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">{if $smarty.request.id}Edit{else}Add{/if} Home Page Images</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top">
			<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
              <table width="100%" border="0" cellspacing="0" cellpadding="3" class="naGrid1">
                <tr>
                  <td height="5" colspan="3" align="right"><div></div></td>
                </tr>
                <tr>
                  <td align="right" height="100">Image 1 : </td>
                  <td align="center"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center">{if file_exists("`$smarty.const.SITE_PATH`/modules/cms/images/thumb/home_imgt_1.jpg")}<img src="{$smarty.const.SITE_URL}/modules/cms/images/thumb/home_imgt_1.jpg" />{/if}</td>
                  </tr>
                </table></td>
                  <td><input type="file" name="file1" />
				  <br />(Upload 260 X 675) .jpg images  only.
				  </td>
                </tr>
				<tr>
                  <td height="5" colspan="3" align="right"><div></div></td>
                </tr>
                <tr>
                  <td align="right" height="100">Image 2 : </td>
                  <td align="center"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center">{if file_exists("`$smarty.const.SITE_PATH`/modules/cms/images/thumb/home_imgt_2.jpg")}<img src="{$smarty.const.SITE_URL}/modules/cms/images/thumb/home_imgt_2.jpg" />{/if}</td>
                  </tr>
                </table></td>
                  <td><input type="file" name="file2" /> <br />(Upload 260 X 675) .jpg images  only.</td>
                </tr>
				<tr>
                  <td height="5" colspan="3" align="right"><div></div></td>
                </tr>
                <tr>
                  <td align="right" height="100">Image 3 : </td>
                  <td align="center"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center">{if file_exists("`$smarty.const.SITE_PATH`/modules/cms/images/thumb/home_imgt_3.jpg")}<img src="{$smarty.const.SITE_URL}/modules/cms/images/thumb/home_imgt_3.jpg" />{/if}</td>
                  </tr>
                </table></td>
                  <td><input type="file" name="file3" /> <br />(Upload 260 X 675) .jpg images  only.</td>
                </tr>
                <tr>
                  <td align="center" colspan="3"><input type="submit" class="naBtn" value="Save Images" /></td>
                </tr>
              </table>
            </form></td>
          </tr>
        </table>
        <br /><br /></td>
      <td width="3%">&nbsp;</td>
    </tr>
</table>
 <script type="text/javascript">
 {literal}
 // <![CDATA[
   Sortable.create("linkOrder",
	 {tag:'div',onUpdate:saveOrder,dropOnEmpty:true,containment:["linkOrder"],handle:'handle',constraint:false});
 // ]]>
 function saveOrder() {
 	document.orderFrm.Submit.style.color = "#FF0000";
 	document.orderFrm.sortOrder.value = Sortable.serialize('linkOrder');
 }
 {/literal}
 </script>