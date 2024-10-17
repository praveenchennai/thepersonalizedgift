{literal}
<Script language="javascript" type="text/javascript">
	
	function showImage(index,val)
	{
{/literal}
		var i =0;
		var image = Array();
		var imageDelete = Array();
		var imageId = Array();
		var imageTitle = Array();
		var defaultImg = Array();
		
		{foreach from = $PHOTO_LIST item=photo key =index}
			image[{$index}] = '<image src="{$smarty.const.SITE_URL}/modules/album/photos/thumb/{$photo.id}{$photo.img_extension}?{$smarty.now}">';
			imageDelete[{$index}] = '<a href="{makeLink mod=album pg=album_admin}act=delete&photoid={$photo.id}&propid={$smarty.request.propid}&crt=M2{/makeLink}"><image src="{$smarty.const.SITE_URL}/templates/blue/images/grid/icon.delete-bk.gif" border="0"></a>';
			imageId[{$index}] = '<input type="hidden" name="photoid[]" value="{$photo.id}">';
			imageTitle[{$index}] = '{$photo.title}';
			defaultImg[{$index}] = '<input name="default_img" type="radio" value="{$photo.id}" {if $photo.id ==   $PROP_DETAILS[0].default_img} checked {/if}>'+"Default image";
		{/foreach}
{literal}
		if(val==0)
		{
			if (index <=image.length-1)
			document.write(image[index]);
		}
		else if(val ==1)
		{
			if (index <=imageDelete.length-1)
			document.write(imageDelete[index]);
		}
		else if(val ==2)
		{
			if (index <=imageId.length-1)
			document.write(imageId[index]);
		}
		else if(val ==3)
		{
		
			if (index <=defaultImg.length-1)
			document.write(defaultImg[index]);
		}
		else
		{
			for(i=0;i<imageTitle.length;i++)
			{
				document.getElementById("title_"+i).value = imageTitle[i];
			}
		}
	}
	/*
	function showImage(index)
	{
		var imageDelete = Array();
{/literal}
		{foreach from = $PHOTO_LIST item=photo key =index}
			imageDelete[{$index}] = '<a href="{makeLink mod=album pg=photo}{/makeLink}"><image src="{$smarty.const.SITE_URL}/templates/blue/images/grid/icon.delete-bk.gif" border="0"></a>';
		{/foreach}
{literal}
		if (index <=imageDelete.length-1)
		return imageDelete[index];

	}
	*/
	
</Script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class="naBrdr"> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td width="47%" nowrap class="naH1">Upload Video</td> 
          <td width="53%" align="right" nowrap class="titleLink"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="18%">&nbsp;</td>
              <td width="32%"><a href="{makeLink mod=album pg=album_admin}act=propdView&propid={$smarty.request.propid}{/makeLink}">View this property </a></td>
              <td width="2%">&nbsp;</td>
              <td width="26%"><a href="{makeLink mod=album pg=album_admin}act=edit_property&propid={$smarty.request.propid}&user_id={$smarty.request.user_id}{/makeLink}">Edit Property </a></td>
              <td width="1%">&nbsp;</td>
              <td width="21%"><a href="{makeLink mod=album pg=album_admin}act=propdView{/makeLink}">Property List</a></td>
            </tr>
          </table>            <!-- <a href="{makeLink mod=member pg=user}act=sub_form{/makeLink}">Add New</a> --></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td>
<form name="form1" method="post" action="" enctype="multipart/form-data" style="border:0px">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" class="tdHeight"></td>
  </tr>
  <tr>
    <td align="center" >
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="naGridTitle" style="padding-left:5px;height:20">{$PROP_DETAILS[0].prop_title}</td>
      </tr>
    </table>
	</td>
  </tr>
  <tr class="naGrid1">
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">{messageBox}</td>
  </tr>
  <tr>
    <td>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr class="naGrid1">
        <td>
		<table width="83%"  border="0" align="center" cellpadding="0" cellspacing="1">
		  <tr>
            <td align="center" style="height:5px"></td>
          </tr>
		{section name=photo loop=10 start=0 max=10}
          <tr>
            <td align="center">
			<table width="100%"  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="right" class="normaltext">Title{$smarty.section.photo.index+1} <span class="fullColen">:</span></td>
                <td align="center" class="normaltext">&nbsp;</td>
                <td align="left"><input name="title[]" type="text" class="input" id="title_{$smarty.section.photo.index}"  style="width:250"></td>
                <td width="1%" rowspan="3" valign="top"  style="text-align:center"><script>showImage({$smarty.section.photo.index},2)</script></td>
                <td width="32%" rowspan="3" valign="middle" style="text-align:center"><script>showImage({$smarty.section.photo.index},0)</script></td>
                <td width="1%" rowspan="3" align="right" valign="middle"  style="text-align:center"><script>showImage({$smarty.section.photo.index},1)</script></td>
              </tr>
              <tr>
                <td colspan="3" align="center" class="normaltext"><script>showImage({$smarty.section.photo.index},3)</script></td>
              </tr>
              <tr >
                <td width="29%" align="right" class="normaltext">Photo{$smarty.section.photo.index+1} <span class="fullColen">:</span></td>
                <td width="1%" align="right" class="normaltext">&nbsp;</td>
                <td width="36%" align="left"><input type="file" name="photoImg[]" class="input" size="33"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" style="height:15px"></td>
          </tr>
		  {/section}
        </table>
		</td>
      </tr>
      <tr>
        <td align="center"><table width="18%" height="21"  border="0" cellpadding="0" cellspacing="1">
          <tr>
            <td width="45%" align="center"><input type="submit" value="Upload" class="naBtn"></td>
            <td width="7%">&nbsp;</td>
            <td width="48%" align="center"><input type="button" value="Cancel" onClick="window.location.href='{makeLink mod=album pg=album_admin}act=propdView&propid={$smarty.request.propid}{/makeLink}'" class="naBtn"></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</td>
  </tr>
</table>
</form>
</td>
</tr>
</table>
<script>
showImage();
</script>
