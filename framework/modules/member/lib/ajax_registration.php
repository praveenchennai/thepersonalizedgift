<?php
/**
 * 
 *
 * @author Shinu
 * @package defaultPackage
 */
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");

$objUser 	= 	new User();
$objAlbum 	= 	new Album();
$objExtras	=	new Extras();

$store_name	=	$_REQUEST['store_name'];

switch ($_REQUEST["act"])
{

	case "sub_pack_dropdown":
		$reg_pack = $_REQUEST["reg_pack"];
		$pag		  =	$_REQUEST["pag"];
		if ($_REQUEST["selected"]!="")
		{
			$selected = $_REQUEST["selected"];
		}
		else
		{
			$selected = "";
		}
		$free = 1;
		if($reg_pack!='')
		{
			$sub = $objUser->loadSubscriptions($reg_pack);
		}
		else
		{
			$free = 0;
		}
		$cmb ="<select name='sub_pack' id='sub_pack'  style='width:200px' onBlur='validSubscription(this.value)'>";
		$cmb.="<option value=''>--Select Subscription--</option>";
		for ($i=0;$i<sizeof($sub);$i++)
		{
			$free = 0;
			if ($sub[$i]->type=='Y')
			{
			($sub[$i]->duration>1)? $type = " Years" : $type = " Year";
			}
			elseif ($sub[$i]->type=='M')
			{
			($sub[$i]->duration>1)? $type = " Months" : $type = " Month";
			}
			elseif ($sub[$i]->type=='D')
			{
			($sub[$i]->duration>1)? $type = " Days" : $type = " Day";
			}

			if ($selected==$sub[$i]->id)
			{
				$sel="selected";
			}
			else
			{
				$sel="";
			}
			$cmb .="<option value='{$sub[$i]->sub_id}' $sel>{$sub[$i]->name} ({$sub[$i]->duration}$type)</option>";
		}
		$cmb .='</select>~1';
		if ($free==1)
		{
			$cmb = "<strong class='greenClass'>FREE</strong><input type='hidden' name='sub_pack' value='0'>~0";
		}
		echo $cmb;
		exit;
		break;

	case "valid_name":
		
		
		if($_REQUEST['name'] != "" && $_REQUEST['lname'] != "" ){
		
		     if($_REQUEST['name']=='First Name')
			 {
			 	echo "<strong class='form_error_message'>Enter the first name and last name.</strong>";
				exit;
			 }
			 else if($_REQUEST['lname']=='Last Name')
			 {
			 	echo "<strong class='form_error_message'>Enter the first name and last name.</strong>";
				exit;
			 }
			else
			{
				$image	=	$global["tpl_url"]."/images/checkbullet.gif";
				echo "IMG";
				exit;
			}
		}
		else
		{
			echo "<strong class='form_error_message'>Enter the first name and last name.</strong>";
			exit;
		}
		break;
	case "valid_lname":
	
		if($_REQUEST['name'] != ""){
		
		     if($_REQUEST['name']=='Last Name')
			 {
			 	echo "<strong class='form_error_message'>Enter the last name.</strong>";
				exit;
			 }
			else
			{
				$image	=	$global["tpl_url"]."/images/checkbullet.gif";
				echo "IMG";
				exit;
			}
		}
		else
		{
			echo "<strong class='form_error_message'>Enter the first name and last name.</strong>";
			exit;
		}
		break;
			
	case "valid_email":
		if($_REQUEST['email'] != "")
		{
			if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_REQUEST['email']))
			{
				// echo "INVALID";
				echo "<strong class='redtext'>&nbsp;Invalid Email Address</strong>";
				exit;
			}
			else
			{
				if (!$objUser->checkEmail($_REQUEST['email'],$_REQUEST['user_id']))
				{
					echo "IMG";
					exit;
				}
				else
				{
					echo "<strong class='redtext'>&nbsp;Email already exists</strong>";
					exit;
				}
			}
		}
		else
		{
			echo "<strong class='redtext'>&nbsp;This information is required</strong>";
			exit;
		}
		break;

	case "valid_haerabout":
		if($_REQUEST['hearaboutus'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='redtext'>&nbsp;This information is required</strong>";
			exit;
		}
		break;

	case "valid_username":
		if($_REQUEST['username'] != '')
		{
			if( strlen($_REQUEST['username']) < 5)
			{
				echo "<strong class='form_error_message'>&nbsp;Minimum 5 characters required</strong>";
				exit;
			}

			elseif(!ctype_alnum($_REQUEST['username'])) 
			{
				echo "<strong class='form_error_message'>Use only alphanumeric characters</strong>";
				exit;
			}

			else
			{
				if (!$objUser->getUsernameDetails($_REQUEST["username"]))
				{
					echo "IMG";
					exit;
				}
				else
				{
					echo "<strong class='form_error_message'>Username already exists</strong>";
					exit;
				}
			}
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
		// password validation
	case "valid_password":
		if($_REQUEST['password'] != "")
		{
			if(strlen($_REQUEST['password']) < 6)
			{
				echo "<strong class='form_error_message'>&nbsp;Minimum 6 characters required</strong>";
				exit;
			}
			else
			{
				echo "IMG";
				exit;
			}
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
		// confirm password validation
	case "valid_confirmpassword":
		if($_REQUEST['con_password'] != "")
		{
			if(strlen($_REQUEST['con_password']) < 6)
			{
				echo "<strong class='form_error_message'>&nbsp;Minimum 6 characters required</strong>";
				exit;
			}
			elseif($_REQUEST['con_password'] != $_REQUEST['password'])
			{
				echo "<strong class='form_error_message'>&nbsp;Passwords are not matching</strong>";
				exit;
			}
			else
			{
				echo "IMG";
				exit;
			}
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
		// account url validation
	case "valid_accounturl":
		if($_REQUEST['account_url'] != '')
		{
			if(!ctype_alnum($_REQUEST['account_url']))
			{
				echo "<strong class='redtext'>&nbsp;Use only alphanumeric characters</strong>";
				exit;
			}
			else{
				$rs = $objUser->allUsers("account_url",$_REQUEST['account_url']);
				if  (strtolower($rs[0]->account_url)==strtolower($_REQUEST['account_url']))
				{
					echo "<strong class='redtext'>&nbsp;Account URL already exists</strong>";
					exit;
				}
				else{
					echo "IMG";
					exit;
				}
			}

		}
		else
		{
			echo "<strong class='redtext'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
		// package validation
	case "valid_package":
		if($_REQUEST['package'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
		// subscription package validation
	case "valid_subpack":
		if($_REQUEST['subpack'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
		// address validation
	case "valid_promo_code":
		if($_REQUEST['promo_code'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;Enter a valid coupon code</strong>";
			exit;
		}
		break;
		// address validation
			
	case "valid_address":
	
		if($_REQUEST['address'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
	//city validation	
	case "valid_city":
		if($_REQUEST['city'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
	//country validation
			
	case "valid_country":
		if($_REQUEST['country'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;	
		
	case "valid_state":
		if($_REQUEST['state'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;				
		// zip code validation
	case "valid_zip":
		if($_REQUEST['zip'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
	case "valid_store_name":
	
	 if(preg_match("/personaltouch/",strtolower(trim($_REQUEST['store_name']))))
		{
				echo "<strong class='form_error_message'>Invalid Store Name</strong>";
			exit;
		}
		else if($_REQUEST['store_name'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		
		break;	
	case "valid_telephone":
	
		if($_REQUEST['phone'] == "")
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		else
		{
			echo "IMG";
			exit;
		}
		break;	
	case "valid_heading1":
		if($_REQUEST['heading1'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
	case "valid_heading2":
		if($_REQUEST['heading2'] != "")
		{
			echo "IMG";
			exit;
		}
		else
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		break;
				
	case "jquery_test":
		echo implode(",",$_POST);
		exit;
		break;
	case "personal_save":
		$arr = $_POST;
		$objUser->setArrData($arr);
		$objUser->update();
		exit;
		break;	
	case "profile_save"	:
		$table_name = $_REQUEST['table_name'];
		$arr = $_POST;
		/*$table_name = 'member_profile_profession';
		$arr = array();
		$arr['user_id']=2;
		$arr['work_skills'] = "sk12122";*/
		$objUser->setArrData($arr);
		$objUser->addEditProfile($arr['user_id'],$table_name);
		exit;
		break;
}

?>