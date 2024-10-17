{literal} 
<script language="javascript">
	function chkFrom()
	{
		var status=0;
		var i=0;
		
		if(document.pForm.delId.length  > 0)
		{
			for( i=0; i <document.pForm.delId.length; i++){
				if(document.pForm.delId[i].checked==true){
					 status++;
				}
			}
		}
		
		if(status==1)
		{
			document.pForm.frmAction.value='update';
			document.pForm.submit();
		}
		else 
		{
			alert("Please select one.");
			return false; 
		}
		
	}
function chkUpdate()
{
	document.pForm.frmAction.value='update';
	document.pForm.submit();
}	
function createNew()
{
	document.pForm.frmAction.value='insert';
	document.pForm.submit();
}	

</script>
{/literal}
  <form action="" method=post enctype="multipart/form-data" name="pForm" id=pForm >
    <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
      <tr>
        <td>{messageBox}</td>
      </tr>
    </table>
    <table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
    <tr>
        <td></td>
      </tr> 
      <tr> 
        <td><table width="98%" align="center"> 
            <tr> 
              <td nowrap class="naH1">Add Book </td> 
              <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=list_book{/makeLink}">List books</a></td> 
            </tr> 
        </table></td> 
      </tr> 
      <tr> 
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#F6F5F5" >
                <tr>
                  <td ><table width="100%" height="24" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td width="1%" class="naGridTitle">&nbsp;</td>
                            <td width="99%" class="naGridTitle">&nbsp;</td>
                          </tr>
                      </table></td>
                </tr>
                <tr>
                  <td align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="10">
				    {if isset($MESSAGE)}
				    <tr class=naGrid2>
					    <td valign=top colspan=3 align="center">
					    <span class="naError">{$MESSAGE}</span>
				      </td>
				    </tr>
			        {/if} 
                    <tr>
                      <td align="center" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0" >
                        <tr>
                          <td width="31%" align="right"><span class="blacktext">Book Title*</span></td>
                          <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td width="64%" height="30" colspan="3"><input name="book_title" type="text" class="inputblue" size="47" value="{$BOOK.book_title}"></td>
                        </tr>
						<tr>
                        <td width="31%" align="right">Tags</td>
                        <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td width="64%" height="30" colspan="3"><textarea name="book_title_tag" class="inputblue" style="width:300px; height:100px" >{$BOOK.book_title_tag}</textarea></td>
					    </tr>
                        <tr>
                          <td align="right"><span class="blacktext">Author or Edited by*</span></td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="book_author" id="acronym" type="text" class="inputblue" size="47" value="{$BOOK.book_author}"></td>
                        </tr>
						<tr>
                        <td width="31%" align="right">Tags</td>
                        <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td width="64%" height="30" colspan="3"><textarea name="book_author_tag" class="inputblue" style="width:300px; height:100px" >{$BOOK.book_author_tag}</textarea></td>
					    </tr>
                        <tr>
                          <td align="right" class="blacktext">Date *</td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="11%" height="30"><span class="blacktext">Month</span></td>
                                <td width="21%"><select name="book_month" class="inputblue">
                           		      <option value="1" {if ( isset($BOOK)&& $BOOK.book_month=='1')}selected{/if}>January</option>
								      <option value="2" {if ( isset($BOOK)&& $BOOK.book_month=='2')}selected{/if}>February</option>
								      <option value="3" {if ( isset($BOOK)&& $BOOK.book_month=='3')}selected{/if}>March</option>
								      <option value="4" {if ( isset($BOOK)&& $BOOK.book_month=='4')}selected{/if}>April</option>
								      <option value="5" {if ( isset($BOOK)&& $BOOK.book_month=='5')}selected{/if}>May</option>
								      <option value="6" {if ( isset($BOOK)&& $BOOK.book_month=='6')}selected{/if}>June</option>
								      <option value="7" {if ( isset($BOOK)&& $BOOK.book_month=='7')}selected{/if}>July</option>
								      <option value="8" {if ( isset($BOOK)&& $BOOK.book_month=='8')}selected{/if}>August</option>
								      <option value="9" {if ( isset($BOOK)&& $BOOK.book_month=='9')}selected{/if}>September</option>
								      <option value="10" {if ( isset($BOOK)&& $BOOK.book_month=='10')}selected{/if}>October</option>
								      <option value="11"{if ( isset($BOOK)&& $BOOK.book_month=='11')}selected{/if}>November</option>
								      <option value="12"{if ( isset($BOOK)&& $BOOK.book_month=='12')}selected{/if}>december</option>
                                </select></td>
                                <td width="9%"><span class="blacktext">Year*</span></td>
                                <td width="59%"><select name="book_year" class="inputblue">
                              {foreach from=$YEAR_LIST item=year name=loop2}
							 {if (isset($BOOK.book_year))}
							  {html_options values=$year output=$year selected=$BOOK.book_year}
							  {else}
                              {html_options values=$year output=$year selected=$NOW}
							  {/if}
							  {/foreach} 
                            </select></td>
                              </tr>
                          </table></td>
                        </tr>
				         <tr>
                           <td align="right" class="blacktext">Publisher*</td>
                           <td align="center" valign="middle"><span class="blacktext">:</span></td>
                           <td height="30" colspan="3"><input name="book_publisher" type="text" class="inputblue" size="25" value="{$BOOK.book_publisher}"></td>
                        </tr>
						<tr>
                        <td width="31%" align="right">Tags</td>
                        <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td width="64%" height="30" colspan="3"><textarea name="book_publisher_tag" class="inputblue" style="width:300px; height:100px" >{$BOOK.book_publisher_tag}</textarea></td>
					    </tr>
				        <tr>
                          <td align="right" class="blacktext">URL of the book </td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="book_url" type="text" class="inputblue" size="47" value="{$BOOK.book_url}"></td>
                        </tr>
				        <tr>
                          <td align="right" class="blacktext">ISBN/ISSN</td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="book_isbn" id="book_isbn" type="text" class="inputblue" size="47" value="{$BOOK.book_isbn}"></td>
                        </tr>
				        <tr>
				 	      <td>&nbsp;</td>
				        </tr>
				        <tr > 
				  		      <td>&nbsp;</td>
						      <td>&nbsp;</td>
					       <td  valign=center><input type=submit value="Submit" class="naBtn">&nbsp;<input type=reset value="Reset" class="naBtn"></td> 
				        </tr> 
				        <tr>
				 	      <td>&nbsp;</td>
				        </tr>
				        </table></td>
                    </tr>
                    </table>
			      </td>
                </tr>
       </table></td> 
      </tr> 
    </table>
  </form>

