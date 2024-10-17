<table width="100%"  border="0">
  <tr>
    <td>
	<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
 	<tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">Gift Certificate Details </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=certificate}act=list{/makeLink}">Certificate List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4></td>
    </tr>
    {/if}   
    <tr class=naGrid2>
      <td  align="right" valign=top>Number</td>
      <td valign=top>:</td>
      <td colspan="2" align="left">{$CERTIFICATE_DETAILS.certi_number}</td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top> Start Date </td> 
      <td width=3 valign=top>:</td> 
      <td colspan="2" align="left">{$CERTIFICATE_DETAILS.certi_startdate}
    </td>
    </tr>
   <tr class=naGrid2>
     <td align="right" valign=top> Expiry Date </td>
     <td valign=top>:</td>
     <td colspan="2" align="left">{$CERTIFICATE_DETAILS.certi_enddate}</td>
  </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Amount Type  </td>
     <td valign=top>:</td>
     <td width="128" align="left">  Amount</td>
	 <td width="166" align="left">{$CERTIFICATE_DETAILS.certi_amount}</td>
   </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Options</td>
     <td valign=top>:</td>
     <td align="left">
		{if $CERTIFICATE_DETAILS.type_option=='one'} One Time use
		{elseif $CERTIFICATE_DETAILS.type_option=='unlimit'} Unlimited use
		{else}Fixed{/if}
	 </td>	   
     <td align="left">
	 {if $CERTIFICATE_DETAILS.type_option=='fixed'}
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="46%">No of Times</td>
         <td width="54%">{$CERTIFICATE_DETAILS.no_times}</td>
       </tr>
     </table>
	 {/if}
	 </td>
   </tr>
   <tr class=naGrid2>
     <td width="230" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>&nbsp;</td> 
    </tr>  
</table>
</td>
  </tr>
</table>