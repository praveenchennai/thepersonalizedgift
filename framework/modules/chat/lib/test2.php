<?php

require_once dirname(__FILE__)."/src/phpfreechat.class.php";
$params = array();
$params["title"] = "Dating";
$params["nick"] = "";
//$params["nick"] = "guest".rand(1,1000);  // setup the intitial nickname

$params["serverid"] = md5("Dating"); // calculate a unique id for this chat
$params["channel"] = "Dating";
//$params["debug"] = true;
$params["isadmin"]   = false; // uncomment this line to give admin rights to everyones
$params["admins"]    = array("jinson" => "jinson"); // login as admin and type /identify jinson to get the admin rights
$params["max_msg"] = 0;
$chat = new phpFreeChat( $params );
if($_SESSION['login_gender'] == 0 ){
    $gender = "f";
}else{
    $gender = "m";
}
$params["nickmeta"] = array('gender'=>"f");
 /*// print the current file
  echo "<h2>The source code</h2>";
  $filename = __FILE__;
  echo "<p><code>".$filename."</code></p>";
  echo "<pre style=\"margin: 0 50px 0 50px; padding: 10px; background-color: #DDD;\">";
  $content = file_get_contents($filename);
  echo htmlentities($content);
  echo "</pre>";
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Link 54 Chat</title>
  <link rel="stylesheet" title="classic" type="text/css" href="style/content.css" />  
 </head>
 <body>
<div  style="width:600px" >
  
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php $chat->printChat(); ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body></html>
