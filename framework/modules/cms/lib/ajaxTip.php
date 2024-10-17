<?php 
//print_r($_REQUEST);


include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms 	= 	new Cms();
/*echo "country_id: $country_id<br>";
echo "name: $name<br>";
echo "opt_name: $opt_name<br>";*/
$rs			=	$objCms->GetCMSTIPpage();


$str =  '<table width="100%"  border="0" cellspacing="0" cellpadding="0"  align="center">
<tr>
<td width="5%" rowspan="2">&nbsp;</td>
<td width="95%" class="toplink">Tip #&nbsp;&nbsp;'. $rs['title'] .'</td>
</tr>
<tr>
<td width="95%" class="blackbodytext">'. $rs['content'] .'</td>
</tr>
</table>
';

echo $str;
exit;
?>