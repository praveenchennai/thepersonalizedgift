<?php
if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;

			if ( $_FILES['adv_image']['name'] )	{
				$req['adv_img_name']	=	basename($_FILES['adv_image']['name']);
				$req['adv_img_type']	=	$_FILES['adv_image']['type'];
				$req['adv_imgtmpname']	=	$_FILES['adv_image']['tmp_name'];
				
			}



			if( ($message = $objAdvertiser->editAdvDetailsByAdmin ( $req )) === true ) {
				setMessage("Advertisement Updated Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"list"), "act=list&sId=List"));
			}
				setMessage($message);
}	
?>
