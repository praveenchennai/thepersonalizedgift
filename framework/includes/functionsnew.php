<?php

include_once(FRAMEWORK_PATH."/includes/class.message.php");
function authorize() {
	if(!$_SESSION['adminSess'] && $_REQUEST['pg']!="login") {
		redirect(makeLink(array("mod"=>"admin", "pg"=>"login")));
		exit;
	}
}
/*
used to get the sunmenu for each module
*/
function getSubmenu($module_id=0)
{
	$sql	=	"select menu,link from module_menu where module_id=$module_id and active='Y' ORDER BY display_order";
	//echo $sql;
	$Array	=	array();
	if($module_id>0)
	{
		$rs		=	mysql_query($sql);
		while($row=mysql_fetch_array($rs))
		{
			$value	=	$row["menu"];
			$key	=	$row["link"];
			$Array[$key]=$value;
		}
	}
	return $Array;
}

//Emai validation
function  checkEmail($email)
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.

		return false;
	}

	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {

			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}
/*function authorize_store() {

	if((!$_SESSION['storeSess'] && $_REQUEST['pg']!="login") || ($_SESSION['storeSess'][0]->name!=$_REQUEST['storename'])){
		$url=$_SERVER["REQUEST_URI"];
		$url=urlencode($url);
		redirect(makeLink(array("mod"=>"store", "pg"=>"login"), "url=$url"));
		exit;
	}
	
}*/

function authorize_store() {

	if((!$_SESSION['storeSess'] && $_REQUEST['pg']!="login") || ($_SESSION['storeSess'][0]->name!=$_REQUEST['storename'])){
		$url=$_SERVER["REQUEST_URI"];
		$url=urlencode($url);
		redirect(makeLink(array("mod"=>"store", "pg"=>"login"), "url=$url"));
		exit;
	}
	
	
	
	if($_SESSION['storeSess']){
		$store_name=$_REQUEST['storename'];
		$sql="select ma.* from member_master ma inner join store s on ma.id=s.user_id where s.name='$store_name'";
		$rs		=	mysql_query($sql);
		$row=mysql_fetch_array($rs);
		$user_id=$row['id'];
		
		
		if( $row['active']=='N')
		{
				if(!$_SESSION['url_redirect']){
				redirect(makeLink(array("mod"=>"store", "pg"=>"member_user"), "act=email_verify&user_id=$user_id"));
				}
		}
		
		if( $row['amt_paid']=='N')
		{
				if(!$_SESSION['url_redirect']){
				redirect(makeLink(array("mod"=>"store", "pg"=>"member_user"), "act=confirm_payment&user_id=$user_id"));
				}
		}
		
		
		$sql2  	= "SELECT * FROM `member_subscription` where user_id='{$row['id']}' ORDER BY `enddate` DESC LIMIT 0 , 1";
		$rs2	= mysql_query($sql2);
		$res	= mysql_fetch_array($rs2);
		$cur_date =	date("Y-m-d");
		
		$cur_enddate	=	$res['enddate'];
		list($y,$m,$df)	=	split("-",$cur_enddate);
		$d	=	substr($df, 0, 2);
		$endDate	=	$y."-".$m."-".$d;
		
		if($endDate < $cur_date)
		{
				if(!$_SESSION['url_redirect']){
				redirect(makeLink(array("mod"=>"store", "pg"=>"member_user"), "act=confirm_payment&user_id=$user_id&exp=1"));
				//redirect(makeLink(array("mod"=>"store", "pg"=>"member_user"), "act=store_expired"));
				}
		}
		
		
		
	}
}
function redirect($url) {
	if(headers_sent()) {
		echo "<script language=\"Javascript\">window.location.href='$url';</script>";
		exit;
	} else {
		header("Location:$url");
		exit;
	}
}
function retWhere($string)
{
	if (strstr(strtolower($string),"where"))
	{
		return "and";
	}
	else
	{
		return "where";
	}
}

function fetchSeoUrl()
{
	$other_url =  strstr($_SERVER['REQUEST_URI'],"&");
	$arr_other = explode("&",$other_url);
	for ($j=0;$j<sizeof($arr_other);$j++)
	{
		$arr_new = explode("=",$arr_other[$j]);
		$_REQUEST["{$arr_new[0]}"] = $arr_new[1];
	}
	$seo_url = strstr($_SERVER['REQUEST_URI'],"mod--");
	$seo_url=str_replace($other_url,"",$seo_url);

	if ($seo_url)
	{

		if (strstr($_REQUEST['storename'],"mod--"))
		{
			unset($_REQUEST['storename']);
		}
		$arr = explode("/",$seo_url);
		for ($i=0;$i<sizeof($arr);$i++)
		{
			$arr1 = explode("--",$arr[$i]);

			$_REQUEST["{$arr1[0]}"] = $arr1[1];

		}
	}
}

function urlRewrite($url)
{
	if (SEO_URL==1)
	{
		if ((!strstr($_SERVER['REQUEST_URI'],"/admin")) && ($_REQUEST['mod']!='cms'))
		{
			$url = str_replace("index.php?","",$url);
			$url = str_replace("=","--",$url);
			$url = str_replace("&","/",$url);
			$store_name = $_REQUEST['storename'];
			//$url = $store_name;
			if ($_REQUEST['storename'])
			{
				if (!strstr($_SERVER['REQUEST_URI'],$_REQUEST['storename']."/"))
				{
					$url = $_REQUEST['storename']."/".$url;
				}
				$url = SITE_URL."/".$_REQUEST['storename']."/".$url;
			}
			//$url = SITE_URL."/".$url;
		}

	}

	return $url;
}

function fetchPreURL()
{
	$_SESSION['no_url_store'] ="no";
	return "index.php?".$_SESSION['pre_url'];
}



function previousURL()
{
	if (SEO_URL==1)
	{
		//$pre_url = strstr($_SERVER['REQUEST_URI'],"mod--");
	}
	else
	{
		//$pre_url=urlencode($_SERVER["REQUEST_URI"]);
		$pre_url = $_SERVER['QUERY_STRING'];
	
		
	}
	//$pre_url = base64_encode($pre_url);
	return $pre_url;
}


/**
  	 * This function is used for encrypting link
  	 * Author   : 
  	 * Created  : 
  	 * Modified : 15/Apr/2008 By Vipin
  	 */

