<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1">Booking Details</td>
          <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
          <td height="30" nowrap>&nbsp;</td>
          <td nowrap align="right" class="titleLink">Results per page</strong>{$LIMIT_LIST}</td>
        </tr>
      </table></td> 
  </tr>
  <tr> 
  
<td>
	
<table border=0 width=100% cellpadding="5" cellspacing="0"> 
        <tr height="30">
			<td width="12%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.date_booked" display="Booking Date"}act=booking_details{/makeLink}</td> 
			<td width="18%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T2.title" display="QuantityTitle"}act=booking_details{/makeLink}</td>
			<td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.start_date" display="Check-in"}act=booking_details{/makeLink}</td>
			<td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.end_date" display="Check-out"}act=booking_details{/makeLink}</td>
			<td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T3.username" display="Seller"}act=booking_details{/makeLink}</td>
			<td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T4.username" display="Renter"}act=booking_details{/makeLink}</td>
			<td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.amountpaid" display="RentAmount"}act=booking_details{/makeLink}</td>
        </tr>
      
			{foreach from=$BOOKING_LIST item=Booking}
				<tr class="{cycle values="naGrid1,naGrid2"}" height="25" >
					<td width="12%" nowrap align="left">{$Booking.date_booked}</td> 
					<td width="18%" nowrap  align="left">{$Booking.title|default:"NA"}</td>
					<td width="10%" nowrap  align="left">{$Booking.start_date}</td>
					<td width="10%" nowrap  align="left">{$Booking.end_date}</td>
					<td width="20%" align="left" nowrap >{$Booking.seller_username}</td>
					<td width="20%" align="left" nowrap >{$Booking.renter_username}</td>
					<td width="10%" align="right" nowrap>${$Booking.amountpaid|string_format:"%.2f"}</td>
				</tr> 
			{/foreach}
        <tr> 
          <td colspan="7" class="msg" align="center" height="30">{$NUM_PAD}</td> 
        </tr>
{if count($BOOKING_LIST) eq 0}
	<tr class="naGrid2"> 
		<td colspan="6" class="naError" align="center" height="30">No Records</td> 
	</tr>
{/if}
 </table>
 
 
	  </td> 
  </tr> 
</table>