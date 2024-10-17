<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
{foreach from=$PROP_DETAILS item=prop}
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td width="42%" nowrap class="naH1">{$prop.prop_title|truncate:30:"..."}</td> 
          <td width="44%" align="right" nowrap><table width="92%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="10%">&nbsp;</td>
              <td width="45%"><a href="{makeLink mod=album pg=album_admin}act=upload&user_id={$prop.user_id}&propid={$smarty.request.propid}{/makeLink}">Edit/Upload Videos</a></td>
              <td width="3%">&nbsp;</td>
              <td width="42%"><a href="{makeLink mod=album pg=album_admin}act=upload_photo&user_id={$prop.user_id}&propid={$smarty.request.propid}&crt=M2{/makeLink}">Edit/Upload Photos</a></td>
            </tr>
          </table></td>
          <td width="14%" align="right" nowrap class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=propdView{/makeLink}">Property List</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		<!-- <tr>
		  <td  align="right" colspan="5" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 
        </tr> -->
        <tr>
          <td nowrap class="naGridTitle" height="24" align="left" colspan="3">Property Information</td> 
        </tr>
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
        	<td valign="middle"  height="24" align="left" width="20%">
				{if $prop.default_vdo}
					<a href="{makeLink mod=album pg=album}act=propdView&propid={$prop.id}&view=overview{/makeLink}" target="_blank"><img src="{$smarty.const.SITE_URL}/modules/album/video/thumb/{$prop.default_vdo}.jpg" border="0" class="border"></a>
				{else}
					<a href="{makeLink mod=album pg=album}act=propdView&propid={$prop.id}&view=overview{/makeLink}" target="_blank"><img src="{$GLOBAL.tpl_url}/images/hnovideo.jpg"></a>
				{/if}
			</td>
			<td valign="middle"  height="24" align="left" width="40%">
				<table>
					{if $prop.prop_city}
					<tr>
						<td width="45%">Property City</td>
						<td width="55%">: {$prop.prop_city}</td>
					</tr>
					{/if}
					{if $prop.prop_region}
					<tr>
						<td width="45%">Property Region</td>
						<td width="55%">: {$prop.prop_region}</td>
					</tr>
					{/if}
					{if $prop.prop_zip}
					<tr>
						<td width="45%">Property Zip</td>
						<td width="55%">: {$prop.prop_zip}</td>
					</tr>
					{/if}
					{if $PROP_CNTRY}
					<tr>
						<td width="45%">Property Country</td>
						<td width="55%">: {$PROP_CNTRY->country_name}</td>
					</tr>
					{/if}
				</table>
			</td> 
			<td valign="middle"  height="24" align="left" width="40%">
				<table>
					{if $prop.cont_addr1}
					<tr>
						<td width="45%">Owner Address1</td>
						<td width="55%">: {$prop.cont_addr1}</td>
					</tr>
					{/if}
					{if $prop.cont_addr2}
					<tr>
						<td width="45%">Owner Address2</td>
						<td width="55%">: {$prop.cont_addr2}</td>
					</tr>
					{/if}
					{if $prop.cont_city}
					<tr>
						<td width="45%">Owner City</td>
						<td width="55%">: {$prop.cont_city}</td>
					</tr>
					{/if}
					{if $prop.cont_state}
					<tr>
						<td width="45%">Owner State</td>
						<td width="55%">: {$prop.cont_state}</td>
					</tr>
					{/if}
					{if $prop.cont_zip}
					<tr>
						<td width="45%">Owner Zip</td>
						<td width="55%">: {$prop.cont_zip}</td>
					</tr>
					{/if}
				</table>
			</td> 
        </tr>
		<tr>
          <td nowrap class="naGridTitle" height="24" align="left" colspan="3">Property Features</td> 
        </tr>
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
			<td valign="middle"  height="24" align="left" width="30%">
				<table>
					{if $prop.year_built}
					<tr>
						<td width="45%">Year Built</td>
						<td width="55%">: {$prop.year_built}</td>
					</tr>
					{/if}
					{if $prop.lot_sqft}
					<tr>
						<td width="45%">Lot Sqft</td>
						<td width="55%">: {$prop.lot_sqft}&nbsp;ft</td>
					</tr>
					{/if}
					{if $prop.bld_sqft}
					<tr>
						<td width="45%">Building Sqft</td>
						<td width="55%">: {$prop.bld_sqft}&nbsp;ft</td>
					</tr>
					{/if}
					{if $prop.prop_bahrooms}
					<tr>
						<td width="45%">Bathrooms</td>
						<td width="55%">: {$prop.prop_bathrooms}</td>
					</tr>
					{/if}
					{if $prop.prop_bedrooms}
					<tr>
						<td width="45%">Bedrooms</td>
						<td width="55%">: {$prop.prop_bedrooms}</td>
					</tr>
					{/if}
				</table>
			</td> 
			<td valign="middle"  height="24" align="left" width="40%">
				<table>
					{if $FEATURES}
					<tr>
						<td width="45%">Features</td>
						<td width="55%">: {foreach from=$FEATURES item=feature} {$feature->category_name},{/foreach}</td>
					</tr>
					{/if}
				</table>
			</td>
			<td>&nbsp;</td>
        </tr>
		<tr>
          <td nowrap class="naGridTitle" height="24" align="left" colspan="3">Videos</td> 
        </tr>
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
			<td valign="middle"  height="24" align="left" colspan="3">
				<table>
					<tr>
					{foreach from=$VIDEO_DETAILS item=video name=foo key=index}
						<td>
							<table>	
								<tr>
									<td colspan="3" valign="top" align="center" class="smalltext2"> <a href="{makeLink mod=album pg=album}propid={$smarty.request.propid}&act=propdView&view=video{/makeLink}" target="_blank"><img src="{$smarty.const.SITE_URL}/modules/album/video/thumb/{$video.id}.jpg" border="0" class="border"></a><br>
										<a href="{makeLink mod=album pg=album}act=propdView&propid={$smarty.request.id}&view=video{/makeLink}" target="_blank">{$video.title|truncate:20:".."}</a>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="3">
										<table width="100" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43" class="smalltext2">{$DURATION[$index]}</td>
												<td width="10" class="smalltext2">|</td>
												<td width="53" class="smalltext2">views:{$video.views}</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							</table>
						</td>
						<td>
							<!-- {if $SING_V_DETAIL.cmtcnt}
								Comments : {$SING_V_DETAIL.cmtcnt}
							{else}
								&nbsp;
							{/if} -->
						</td>
						<td>&nbsp;</td>
						{if $index gt 0 and $index%5 eq 0}
							</tr>
						{/if}
					{/foreach}
					</tr>
				</table>
			</td>
        </tr>
		<tr>
          <td nowrap class="naGridTitle" height="24" align="left" colspan="3">Photos</td> 
        </tr>
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
			<td valign="middle"  height="24" align="left" colspan="3">
				<table>
					<tr>
					{foreach from=$PHOTO_DETAILS item=photo name=foo key=index}
						<td>
							<table>	
								<tr>
									<td colspan="3" valign="top" align="center" class="smalltext2">
										<a href="{makeLink mod=album pg=album}act=propdView&propid={$smarty.request.propid}&view=photo{/makeLink}" target="_blank"><img src="{$smarty.const.SITE_URL}/modules/album/photos/thumb/{$photo.id}{$PHOTO_EXT[$index]}" border="0" class="border"></a><br>
										<a href="{makeLink mod=album pg=album}act=propdView&propid={$smarty.request.propid}&view=photo{/makeLink}" target="_blank">{$photo.title|truncate:20:".."}</a>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="3">
										<table width="100" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43" class="smalltext2">&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							</table>
						</td>
						<td>
							<!-- {if $SING_V_DETAIL.cmtcnt}
								Comments : {$SING_V_DETAIL.cmtcnt}
							{else}
								&nbsp;
							{/if} -->
						</td>
						<td>&nbsp;</td>
						{if $index gt 0 and $index%5 eq 0}
							</tr>
						{/if}
					{/foreach}
					</tr>
				</table>
			</td>
        </tr>
      </table></td> 
  </tr>
  {/foreach}
</table>