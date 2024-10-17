<?php


print ($_REQUEST['vars']);

$vals = array(31,12,24,11,20,10,-25);






?>
<form name="frm1" action="Form.php?vars=<?=implode(",",$vals)?>" method="post">
  <input type="submit" name="Submit" value="Submit">
</form>