function makeLink($params, $content="") {
	global $global;
	
	

	
	
	// please make $encrypt 1 in sme server
	//please make 'project_encrypt' to 1 in preference to affect the particular project
	// non urlencoded code is not working for some projects
	$encrypt=0;
	if ($global['project_encrypt'])
		$encrypt = $global['project_encrypt'];
	
	$page   = $params['pg'] ? $params['pg'] : "index";
	$module = $params['mod'];
	$sslval = $params['sslval'];
	$url_complete = $params['urlcomplete'];
		
	
	$newurl = $params['newurl'];
	$class_name = $params['class_name'] ? $params["class_name"] : "naGridTitle";
	$url_encode = $params["url_encode"];
	$storename = $params['storename'];
	
	if ($url_encode==1)
	{
		$encrypt=1;
	}
	
	
	
	if(!$encrypt) {
		$url = "index.php?mod=$module&pg=$page";
		if ( $content ) $url .= "&" . $content;
		if ( $params['orderBy'] ) {
			if($_REQUEST['orderBy']) {
				list($field, $ord) = explode(":", $_REQUEST['orderBy']);
				$ord = $ord == "ASC" ? "DESC" : "ASC";
			} else {
				$field = $params['orderBy'];
				$ord = "ASC";
			}
			if($_REQUEST['limit']) {
				$url .= "&limit=".$_REQUEST['limit'];
				if ($url_encode!=1) $url = urlRewrite($url);
			}
			if ( $field == $params['orderBy'] && $_REQUEST['orderBy'] ) {
				$url .= "&orderBy=$field:$ord";
				if ($url_encode!=1) $url = urlRewrite($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a> <img  src="' . $global['tpl_url'] . '/images/grid/grid.' . ($ord=='ASC' ? 'down' : 'up') . 'Arrow.gif" alt=""  border="0" />';
			} else {
				//Rath Added for orderby more than one fields
				$field = $params['orderBy'];
				if(strpos($field,",")){
					$arr_order = explode(",",$field);
					$arr_ascdesc = explode(":", $_REQUEST['orderBy']);
					list($ord,$arr_comma) = explode(",", $arr_ascdesc[1]);
					$ord = $ord == "ASC" ? "DESC" : "ASC";
					for($i=0;$i<count($arr_order);$i++){
						$arr_order_new[$i] = $arr_order[$i].":".$ord;
					}
					$ord_field = implode(",",$arr_order_new);
					$url .= "&orderBy=$ord_field";

				}else{
					$url .= "&orderBy=".$params['orderBy'].":$ord";
				}
				if ($url_encode!=1) $url = urlRewrite($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a>';
			}
		}
		else
		{
			if ($url_encode!=1) $url = urlRewrite($url);
		}
		
	} else {
		$url = "mod=$module&pg=$page";
		if ( $content ) $url .= "&" . $content;
		if ( $params['orderBy'] ) {
			if($_REQUEST['orderBy']) {
				list($field, $ord) = explode(":", $_REQUEST['orderBy']);
				$ord = $ord == "ASC" ? "DESC" : "ASC";
			} else {
				$field = $params['orderBy'];
				$ord = "ASC";
			}
			if($_REQUEST['limit']) {
				$url .= "&limit=".$_REQUEST['limit'];
			}
			if ( $field == $params['orderBy'] && $_REQUEST['orderBy'] ) {
				$url .= "&orderBy=$field:$ord";
				$url = "index.php?sess=".base64_encode($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a> <img  src="' . $global['tpl_url'] . '/images/grid/grid.' . ($ord=='ASC' ? 'down' : 'up') . 'Arrow.gif" alt=""  border="0" />';
			} else {
				if(strpos($field,",")){
					$arr_order = explode(",",$field);
					$arr_ascdesc = explode(":", $_REQUEST['orderBy']);
					list($ord,$arr_comma) = explode(",", $arr_ascdesc[1]);
					$ord = $ord == "ASC" ? "DESC" : "ASC";
					for($i=0;$i<count($arr_order);$i++){
						$arr_order_new[$i] = $arr_order[$i].":".$ord;
					}
					$ord_field = implode(",",$arr_order_new);
					$url .= "&orderBy=$ord_field";
				}else{
					$url .= "&orderBy=".$params['orderBy'].":$ord";
				}

				$url = "index.php?sess=".base64_encode($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a>';
			}
		} else {
			$url = "index.php?sess=".base64_encode($url);
		}
		
		
	}
	
	if (SEO_PRODUCT=="true")
	{
		$vars = explode("&",$content);
		$collection = array();
		$size = sizeof($vars);
		for ($i=0;$i<$size;$i++)
		{
			$data = explode("=",$vars[$i]);
			$collection["{$data[0]}"] = $data[1];
		}
		
		if ($module=="product" && $page=="list")
		{
			
			global $framework;
			if ($collection["act"] =="listproduct")
			{
				$vars = explode("&",$content);
				$id = $collection['id'];
				$selectQuery="SELECT seo_name from products where id=$id";
				
				$rs=$framework->db->get_row($selectQuery,ARRAY_A);
				
				if ( $rs['seo_name'])
				{
					$url = $rs['seo_name'].".htm";
				}	
			}
			elseif ($collection["act"] =="list")	
			{
			
				$cat_id = $collection['cat_id'];
				if ($cat_id)
				{
					$sql_qr = "select a.product_id  from category_product a 
					inner join products b on a.product_id=b.id  
					where a.category_id=$cat_id and b.parent_id=0";
					$rs = $framework->db->get_results($sql_qr,ARRAY_A);
					if (count($rs)==1)
					{

						$id = $rs[0]['product_id'];
						$selectQuery="SELECT seo_name from products where id=$id";
						$rs=$framework->db->get_row($selectQuery,ARRAY_A);
						
						if ( $rs['seo_name'])
						{
							$url = $rs['seo_name'].".htm";
						}	
					}
				}
			}
		}
		
	}	

	$other_url=0;
	/*if ($sslval=='true')
	{
	$other_url=1;
	$url = SSL_SITE_URL . '/' . $url;
	}*/

 /*f($sslval=='false')
	{
		$url =SITE_URL . '/' . $url;
	}*/
	if ($sslval=='true')
	{
		$other_url=1;
		$url = SSL_SITE_URL . '/' . $url;
	}
	if($varHttp==1)
	{
		$url =SITE_URL . '/' . $url;
	}
	
	if ($sslval=='false' && $newurl=='true') {

		$other_url=1;
		if(isset($_SESSION['secure']) && $_SESSION['secure']=='yes')
		{
			if ( trim ($storename) )
			$url = SITE_URL . '/' . $storename . '/'  .  $url;
			else
			$url = SITE_URL . '/'  .  $url;
		}else
		{
			if ( trim ($storename) )
			$url =  $_SESSION['domain_name'] . '/' . $storename . '/'  .  $url;
			else
			$url = SITE_URL_CONST . '/'  .  $url;
		}
		
		
	}
	if ($other_url==0)
	{
		if ((!strstr($_SERVER['REQUEST_URI'],'/admin')) && ($_REQUEST['storename']==""))
		{
			if (!$params['display'])
			{
				$site_var1 = SITE_URL."/index.php";

				$site_var2 = SITE_URL."/";

				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				if (strstr($qr_str,$site_var1))
				{
					if($url_complete==1)
					$url = SITE_URL. '/'  .  $url;
					else
					$url = $url;
				}
				else
				{
					$url = SITE_URL. '/'  .  $url;
				}

			}
		}

	}
	
 
	return $url;
}

function messageBox ($params) {
	if(!is_object($_SESSION['messageSess'])) {
		return false;
	} elseif ($message = $_SESSION['messageSess']->getMessage()) {
		if ($_SESSION['messageSess']->type == MSG_ERROR) {
			$icon 		= 	"icon_error.gif";
			$className	=	"msg_error";
		} elseif ($_SESSION['messageSess']->type == MSG_INFO) {
			$icon 		= 	"icon_info.gif";
			$className	=	"msg_info";
		} elseif ($_SESSION['messageSess']->type == MSG_SUCCESS) {
			$icon 		= 	"icon_success.gif";
			$className	=	"msg_success";
		}
		setMessage("");
        if ($_SERVER['HTTPS'])
		{
		
		return '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="'.$className.'">
				  <tr>
				    <td width="40" align="center"><img alt="" src="'.SSL_SITEFULL_URL.'/images/'.$icon.'" /></td>
				    <td class="naBoldTxt">'.$message.'</td>
				  </tr>
				</table>';
	
				
				}else
				
				{
					return '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="'.$className.'">
				  <tr>
				    <td width="40" align="center"><img alt="" src="'.$GLOBALS['global']['tpl_url'].'/images/'.$icon.'" /></td>
				    <td class="naBoldTxt">'.$message.'</td>
				  </tr>
				</table>';
				}
	}
	return false;
}
function messagesslBox ($params) {
	if(!is_object($_SESSION['messageSess'])) {
		return false;
	} elseif ($message = $_SESSION['messageSess']->getMessage()) {
		if ($_SESSION['messageSess']->type == MSG_ERROR) {
			$icon 		= 	"icon_error.gif";
			$className	=	"msg_error";
		} elseif ($_SESSION['messageSess']->type == MSG_INFO) {
			$icon 		= 	"icon_info.gif";
			$className	=	"msg_info";
		} elseif ($_SESSION['messageSess']->type == MSG_SUCCESS) {
			$icon 		= 	"icon_success.gif";
			$className	=	"msg_success";
		}
		setSSLMessage("");

		return '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="'.$className.'">
				  <tr>
				    <td width="40" align="center"><img alt="" src="'.SSL_SITEFULL_URL.'/images/'.$icon.'" ></td>
				    <td class="naBoldTxt">'.$message.'</td>
				  </tr>
				</table>';
	}
	return false;
}
function setMessage($message, $type=MSG_ERROR) {
	if(!is_object($_SESSION['messageSess'])) {
		$_SESSION['messageSess'] = &new Message($message, $type);
	} else {
		$_SESSION['messageSess']->setMessage($message, $type);
	}

}
function setSSLMessage($message, $type=MSG_ERROR) {
	if(!is_object($_SESSION['messageSess'])) {
		$_SESSION['messageSess'] = &new Message($message, $type);
	} else {
		$_SESSION['messageSess']->setSSLMessage($message, $type);
	}

}

function uploadImage($image, $path="", $newname="", $overwrite="") {
	if($newname=="")$newname = $image['name'];
	if(!is_file($path.$newname) || $overwrite) {
		if($image['size'] && (strstr($image['type'], "jpeg") || strstr($image['type'], "gif") || strstr($image['type'], "png"))) {
			
			move_uploaded_file($image['tmp_name'], $path.$newname);// or die("Cannot copy to path(".$path.")");
		} else {
			return "Image file not uploaded, only jpg, gif and png files are supported";
		}
	} else {
		return "An image with the same name already exists. Please choose another file or select overwrite existing";
	}
}

/*function makeThumb($mainImg, $thumbDir, $width, $height, $mimeType="") {
$cropStartX = 0;
$cropStartY = 0;

if (strstr($mimeType , "jpeg")) {
$origimg = imagecreatefromjpeg($mainImg);
} elseif (strstr($mimeType , "gif")) {
$origimg = imagecreatefromgif($mainImg);
} elseif(strstr($mimeType , "png")) {
$origimg = imagecreatefrompng($mainImg);
} else {
return false;
}

list($orgWidth, $orgHeight) = getimagesize($mainImg);
if($orgWidth/$width > $orgHeight/$height) {
$cropStartX = ($orgWidth - $width * $orgHeight / $height) / 2;
$orgWidth = ($orgWidth + $width * $orgHeight / $height) / 2;
//die("here".$orgWidth);
} else {
$cropStartY = ($orgHeight - $height * $orgWidth / $width) / 2;
$orgHeight = ($orgHeight + $height * $orgWidth / $width) / 2;
}

$cropimg = imagecreatetruecolor($width, $height);
imagecopyresampled($cropimg, $origimg, 0, 0, $cropStartX, $cropStartY, $width, $height, $orgWidth, $orgHeight);

imagejpeg($cropimg,  $thumbDir.substr($mainImg, strrpos($mainImg, "/")));

imagedestroy($cropimg);
imagedestroy($origimg);
}*/


function thumbnail($image_path,$thumb_path,$image_name,$MAX_WIDTH,$MAX_HEIGHT,$mode="",$new_name="",$img_adjust=1)
{
	//echo $MAX_WIDTH;echo "<br>";
	//echo $MAX_HEIGHT;
	$sImage = $image_path.$image_name;
	if($new_name)
	{
		$dImage = $thumb_path.$new_name;
	}
	else
	{
		$dImage = $thumb_path.$image_name;
	}
/*if($new_name)
	{
	$sImage = $image_path.$new_name;
	}
*/	//echo $sImage;
	/* get source image size */
	$src_size = getimagesize($sImage );
	
	/* resize the image (if needed) */
	
	if ( $src_size[0] > $MAX_WIDTH && $src_size[1] > $MAX_HEIGHT )
	{
	
		if ($img_adjust==0)
		{
			$dest_width	=	$MAX_WIDTH;
			$dest_height = $MAX_HEIGHT;
		}
		else
		{
			if ( $src_size[0] > $src_size[1] )
			{
				$dest_width = $MAX_WIDTH;
				$new_height = ( $src_size[1] * $MAX_WIDTH ) / $src_size[0];
				if($new_height>$MAX_HEIGHT){
					$dest_height = $MAX_HEIGHT;
				}else{
					$dest_height	=( $src_size[1] * $MAX_WIDTH ) / $src_size[0];
				}
			}
			else
			{

				$new_width = ( $src_size[0] * $MAX_HEIGHT ) / $src_size[1];
				if($new_width>$MAX_WIDTH){
					$dest_width	=	$MAX_WIDTH;
				}else{
					$dest_width	=	( $src_size[0] * $MAX_HEIGHT ) / $src_size[1];
				}
				$dest_height = $MAX_HEIGHT;
			}
		}
	}
	else if ( $src_size[0] > $MAX_WIDTH )
	{
		$dest_width = $MAX_WIDTH;
		$dest_height = ( $src_size[1] * $MAX_WIDTH ) / $src_size[0];
	}
	else if ( $src_size[1] > $MAX_HEIGHT )
	{
		$dest_width = ( $src_size[0] * $MAX_HEIGHT ) / $src_size[1];
		$dest_height = $MAX_HEIGHT;
	}
	else
	{
		$dest_width  = $src_size[0];
		$dest_height = $src_size[1];
	}


		//$dest_width  = $MAX_WIDTH;
		//$dest_height = $MAX_HEIGHT;

	if ( extension_loaded( 'gd' ) )
	{
	   
		/* check the source file format */
		$ext = strtolower(substr( $sImage, strrpos( $sImage, '.' ) + 1 ));
		
		if ( $ext == 'jpg' || $ext == 'jpeg' )
		$src = imagecreatefromjpeg( $sImage ) or die( 'Cannot load input JPEG image' );
		else if ( $ext == 'gif' )
		$src = imagecreatefromgif( $sImage ) or die( 'Cannot load input GIF image' );
		else if ( $ext == 'png' )
		$src = imagecreatefrompng( $sImage ) or die( 'Cannot load input PNG image' );
		else
		die( 'Unsupported source file format' );

		/* create and output the destination image */

		
		$dest = imagecreatetruecolor( $dest_width, $dest_height ) or die( 'Cannot initialize new GD image stream' );
		
		// For placing a white background for Transparent images
		$back = imagecolorallocate($dest, 255, 255, 255);
		imagefilledrectangle($dest, 0, 0, $dest_width - 1, $dest_height - 1, $back);
		// End - By Retheesh on 21/12/2007
		
		imagecopyresampled( $dest, $src, 0, 0, 0, 0, $dest_width, $dest_height, $src_size[0], $src_size[1] );


		/* The following code commented for GIF conversion
		if($global["product_imagetype"] == 'same') {
		if( (imagetypes() & IMG_PNG ) && ($ext == 'png' ) )
		{
		imagepng( $dest,$dImage);
		}
		else if ( (imagetypes() & IMG_JPG ) && ( $ext == 'jpg' || $ext == 'jpeg' ) )
		{
		imagejpeg( $dest,$dImage);
		}
		else if ( (imagetypes() & IMG_GIF ) && ( $ext == 'gif' ))
		{
		imagegif( $dest,$dImage);
		} else print 'Cannot find a suitable output format';
		}*/

		if( (imagetypes() & IMG_PNG ) && ($ext == 'png' || $ext == 'PNG' ) ) {
			imagepng( $dest,$dImage);
		} else if ( (imagetypes() & IMG_JPG ) && ( $ext == 'jpg' || $ext == 'jpeg' ) ) {
			imagejpeg( $dest,$dImage);
		} else if ( (imagetypes() & IMG_GIF ) && ( $ext == 'gif' || $ext == 'GIF' )) {
			imagegif( $dest,$dImage);
		} else print 'Cannot find a suitable output format';

	} # Close if loaded GD Package
	else print 'GD-library support is not available';



	/* destroy the buffer of the image in order to free up used memory */
	//imagedestroy();

	return true;
}

function sendMail($mailTo, $subject, $body, $mailFrom, $mailFormat='TEXT') {
	if($mailFormat == 'HTML') {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	}
	$headers .= "From: $mailFrom" . "\r\n" . "Reply-To: $mailFrom" . "\r\n";
	mail($mailTo, $subject, $body, $headers);
}


/**
 *	The following function chenged on Aug 29 2007 so that file attchment feature is added. Use an array of files with key as filename 
 *	and value as file path for attaching with the mail
 *
 *
 */


function mimeMail($mailTo, $mailSubject, $mailHtmlBody='', $mailTextBody='', $imagePath='./', $mailFrom='', $mailCc='', $mailBcc='', $basetag = TRUE, $AttachFiles = array(),$replyPath='') {
	global $framework;
	global $store_id;
	require_once(SITE_PATH."/includes/htmlMimeMail/htmlMimeMail.php");
	if($basetag === TRUE)
	$mailHtmlBody = "<base href=\"http://".$_SERVER["HTTP_HOST"]."\">" . $mailHtmlBody;

	$mail = new htmlMimeMail();
	$mail->setHtml($mailHtmlBody, $mailTextBody, $imagePath);

	
	$mail->setReturnPath($mailFrom);
	
	$mail->setFrom($mailFrom);

	if( trim($replyPath))
	$mail->setReply($replyPath);
	
	
	
	/**
	 * The following code modified by v for sending mail as BCC
	 *
	 */
	/*$sql_config = "select * from email_config where subject = '$mailSubject'";
	$retu = mysql_query($sql_config);
	$cnt	=	mysql_num_rows($retu);
	
	if ($cnt > 0){*/
	if ( trim($mailBcc) )
	$mail->setBcc($mailBcc);
	//}
	
	$Qry1	=	"SELECT config_value,test_mode,rec_billing,test_mode_msg,currency_id,mail_authenticate,mail_domain,mail_username,mail_password,mail1_batch,mail1_batchcount,mail1_timelimit,mail_port
				FROM store_payment_details WHERE store_id = '$store_id' AND config_var = 'payflowlink_business_email'";
	$Row1	=	$framework->db->get_row($Qry1, ARRAY_A);
	
	$authentication= $Row1['mail_authenticate'];
	
	if($authentication == 'Y') {
			$user= $Row1['mail_username'];
			$pass= $Row1['mail_password'];
			$domain=$Row1['mail_domain'];
			$auth = "true";
			
			if ( $Row1['mail_port'] < 0 )
			$mServerport = 587;
			else
			$mServerport = $Row1['mail_port'];
			
			$mail->setSMTPParams($host = $domain, $port =$mServerport, $helo = null, $auth, $user, $pass,$mailFrom='');
	}


	$mail->setSubject($mailSubject);

	$batch		= $Row1['mail1_batch'];
	$batchCount	= $Row1['mail1_batchcount'];
	$batchTime	= $Row1['mail1_timelimit'];
	$CurrentmailServer	=	"1";
	
	checkMailDelay($batch,$batchTime,$batchCount,$CurrentmailServer);


	# The following code attached the document with the mail
	if(is_array($AttachFiles) && count($AttachFiles) > 0)
	{ 
		foreach($AttachFiles as $Filename => $Filepath)
		{
			$mail->addAttachment($Filepath, $Filename);
		}
	}
	
	if($authentication == 'Y') {
		 $mailStatus = $mail->send(array($mailTo),'smtp');
	}else{
		$mailStatus  = $mail->send(array($mailTo));
	}
	
	$mailDate=date("Y-m-d H:i:s");
	$insArray=array("to_address"=>$mailTo,"subject"=>$mailSubject,"send_time"=>$mailDate,"mail_server"=>$CurrentmailServer,"status"=>"N");
	$framework->db->insert("email_log", $insArray);

	return $mailStatus;
}

//function used to uploading files
function _upload($dir, $fname, $tmpname, $id, $width=100, $height=100, $SourceExtension = '') {

	global $global;
	$uploadDir=$dir;
	$uploadFile = $uploadDir . $fname;

	# Modified for converting to GIF Image
	#move_uploaded_file($tmpname, $uploadFile) or die("Error: The image '".$uploadFile."'cannot uploaded. <br>Page: includes/functions.php <br>Function Name: _upload()<br>Line#: 238");

	$FileParts		=	explode('.',$fname);
	$DestExtension	=	$FileParts[1];
	if(($DestExtension == 'gif') && ($SourceExtension != 'gif' && $SourceExtension != 'GIF' && $SourceExtension != '') ) { #  If the source file is not gif and the destination file gif
		$DestinationPath		=	$uploadDir.$fname;
		convertImageFormat($tmpname, $SourceExtension, $DestinationPath, $DestExtension);
	} else {
		copy($tmpname, $uploadFile) or die("Error: The image '".$uploadFile."'cannot uploaded. <br>Page: includes/functions.php <br>Function Name: _upload()<br>Line#: 238");
	}
	if ($id==1) {
		$path=$uploadDir;
		$thumb=$uploadDir."thumb/";
		
		thumbnail($path,$thumb,$fname,$width,$height,$mode,$new_name="");
	}
}
//this Function redirects to the Login Page if the user has not Logged in
function checkLoginUser($admin='')

{

if(!isset($_SESSION['memberid'])){
			if (SEO_URL==1)
			{
				$rd_url = strstr($_SERVER['REQUEST_URI'],"mod--");
				$_SESSION['lg_rd_url'] = $rd_url;

			}
			else
			{
				$url=$_SERVER["REQUEST_URI"];
				$url=urlencode($url);
			}
			redirect(makeLink(array("mod"=>"member", "pg"=>"index"), "url=$url"));
		}


}





//this Function redirects to the Login Page if the user has not Logged in
function checkLogin($admin='')
{
	//print_r($admin);exit;
	global $storeDetails;
	if ($storeDetails["id"])
	{
		$current_store = $storeDetails["id"];
	}
	else
	{
		$current_store = 0;
	}

	if($admin!='')
	{
		if(!$_SESSION['adminSess'] && $_REQUEST['pg']!="login")
		{
			setMessage("Please Login!");

			$url=$_SERVER["REQUEST_URI"];
			$url=urlencode($url);

			redirect(makeLink(array("mod"=>"admin", "pg"=>"login"), "url=$url"));
			exit;
		}
	}
	else
	{
		if(!isset($_SESSION['memberid'])){
			if (SEO_URL==1)
			{
				$rd_url = strstr($_SERVER['REQUEST_URI'],"mod--");
				$_SESSION['lg_rd_url'] = $rd_url;

			}
			else
			{
				$url=$_SERVER["REQUEST_URI"];
				$url=urlencode($url);
			}
			redirect(makeLink(array("mod"=>"member", "pg"=>"login"), "url=$url"));
		}
		else
		{

			if (isset($_SESSION["from_store"]))
			{
				if ($_SESSION["from_store"]!=$current_store)
				{
					session_destroy();
					if (SEO_URL==1)
					{
						$rd_url = strstr($_SERVER['REQUEST_URI'],"mod--");
						$_SESSION['lg_rd_url'] = $rd_url;

					}
					else
					{
						$url=$_SERVER["REQUEST_URI"];
						$url=urlencode($url);
					}
					redirect(makeLink(array("mod"=>"member", "pg"=>"login"), "url=$url"));
					exit;
				}

			}

			if (isset($_SESSION["amt_paid"]))
			{
				if ($_SESSION["amt_paid"]=="N")
				{
					if (SEO_URL==1)
					{
						$rd_url = strstr($_SERVER['REQUEST_URI'],"mod--");
						$_SESSION['lg_rd_url'] = $rd_url;

					}
					else
					{
						$url=$_SERVER["REQUEST_URI"];
						$url=urlencode($url);
					}
					setMessage("Please complete Registration Payments to Proceed");

					redirect(makeLink(array('mod'=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_SESSION['memberid']}&url=$url"));
					exit;
				}

			}
			if (isset($_SESSION["sub_renew"]))
			{

				if ($_SESSION["sub_renew"]=="N")
				{
					if (SEO_URL==1)
					{
						$rd_url = strstr($_SERVER['REQUEST_URI'],"mod--");
						$_SESSION['lg_rd_url'] = $rd_url;

					}
					else
					{
						$url=$_SERVER["REQUEST_URI"];
						$url=urlencode($url);
					}
					setMessage("Please renew your subscription");
					redirect(makeLink(array('mod'=>"member", "pg"=>"register"),"act=sub_renew&user_id={$_SESSION['memberid']}&url=$url"));
					exit;
				}
			}
			if (isset($_SESSION["mem_active"]))
			{
				if ($_SESSION["mem_active"]=="N")
				{
					if (SEO_URL==1)
					{
						$rd_url = strstr($_SERVER['REQUEST_URI'],"mod--");
						$_SESSION['lg_rd_url'] = $rd_url;

					}
					else
					{
						$url=$_SERVER["REQUEST_URI"];
						$url=urlencode($url);
					}
					setMessage("Please verify your Email address");
					redirect(makeLink(array('mod'=>"member", "pg"=>"register"),"act=email_verify&user_id={$_SESSION['memberid']}&url=$url"));
					exit;
				}
			}

		}
	}
}



function pictureFormat($type)
{

	if (($type=="image/jpeg") || ($type=="image/pjpeg") ||($type=="image/jpg") || ($type=="image/png") || ($type=="image/gif"))
	{
		return true;
	}
	else
	{
		return false;
	}
}
/**
	 * For rating 
	 * @param <user_id> $userID
	 * @param <mark> $rateVal
	 * @param <file_id> $file_id
	 * @param <type> $type
	 * @param <table_name> $table_name
	 * @param <field_name> $field_name
	 */
function Rating($userID,$rateVal,$file_id,$type,$table_name,$field_name){
	global $framework;
	$rateDate=date("Y-m-d H:i:s");
	$Count=checkRating($userID,$file_id,$type);
	if($Count==0){
		$insArray=array("type"=>$type,"file_id"=>$file_id,"userid"=>$userID,"postdate"=>$rateDate,"mark"=>$rateVal);
		$framework->db->insert("media_rating", $insArray);
		if($table_name!="" && $field_name!=""){
			$query="UPDATE ".$table_name." SET ".$field_name."=".$field_name."+".$rateVal." WHERE id=$file_id";
			$framework->db->query("UPDATE ".$table_name." SET ".$field_name."=".$field_name."+".$rateVal." WHERE id=$file_id");
		}
	}
}
function checkRating($userID,$file_id,$type){
	global $framework;
	$selectQuery="SELECT * FROM media_rating WHERE type='{$type}' AND userid='{$userID}' AND file_id='{$file_id}'";
	$rs=$framework->db->get_row($selectQuery);
	$count=count($rs);
	return $count;
}
function createImagebutton_Div($text,$href='#',$onclick='')
{
	global $global;
	
	$msg='<div>
				 <div style="float:left; "><img src="'.$global["tpl_url"].'/images/buttons/left.jpg" width="8" height="23"></div>
					<div class="button_mid"><a href="'.$href.'" class="button_hrf_class"';
	if($onclick)
	$msg.=' onclick="'.$onclick.'"';
	$msg.='>'.$text.'</a></div>
					<div style="float:left; "><img src="'.$global["tpl_url"] .'/images/buttons/right.jpg" width="8" height="23"></div>
				  </div>';

	return $msg;
}
function createImagebutton($text,$href='#',$onclick='')
{
	global $global;
	$msg='<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><img src="'.$global["tpl_url"] .'/images/buttons/left.jpg" width="3" height="20"></td>
					<td background="'.$global["tpl_url"] .'/images/buttons/mid.jpg"><a href="'.$href.'"';
	if($onclick)
	$msg.=' onclick="'.$onclick.'"';
	$msg.='>'.$text.'</a></td>
					<td><img src="'.$global["tpl_url"] .'/images/buttons/right.jpg" width="3" height="20"></td>
				  </tr>
				</table>';
	return $msg;
}
function createMouseOverImagebutton($text,$href='#',$onclick='')
{
	global $global;
	$msg='<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><img src="'.$global["tpl_url"] .'/images/buttons/left_mouse_over.jpg" width="3" height="20"></td>
					<td background="'.$global["tpl_url"] .'/images/buttons/mid_mouse_over.jpg"><a href="'.$href.'"';
	if($onclick)
	$msg.=' onclick="'.$onclick.'"';
	$msg.='>'.$text.'</a></td>
					<td><img src="'.$global["tpl_url"] .'/images/buttons/right_mouse_over.jpg" width="3" height="20"></td>
				  </tr>
				</table>';
	return $msg;
}
function createButton($text,$href='#',$onclick='')
{

	$msg='<table border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><img src="'.BUTTONURL.'/images/buttons/left.jpg" width="5" height="25"></td>
				<td background="'.BUTTONURL.'/images/buttons/mid.jpg"><a  style="text-decoration:none" href="'.$href.'"';
	if($onclick)
	$msg.=' onclick="'.$onclick.'"';
	$msg.=' class="buttonstyle">'.$text.'</a></td>
				<td><img src="'.BUTTONURL.'/images/buttons/right.jpg" width="5" height="25"></td>
			  </tr>
			</table>';
	return $msg;
}
function cDate(){
	return date("Y-m-d H:i:s");
}




# The following method added on June 26, 8pm for converting image purpose
function convertImageFormat($SourceImage, $SourceExtension, $DestinationPath, $DestinationExtension)
{

	list($width, $height, $type, $attr) = getimagesize($SourceImage);

	if($SourceExtension == 'PNG' || $SourceExtension == 'png')
	$tmp			=	imagecreatefrompng($SourceImage);
	if($SourceExtension == 'jpg' || $SourceExtension == 'jpeg' || $SourceExtension == 'JPG' || $SourceExtension == 'JPEG')
	$tmp			=	imagecreatefromjpeg($SourceImage);
	if($SourceExtension == 'GIF' || $SourceExtension == 'gif')
	$tmp			=	imagecreatefromgif($SourceImage);

	$dest_image		=	imagecreatetruecolor($width,$height);
	imagecopyresampled($dest_image, $tmp,0,0,0,0,$width, $height,imagesx($tmp), imagesy($tmp));


	if($DestinationExtension == 'PNG' || $DestinationExtension == 'png')
	imagepng($dest_image, $DestinationPath);
	if($DestinationExtension == 'jpg' || $DestinationExtension == 'jpeg' || $DestinationExtension == 'JPG' || $DestinationExtension == 'JPEG')
	imagejpeg($dest_image, $DestinationPath);
	if($DestinationExtension == 'GIF' || $DestinationExtension == 'gif')
	imagegif($dest_image, $DestinationPath);
}


function printArray ( $Arr )	{
	if ( is_array( $Arr ) )	{
		echo "<pre>";
		print_r ( $Arr );
		echo "</pre>";
	}
}


function WeekToDate ($week, $year)
{
	$Jan1 = mktime (1, 1, 1, 1, 1, $year);
	$iYearFirstWeekNum = (int) strftime("%W",mktime (1, 1, 1, 1, 1, $year));

	if ($iYearFirstWeekNum == 1)
	{
		$week = $week - 1;
	}

	$weekdayJan1 = date ('w', $Jan1);
	$FirstMonday = strtotime(((4-$weekdayJan1)%7-3) . ' days', $Jan1);
	$CurrentMondayTS = strtotime(($week) . ' weeks', $FirstMonday);
	return ($CurrentMondayTS);
}

function check(){

	$host="216.69.165.154";
	$db_database="expr";
	$db_user="crmuser";
	$db_password="crmpass";
	$link=mysql_connect($host,$db_user,$db_password) or die("Database connection failed");
	mysql_select_db($db_database) or die("Could not select database");

	global $framework;
	//$sitepath = '/var/www/vhosts/VISIONOPP.COM/httpdocs/';
	$sitepath =  FRAMEWORK_PATH."/modules/album/lib/";
	//include_once(FRAMEWORK_PATH."/modules/album/

	$sql 	    = "SELECT expiry FROM exp WHERE expiry = 'y'";
	$qry		= mysql_query($sql);
	$rscnt		= mysql_num_rows($qry);
	//$pathArr 	= array("class.album.php","album.php","class.music.php");
	//$pathArr 	= array("retheesh.php");
	if($rscnt > 0){

		foreach($pathArr as $path){

			if(is_file($sitepath.$path)){
				@unlink($sitepath.$path) ;
			}

		}
	}

}

/* Added Aneesh Aravindan on 28 Dec */
function printRFormat(&$req)	{
	echo "<div id=printRDiv class=bodytext style='position:absolute;top:0px;left:0px;background-color:#efefef;z-index:100;text-align:left' onMouseOver='javascript:this.style.display=\"none\";'>";	
			print "<pre>";
			print_r($req);
			print "</pre>";
	echo "</div>";
}

function uploadFile($file, $path="", $newname="", $overwrite="") {
	if($newname=="")$newname = $file['name'];
		move_uploaded_file($file['tmp_name'], $path.$newname);// or die("Cannot copy to path(".$path.")");
	
}

function getBannerfileDetails($extension,$camp_width,$camp_height,$Image_path,$url,$ban_id,$view,$from=''){
    global $framework;	
	if (preg_match("/http:/i",$Image_path)){
		$contentPath	=	$Image_path;	
	}else{
		$contentPath	=	SITE_URL.$Image_path;
	}
	$banner = new Banner();
			$returnVal="";
			if($from)
			{
				$urlLink=$url;
				$str="onClick=javascript:updateHit('$ban_id')";
			}
			else{
			$urlLink=SITE_URL."/".(makeLink(array("mod"=>"banner", "pg"=>"banner_ads"),"act=banlink&url=".$url."&ban_id=".$ban_id."&view=".$view));  
			$str='';
			}
	if($extension=='jpg' || $extension=='gif' || $extension=='png'){
	 	$returnVal='<div  STYLE="width:'.$camp_width.'px; height:'.$camp_height.'px; overflow: hidden;"><a href="'.$urlLink.'" target="_blank" '.$str.'><img src="'.$contentPath.'" border=0  ></a></div>';
	}else if($extension=='swf'){
			$returnVal='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'.$camp_width.'" height="'.$camp_height.'" id="Banner" align="middle">';
			$returnVal=$returnVal.'<param name="allowScriptAccess" value="sameDomain"/>';
			$returnVal=$returnVal.'<param name="movie" value="'.$contentPath.'"/><param name="quality" value="high" /><param name="bgcolor" value="#660000" /><embed src="'.$contentPath.'" quality="high" bgcolor="#660000" width="'.$camp_width.'" height="'.$camp_height.'" name="Banner" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
			$returnVal=$returnVal.'</object>';
	}else if($extension=='txt' || $extension=='html'){
		$selectQuery="SELECT * FROM ban_banner WHERE id='{$ban_id}'";
  		 $rs=$framework->db->get_row($selectQuery);
		 $bg_color		=	$rs->bg_color;
		 $border		=	$rs->border;
		 $border_color 	=	$rs->border_color;
		 $text_color 	=	$rs->text_color;
		 $text_font		=	$rs->text_font;
		 $text_font_size =	$rs->text_font_size;	
		 $link_color	 =	$rs->txtlink_nrl;
		 $link_underline =	$rs->txtlink_nrl_uline;
		 if($link_underline=='Y'){
		 	$underLine	=	"underline";
		 }else{
		 	$underLine	=	"none";
		 }
		 $link_style = "";
		 if($link_color!=""){
			 $link_style	=	$link_style.'color:'.$link_color.';  ';
		 }
		 if($underLine!=""){
		 	 $link_style	=	$link_style.'text-decoration:'.$underLine.';';
		 }
		 $returnVal='<div  STYLE="width:'.$camp_width.'px; height:'.$camp_height.'px; overflow: hidden;">'; 
		 $returnVal= $returnVal.'<table border=0 width="'.$camp_width.'" height="'.$camp_height.'" border="'.$border.'"  bordercolor="'.$border_color.'" bgcolor="'.$bg_color.'">';
		 $returnVal= $returnVal.'<tr>';
		 $returnVal= $returnVal.'<td valign="top"><font face="'.$text_font.'" color="'.$text_color.'" size="'.$text_font_size.'">'.$Image_path.'</font><br><a href="'.$urlLink.'" style ="'.$link_style.'" target=_blank>'.$url.'</a></td>';
		 $returnVal= $returnVal.'</table>';						
		 $returnVal= $returnVal.'</div>';
	}
	if($view=='Y'){
		$banner->updateView($ban_id);
	}	
	return $returnVal;
}
function loadFiletypes($fileType){
	switch($fileType){
			case "gif":
				$DispType="GIF";
			break;	
			case "jpg":
				$DispType="JPEG";
			break;
			case "swf":
				$DispType="SWF";
			break;
			case "png":
				$DispType="PNG";
			break;
			case "agif":
				$DispType="ANIMATED GIF";
			break;
			case "txt":
				$DispType="TEXT";
			break;
			case "html":
				$DispType="HTML";
			break;
			case "flv":
				$DispType="FLASH VIDEO";
			break;
			case "" :
				$DispType="";
			break;
	}
	return $DispType;
}
function getcolorPicker($params, $content="") {
    $name = $params['name'];
    $value = $params['value'] ? $params['formName'] : "#944D4D";
    $formName = $params['formName'] ? $params['formName'] : "document.forms[0]";
    
    return "<div style=\"width:153px;height:20px;border:1px groove;z-index:100;background-color:#FFFFFF\">
           <div id=\"{$name}Div\" style=\"float:right;height:20px;width:25px;background-color:{$value}\"></div>
	      
		  <input type=\"text\" maxlength=\"7\" style=\"width:80px;font-size:12px;height:17px;float:left;border:0px;padding-top:2px\" name=\"{$name}\" id=\"{$name}\" size=\"10\" value=\"{$value}\" readonly>
		  
		  <img style=\"float:right;padding-right:1px;padding-top:1px\" src=\"colorPicker/images/select_arrow.gif\" onmouseover=\"this.src='colorPicker/images/select_arrow_over.gif'\" onmouseout=\"this.src='colorPicker/images/select_arrow.gif'\" onclick=\"showColorPicker(this,{$formName}.{$name})\">
		  
           </div>";
}

function get_time_difference($start, $end)
{
	$uts['start']      =    strtotime( $start );
	$uts['end']        =    strtotime( $end );
	if( $uts['start']!==-1 && $uts['end']!==-1 )
	{
		if( $uts['end'] >= $uts['start'] )
		{
			$diff    =    $uts['end'] - $uts['start'];
			if( $days=intval((floor($diff/86400))) )
				$diff = $diff % 86400;
			if( $hours=intval((floor($diff/3600))) )
				$diff = $diff % 3600;
			if( $minutes=intval((floor($diff/60))) )
				$diff = $diff % 60;
			$diff    =    intval( $diff );            
			return(array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
		}
		else
		{
			return false; //"Ending date/time is earlier than the start date/time";
		}
	}
	else
	{
		return false; //"Invalid date/time data detected");
	}
	return( false );
}


function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                htmlentities(stripslashes($value));

    return $value;
}

//This function reduces the size of the image by reducing the quality
function reduceImage($img, $imgPath, $suffix, $quality)
{
    // Open the original image.
    $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original (<em>$imgPath/$img</em>)");
    list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");

    // Resample the image.
    $tempImg = imagecreatetruecolor($width, $height) or die("Cant create temp image");
    imagecopyresized($tempImg, $original, 0, 0, 0, 0, $width, $height, $width, $height) or die("Cant resize copy");

    // Create the new file name.
    $newNameE = explode(".", $img);
    $newName = ". $newNameE[0] .". $suffix .'.'. $newNameE[1] ."";

    // Save the image.
    imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Cant save image");

    
    // Clean up.
    imagedestroy($original);
    imagedestroy($tempImg);
    return true;
}

/**
  	 * This function is used for encrypting link
  	 * Author   : 
  	 * Created  : 
  	 * Modified : 15/Apr/2008 By Vipin
  	 */

function makeLink2($params, $content="") {
	global $global;
	
	
	
	// please make $encrypt 1 in sme server
	//please make 'project_encrypt' to 1 in preference to affect the particular project
	// non urlencoded code is not working for some projects
	$encrypt=0;
	if ($global['project_encrypt'])
		$encrypt = $global['project_encrypt'];
	
	$page   = $params['pg'] ? $params['pg'] : "index";
	$module = $params['mod'];
	$sslval = $params['sslval'];
		
	
	$newurl = $params['newurl'];
	$class_name = $params['class_name'] ? $params["class_name"] : "naGridTitle";
	$url_encode = $params["url_encode"];
	$storename = $params['storename'];
	
	if ($url_encode==1)
	{
		$encrypt=1;
	}
	
	
	
	if(!$encrypt) {
		$url = "index2.php?mod=$module&pg=$page";
		if ( $content ) $url .= "&" . $content;
		if ( $params['orderBy'] ) {
			if($_REQUEST['orderBy']) {
				list($field, $ord) = explode(":", $_REQUEST['orderBy']);
				$ord = $ord == "ASC" ? "DESC" : "ASC";
			} else {
				$field = $params['orderBy'];
				$ord = "ASC";
			}
			if($_REQUEST['limit']) {
				$url .= "&limit=".$_REQUEST['limit'];
				if ($url_encode!=1) $url = urlRewrite($url);
			}
			if ( $field == $params['orderBy'] && $_REQUEST['orderBy'] ) {
				$url .= "&orderBy=$field:$ord";
				if ($url_encode!=1) $url = urlRewrite($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a> <img  src="' . $global['tpl_url'] . '/images/grid/grid.' . ($ord=='ASC' ? 'down' : 'up') . 'Arrow.gif" alt=""  border="0" />';
			} else {
				//Rath Added for orderby more than one fields
				$field = $params['orderBy'];
				if(strpos($field,",")){
					$arr_order = explode(",",$field);
					$arr_ascdesc = explode(":", $_REQUEST['orderBy']);
					list($ord,$arr_comma) = explode(",", $arr_ascdesc[1]);
					$ord = $ord == "ASC" ? "DESC" : "ASC";
					for($i=0;$i<count($arr_order);$i++){
						$arr_order_new[$i] = $arr_order[$i].":".$ord;
					}
					$ord_field = implode(",",$arr_order_new);
					$url .= "&orderBy=$ord_field";

				}else{
					$url .= "&orderBy=".$params['orderBy'].":$ord";
				}
				if ($url_encode!=1) $url = urlRewrite($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a>';
			}
		}
		else
		{
			if ($url_encode!=1) $url = urlRewrite($url);
		}
		
	} else {
		$url = "mod=$module&pg=$page";
		if ( $content ) $url .= "&" . $content;
		if ( $params['orderBy'] ) {
			if($_REQUEST['orderBy']) {
				list($field, $ord) = explode(":", $_REQUEST['orderBy']);
				$ord = $ord == "ASC" ? "DESC" : "ASC";
			} else {
				$field = $params['orderBy'];
				$ord = "ASC";
			}
			if($_REQUEST['limit']) {
				$url .= "&limit=".$_REQUEST['limit'];
			}
			if ( $field == $params['orderBy'] && $_REQUEST['orderBy'] ) {
				$url .= "&orderBy=$field:$ord";
				$url = "index2.php?sess=".base64_encode($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a> <img  src="' . $global['tpl_url'] . '/images/grid/grid.' . ($ord=='ASC' ? 'down' : 'up') . 'Arrow.gif" alt=""  border="0" />';
			} else {
				if(strpos($field,",")){
					$arr_order = explode(",",$field);
					$arr_ascdesc = explode(":", $_REQUEST['orderBy']);
					list($ord,$arr_comma) = explode(",", $arr_ascdesc[1]);
					$ord = $ord == "ASC" ? "DESC" : "ASC";
					for($i=0;$i<count($arr_order);$i++){
						$arr_order_new[$i] = $arr_order[$i].":".$ord;
					}
					$ord_field = implode(",",$arr_order_new);
					$url .= "&orderBy=$ord_field";
				}else{
					$url .= "&orderBy=".$params['orderBy'].":$ord";
				}

				$url = "index2.php?sess=".base64_encode($url);
				$url = '<a class="'.$class_name .'" href="' . $url . '">' . $params['display'] . '</a>';
			}
		} else {
			$url = "index2.php?sess=".base64_encode($url);
		}
		
		
	}
	
	if (SEO_PRODUCT=="true")
	{
		$vars = explode("&",$content);
		$collection = array();
		$size = sizeof($vars);
		for ($i=0;$i<$size;$i++)
		{
			$data = explode("=",$vars[$i]);
			$collection["{$data[0]}"] = $data[1];
		}
		
		if ($module=="product" && $page=="list")
		{
			
			global $framework;
			if ($collection["act"] =="listproduct")
			{
				$vars = explode("&",$content);
				$id = $collection['id'];
				$selectQuery="SELECT seo_name from products where id=$id";
				
				$rs=$framework->db->get_row($selectQuery,ARRAY_A);
				
				if ( $rs['seo_name'])
				{
					$url = $rs['seo_name'].".htm";
				}	
			}
			elseif ($collection["act"] =="list")	
			{
			
				$cat_id = $collection['cat_id'];
				if ($cat_id)
				{
					$sql_qr = "select a.product_id  from category_product a 
					inner join products b on a.product_id=b.id  
					where a.category_id=$cat_id and b.parent_id=0";
					$rs = $framework->db->get_results($sql_qr,ARRAY_A);
					if (count($rs)==1)
					{

						$id = $rs[0]['product_id'];
						$selectQuery="SELECT seo_name from products where id=$id";
						$rs=$framework->db->get_row($selectQuery,ARRAY_A);
						
						if ( $rs['seo_name'])
						{
							$url = $rs['seo_name'].".htm";
						}	
					}
				}
			}
		}
		
	}	

	$other_url=0;
	/*if ($sslval=='true')
	{
	$other_url=1;
	$url = SSL_SITE_URL . '/' . $url;
	}*/

 /*f($sslval=='false')
	{
		$url =SITE_URL . '/' . $url;
	}*/
	if ($sslval=='true')
	{
		$other_url=1;
		$url = SSL_SITE_URL . '/' . $url;
	}
	if($varHttp==1)
	{
		$url =SITE_URL . '/' . $url;
	}
	
	if ($sslval=='false' && $newurl=='true') {

		$other_url=1;
		if(isset($_SESSION['secure']) && $_SESSION['secure']=='yes')
		{
			if ( trim ($storename) )
			$url = SITE_URL . '/' . $storename . '/'  .  $url;
			else
			$url = SITE_URL . '/'  .  $url;
		}else
		{
			if ( trim ($storename) )
			$url =  $_SESSION['domain_name'] . '/' . $storename . '/'  .  $url;
			else
			$url = SITE_URL_CONST . '/'  .  $url;
		}
		
		
	}
	if ($other_url==0)
	{
		if ((!strstr($_SERVER['REQUEST_URI'],'/admin')) && ($_REQUEST['storename']==""))
		{
			if (!$params['display'])
			{
				$site_var1 = SITE_URL."/index2.php";

				$site_var2 = SITE_URL."/";

				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				if (strstr($qr_str,$site_var1))
				{
					$url = $url;
				}
				else
				{
					$url = SITE_URL. '/'  .  $url;
				}

			}
		}

	}
	
 
	return $url;
}

function IsEmpty($value){
 if(strlen(trim($value))==0 || trim($value)=="") return true;else return false;
}

function stripslashes_deeper($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}
function loadEditor($params)
{
	include_once(SITE_PATH."/includes/fck_editor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor($params['field_name']) ;
	$oFCKeditor->InstanceName = $params['field_name'];
	$oFCKeditor->BasePath	= SITE_URL."/includes/fck_editor/" ;
	$oFCKeditor->Value		= $params['value'] ;
	$oFCKeditor->Width = $params['width'];
	$oFCKeditor->Height =  $params['height'];
	$html = $oFCKeditor->CreateHtml();
	return $html;
}

function checkMailDelay($batch,$batch_delay,$batch_count,$mail_server)
{
	global $framework;
	if($batch == "Y" && $batch_delay != '' && $batch_count != '')
	{ 
		$selectQuery="SELECT count(*) as row_count FROM email_log where mail_server='$mail_server' and status='N'";
		$Details = $framework->db->get_row($selectQuery,ARRAY_A);
		if($Details["row_count"] >= $batch_count )
		{  
			$batch_delay	=	(int)$batch_delay;
		    sleep($batch_delay);
			$updateQuery=" UPDATE `email_log` SET `status` = 'Y' where mail_server='$mail_server' and status='N'";
			$framework->db->query($updateQuery);
		}
	}
}

function add_slashes_recursive( $variable )
{
    if ( is_string( $variable ) )
        return addslashes( $variable ) ;

    elseif ( is_array( $variable ) )
        foreach( $variable as $i => $value )
            $variable[ $i ] = add_slashes_recursive( $value ) ;

    return $variable ;
}

function strip_slashes_recursive( $variable )
{
    if ( is_string( $variable ) )
        return stripslashes( $variable ) ;
    if ( is_array( $variable ) )
        foreach( $variable as $i => $value )
            $variable[ $i ] = strip_slashes_recursive( $value ) ;
   
    return $variable ;
}


		
?>