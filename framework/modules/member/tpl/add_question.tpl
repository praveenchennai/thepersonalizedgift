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
              <td nowrap class="naH1">Add Question </td> 
              <td nowrap align="right" class="titleLink"><a href="{makeLink mod=member pg=user}act=profile_question_list{/makeLink}">List question </a></td> 
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
							<td width="31%" align="right">Profile Question </td>
							<td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
							<td width="64%" height="30" colspan="3"><textarea name="question" class="inputblue" style="width:295px; height:100px" >{$OPTION.question	}</textarea></td>
					    </tr>
				        <tr >
				          <td>&nbsp;</td>
				          <td>&nbsp;</td>
				          <td  valign=center>&nbsp;</td>
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

