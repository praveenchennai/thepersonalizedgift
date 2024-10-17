<table  width="80%" align="center" border="0" cellspacing="0" cellpadding="0" > 
  <tr> 
    <td valign="top"><table width="100%"  cellspacing="0" cellpadding="0" class=naBrdr>
                <tr valign="middle">
                  <td width="79%" height="39" class="naH1">Article Details </td>
                  <td width="21%" align="right" class="blackboldtext">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" align="left" valign="top" class="blacktext">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
                      <tr>
                        <td width="100%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="22" colspan="2" align="left" valign="top" class="blacktext"><table width="100%" height="24" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                          <td width="1%" class="naGridTitle">&nbsp;</td>
                          <td width="99%" class="naGridTitle">Details</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td width="30%" align="left" valign="top"  bgcolor="#F6F5F5"  class="blacktext">&nbsp;</td>
                    <td width="70%" height="19" align="left"  bgcolor="#F6F5F5"  valign="top" class="blacktext"><span class="blacktext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></td>
                  </tr>
                  <tr>
                    <td  colspan="2" align="left" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0"  bgcolor="#F6F5F5" >
                        <tr>
                          <td width="2%" bgcolor="#F6F5F5" >&nbsp;</td>
                          <td width="24%" height="22" bgcolor="#F6F5F5" >&nbsp;&nbsp;<span class="blueboltext">Title </span></td>
                          <td width="74%" height="22"  align="left" valign="middle" class="blacktext">{$ARTICLE.album_name}</td>
                        </tr>
						<tr><td  colspan="3" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td  colspan="3">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"  bgcolor="#F6F5F5" >
						
						{foreach from=$ARTICLE.author_id item=row}
                        <tr >
                          <td width="2%">&nbsp;</td>
							
                          <td width="24%" height="22">&nbsp;&nbsp;<span class="blueboltext">Author</span></td>
                          <td width="74%" height="22" align="left" valign="middle" class="blacktext">{$row.author}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Affiliation</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.affiliation} </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Department</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.department}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Institution</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.institution}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">City</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.city}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">State</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.state}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Country</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.country}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">E-mail Address</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.email}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Type</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$row.author_type}</td>
                        </tr>
						<tr>
						  <td>&nbsp;</td>
                          <td>&nbsp;&nbsp;<span class="blueboltext">&nbsp;</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">&nbsp;</td>
                        </tr>
						{/foreach}
						</table></td>
						</tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Publish At </span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{if $ARTICLE.published=='conference'}Conference Paper {elseif $ARTICLE.published=='journal'}Journal Paper{elseif $ARTICLE.published=='book'}Paper as a book Chapter{elseif $ARTICLE.published=='report'}Report{/if}</td>
                        </tr>
						{if $ARTICLE.published=='conference'}
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Full Conference name</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_name}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Acronym</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_acronym}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Place</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_town}  {$ARTICLE.conference_id.conference_state} {$ARTICLE.conference_id.conference_country}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp; <span class="blueboltext">Date</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_year}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Sponsors</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_sponsors}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Pages</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_pages}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;Publisher</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_publisher}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;URL of the conference</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext"><span class="underline">{$ARTICLE.conference_id.url_conference}</span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;ISBN/ISSN</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_isbn </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Acceptance Rate</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_acceptance_rating}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Quality rating</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.conference_id.conference_quality_rating}</td>
                        </tr>
						{elseif ($ARTICLE.published=='journal')}
						<tr>
						  <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Full Journal name</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_name}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Acronym</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_acronym}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp; <span class="blueboltext">Date</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_year}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Pages</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_pages}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;Publisher</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_publisher}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;URL of the conference</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext"><span class="underline">{$ARTICLE.journal_id.url_journal}</span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;ISBN/ISSN</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_isbn}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Quality rating</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.journal_id.journal_quality_rating}</td>
                        </tr>
						{elseif $ARTICLE.published=='book'}
						<tr>
						  <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Book Name</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.book_id.book_name}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Author or Edited by</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.book_id.book_edited_by}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp; <span class="blueboltext">Date</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.book_id.book_year}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Pages</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.book_id.book_pages}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;Publisher</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.book_id.book_publisher}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp; &nbsp;ISBN/ISSN</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.book_id.book_isbn </td>
                        </tr>
						{elseif $ARTICLE.published=='report'}
						<tr>
						  <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Institution Name</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.report_id_institution_name}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp;&nbsp;<span class="blueboltext">Indentifier</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.report_identifier}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22">&nbsp; <span class="blueboltext">Date</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.report_year}</td>
                        </tr>
						{/if}
                        <tr>
                          <td width="2%" align="left" valign="top">&nbsp;</td>
                          <td height="44" width="24%" align="left" valign="top"><span class="blueboltext">&nbsp;&nbsp;&nbsp;Abstract</span></td>
                          <td height="26" width="74%"align="left" valign="top" class="blacktext">{$ARTICLE.abstract}&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;ISBN/ISSN </span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.isbn}</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td height="22"><span class="blueboltext">&nbsp;&nbsp;&nbsp;DOI (Digital Object Identifier)</span></td>
                          <td height="22" align="left" valign="middle" class="blacktext">{$ARTICLE.doi}</td>
                        </tr>
						<tr>
						  <td>&nbsp;</td>
						  <td height="22">&nbsp;</td></tr>
                          </table></td>
                    </table></td>
                  </tr>
                </table></td>
                          </table></td>
                      </tr>
                  </table></td> 
  </tr> 
  
</table>
</td></tr>
<tr><td height="22">&nbsp;</td></tr>
<tr><td height="22">&nbsp;</td></tr>
</table>