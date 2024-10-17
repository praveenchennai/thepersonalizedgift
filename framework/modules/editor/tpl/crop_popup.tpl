<link type="text/css" rel="stylesheet" href="{$smarty.const.SITE_URL}/includes/css/debug.css" media="screen, projection" />
<link type="text/css" rel="stylesheet" href="{$smarty.const.SITE_URL}/includes/css/cropper.css" media="screen, projection" />
<link type="text/css" rel="stylesheet" href="{$smarty.const.SITE_URL}/includes/css/imig.css" media="screen, projection" />
{literal}
<script src="{$smarty.const.SITE_URL}/includes/lib/prototype.js" type="text/javascript"></script>	
<script src="{$smarty.const.SITE_URL}/includes/lib/scriptaculous.js?load=builder,dragdrop" type="text/javascript"></script>
<script src="{$smarty.const.SITE_URL}/includes/lib/cropper.js" type="text/javascript"></script>
<script src="{$smarty.const.SITE_URL}/includes/lib/init_cropper.js" type="text/javascript"></script>
{/literal}
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			
							  <tr>
                                <td height="45" valign="middle" class="greyboldext" colspan="3">Art</td>
                              </tr>
                              <tr>
                                <td height="2" valign="top"  colspan="3"><hr size="1"  class="border1"/></td>
                              </tr>
              <tr>              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top">
				<div id="crop_save">
				<form action="" method="post" class="frmCrop">
				  <fieldset>
					<legend>Continue</legend>
					<input type="hidden" class="hidden" name="imageWidth" id="imageWidth" value="<?php echo $dimensions['width'] ?>" />
					<input type="hidden" class="hidden" name="imageHeight" id="imageHeight" value="<?php echo $dimensions['height'] ?>" />
					<input type="hidden" class="hidden" name="imageFileName" id="imageFileName" value="<?php echo $imageFileName ?>" />
					<input type="hidden" class="hidden" name="cropX" id="cropX" value="0" />
					<input type="hidden" class="hidden" name="cropY" id="cropY" value="0" />
					<input type="text"  name="cropWidth" id="cropWidth" value="<?php echo $dimensions['width'] ?>" />
					<input type="text"  name="cropHeight" id="cropHeight" value="<?php echo $dimensions['height'] ?>" />
					<div id="submit">
					  <input type="submit" value="Save" name="save" id="save" />
					</div>
				  </fieldset>
				</form>
			  </div> <!-- /crop_save -->
			
			  <div id="crop">
				<div id="cropWrap">
				  <img src="{$imageLocation}" alt="Image to crop" id="cropImage" />
				</div> <!-- /cropWrap -->
			  </div>
				</td>
              </tr>
		
</table>
