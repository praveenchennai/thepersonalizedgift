<?php

class Newsletter extends FrameWork {

    function Newsletter() {
        $this->FrameWork();
    }

    /**
	 * List Mailing List
	 *
	 */
    function mailingListList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT * FROM newsletter_mailing_list";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
        return $rs;
    }

    /**
	 * Add Edit Mailing List
	 *
	 * @param <POST/GET Array> $req
	 * @return Error Message if Any
	 */
    function mailingListAddEdit (&$req) {

        extract($req);
        if(!trim($name)) {
            $message = "List Name is required";
        } elseif (!trim($owner_name)) {
            $message = "Owner Name is required";
        } elseif (!trim($owner_email)) {
            $message = "Owner Email is required";
        } else {
            $array = array("name"=>$name, "owner_name"=>$owner_name, "owner_email"=>$owner_email, "allow_subscription"=>$allow_subscription);
            if($id) {
                $array['id'] = $id;
                $this->db->update("newsletter_mailing_list", $array, "id='$id'");
            } else {
                $this->db->insert("newsletter_mailing_list", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }

    /**
	 * Get Mailing List
	 *
	 */
    function mailingListGet ($id) {
        $sql		= "SELECT * FROM newsletter_mailing_list WHERE id = '$id'";

        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    /**
	 * Delete Mailing List
	 *
	 * @param $id
	 * @param [Error Message] $message
	 */
    function mailingListDelete ($id) {
        $this->db->query("DELETE FROM newsletter_schedule WHERE list_id='$id'");
        $this->db->query("DELETE FROM newsletter_subscription WHERE list_id='$id'");
        $this->db->query("DELETE FROM newsletter_mailing_list WHERE id='$id'");
    }

    /**
	 * Subscribed Mailing List
	 *
	 */
    function mailingListSubsList ($user_id) {
        $sql		= "SELECT ml.*, ns.member_id
                         FROM newsletter_mailing_list ml 
                    LEFT JOIN newsletter_subscription ns 
                           ON (ns.list_id = ml.id AND ns.member_id='$user_id')
                              
                     ORDER BY ml.name";

        $rs = $this->db->get_results($sql);
        return $rs;
    }

    /**
	 * Subscribe
	 *
	 */
    function subscribe ($user_id, $email, $listArr) {

        $this->db->query("DELETE FROM newsletter_subscription WHERE member_id = '$user_id' OR email = '$email'");
        $this->db->update("member_master", array("newsletter"=>'Y'), "id='$user_id'");
        
        if($listArr) {
            foreach ($listArr as $list) {
            	$messages[] = $this->subscriptionAddEdit(array("list_id"=>$list, "email"=>$email, "member_id"=>$user_id));
            }
        }

        return $rs;
    }

    /**
	 * List Subscriptions
	 *
	 */
    function subscriptionList (&$req, $pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {

        $sql		= "SELECT * FROM newsletter_mailing_list l, newsletter_subscription s WHERE l.id = s.list_id ";

        if($req['list_id']) {
            $sql .= "AND s.list_id = '{$req['list_id']}' ";
        }
        if($req['email']) {
            $sql .= "AND s.email LIKE '%{$req['email']}%' ";
        }
        $sql .= "AND s.format LIKE '%{$req['format']}%' AND s.confirmed LIKE '%{$req['confirmed']}%' AND s.active LIKE '%{$req['active']}%' ";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
        return $rs;
    }

    /**
	 * Add Edit Subscription
	 *
	 * @param <POST/GET Array> $req
	 * @return Error Message if Any
	 */
    function subscriptionAddEdit ($req) {

        extract($req);
        if(!trim($list_id)) {
            $message = "Please select a Mailing List";
        } elseif (!trim($email)) {
            $message = "Email is required";
        } elseif (($idChk = $this->db->get_var("SELECT id FROM newsletter_subscription WHERE list_id = '$list_id' AND member_id = '$member_id' AND id != '$id'")) && $member_id) {
            $message = "Member already subscribed for this Mailing List";
        } elseif ($emailChk = $this->db->get_var("SELECT id FROM newsletter_subscription WHERE list_id = '$list_id' AND email = '$email' AND id != '$id'")) {
            $message = "This Email is already subscribed for this Mailing List";
        } else {
            $array = array("list_id"=>$list_id, "email"=>$email, "format"=>$format, "confirmed"=>$confirmed, "active"=>$active);
            if($member_id)$array["member_id"] = $member_id;
            if($id) {
                $array['id'] = $id;
                $this->db->update("newsletter_subscription", $array, "id='$id'");
            } else {
                $array['date_subscribed'] = date("Y-m-d H:i:s");
                $this->db->insert("newsletter_subscription", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }

    /**
	 * Get Subscription
	 *
	 */
    function subscriptionGet ($id) {
        $sql		= "SELECT * FROM newsletter_subscription WHERE id = '$id'";

        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    /**
	/**
	 * Get Subscription Based on email
	 *
	 */
    function subscriptionGetMail ($email) {
        $sql		= "SELECT * FROM newsletter_subscription WHERE email = '$email'";

        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    /**
	 * Delete Subscription
	 *
	 * @param $id
	 * @param [Error Message] $message
	 */
    function subscriptionDelete ($id) {
        $this->db->query("DELETE FROM newsletter_log WHERE subscription_id='$id'");
        $this->db->query("DELETE FROM newsletter_subscription WHERE id='$id'");
    }

    /**
	 * Mailing List Combo
	 *
	 */
    function listCombo () {
        $sql		= "SELECT id, name FROM newsletter_mailing_list ORDER BY `name`";

        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['name'] = $this->db->get_col("", 1);
        return $rs;
    }

    function memberSearch ($keyword, $pageNo, $limit = 20, $params='', $output=OBJECT, $orderBy) {

        $sql		= "SELECT * FROM member_master WHERE 1 ";

        if($keyword) {
            $sql   .= "AND (first_name LIKE '%{$keyword}%' OR last_name LIKE '%{$keyword}%') ";
        }

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
        return $rs;
    }

    function newsletterList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT *, DATE_FORMAT(date_created, '".$this->config['date_format'].' '.$this->config['time_format']."') AS date_created_f FROM newsletter WHERE 1";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy?$orderBy:"date_created:DESC");
        return $rs;
    }

    /**
	 * Add Edit Newsletter
	 *
	 * @param <POST/GET Array> $req
	 * @return Error Message if Any
	 */
    function newsletterAddEdit (&$req) {

        extract($req);
        if(!trim($name)) {
            $message = "Newsletter Name is required";
        } elseif (!trim($format) && !$id) {
            $message = "Format is required";
        } elseif (!trim($subject)  && $id) {
            $message = "Subject is required";
        } elseif ($idChk = $this->db->get_var("SELECT id FROM newsletter WHERE name = '$name' AND id != '$id'")) {
            $message = "Newsletter name is already in use. Please choose another name";
        } else {
            if($id) {
                $array = array("name"=>$name, "subject"=>$subject, "body_html"=>$body_html, "body_text"=>$body_text);
                $this->db->update("newsletter", $array, "id='$id'");
            } else {
                $array = array("name"=>$name, "format"=>$format, "date_created"=>date("Y-m-d H:i:s"));
                $this->db->insert("newsletter", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }
//Modified:07/11/2007  By Ratheesh KK
    function newsletterCount($req) {
	
        extract($req);
		if (trim($all_users)>0){
				if($req[country]!="") $condition = "AND T2.country ='$req[country]'";
				else $condition = "";
				if($req[active]!="") $condition .= "AND T1.active LIKE '%$active%'";
			$count = $this->db->get_var("select COUNT(*) from member_master AS T1, member_address AS T2 where T1.id = T2.user_id  $condition AND T1.email LIKE '%$email%' ");
			return $count;
		
		}else{
			
        if(!trim($list_id)) {
            $message = "Please Select a Mailing List";
        } else {
				if($req[country]!="") $condition = "AND country ='$req[country]'";
				else $condition = "";
				if($req[active]!="") $condition .= "AND T1.active LIKE '%$active%'";
				
				//$count = $this->db->get_var("SELECT COUNT(*) FROM newsletter_subscription WHERE list_id = '$list_id' AND email LIKE '%$email%' AND format LIKE '%$format%' AND confirmed LIKE '%$confirmed%' AND active LIKE '%$active%'");
           		$count = $this->db->get_var("SELECT COUNT(*) FROM newsletter_subscription AS T1,member_master AS T2,member_address AS T3 where T2.email = T1.email AND T3.user_id = T2.id AND T3.addr_type='master' AND T1.list_id = '$list_id' AND T1.email LIKE '%$email%' AND T1.format LIKE '%$format%' AND T1.confirmed LIKE '%$confirmed%' $condition");
		   return $count;
        }
		}
        return $message;
    }

    /**
	 * Delete Newsletter
	 *
	 * @param $id
	 * @param [Error Message] $message
	 */
    function newsletterDelete ($id) {
        $this->db->query("DELETE FROM newsletter_schedule WHERE newsletter_id='$id'");
        $this->db->query("DELETE FROM newsletter_log WHERE newsletter_id='$id'");
        $this->db->query("DELETE FROM newsletter WHERE id='$id'");
    }

    /**
	 * Get Newsletter
	 *
	 */
    function newsletterGet ($id) {
        $sql		= "SELECT * FROM newsletter WHERE id = '$id'";

        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    /**
	 * Get Newsletter Schedule
	 *
	 */
    function newsletterScheduleGet ($id) {
        $sql = "SELECT * FROM newsletter_schedule WHERE id = '$id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);

        $sql = "SELECT name FROM newsletter_mailing_list WHERE id='{$rs['list_id']}'";
        $rs['list_name'] = $this->db->get_var($sql);

        $sql = "SELECT name FROM newsletter WHERE id='{$rs['newsletter_id']}'";
        $rs['newsletter_name'] = $this->db->get_var($sql);

        return $rs;
    }

    function newsletterScheduleAdd(&$req) {
        extract($req);
        if(!trim($sender_name) || !trim($sender_email)) {
            $message = "Sender Name and Email is required";
        } else {
            $replyto_email = $replyto_email ? $replyto_email : $sender_email;
            $criteria = "email={$req['email']}&format={$req['format']}&confirmed={$req['confirmed']}&active={$req['active']}&country={$req['country']}&all_users={$req['all_users']}";
            $array = array("newsletter_id"=>$id, "list_id"=>$list_id, "criteria"=>$criteria, "date_scheduled"=>date("Y-m-d H:i:s"), "member_count"=>$count, "sender_name"=>$sender_name, "sender_email"=>$sender_email, "replyto_email"=>$replyto_email);
            $this->db->insert("newsletter_schedule", $array);
            $message = $this->db->insert_id;
        }
        return $message;
    }

    function send($id, $page) {

        $schedule = $this->newsletterScheduleGet($id);
        $list_id = $schedule['list_id'];
        $newsletter_id = $schedule['newsletter_id'];
        parse_str($schedule['criteria']);
		if ($country)
		$condition  = "and T2.country LIKE '%$country%'";
        $newsletterRS = $this->newsletterGet($newsletter_id);
		if ($list_id>0)
        $newsSubs = $this->db->get_row("SELECT * FROM newsletter_subscription WHERE list_id = '$list_id' AND email LIKE '%$email%' AND format LIKE '%$format%' AND confirmed LIKE '%$confirmed%' AND active LIKE '%$active%' LIMIT $page, 1", ARRAY_A);
		else
		$newsSubs = $this->db->get_row("select * from member_master AS T1, member_address AS T2 where T1.id = T2.user_id and T1.active LIKE '%$active%' $condition AND T1.email LIKE '%$email%' LIMIT $page, 1" , ARRAY_A);
        if($newsSubs) {
		 if ($list_id>0){
            if ($this->db->get_var("SELECT subscription_id FROM newsletter_log WHERE subscription_id = '{$newsSubs['id']}' AND newsletter_id = '$newsletter_id'")) {
                $emailStat = $newsSubs['email']."&nbsp;----&nbsp;Already Sent";
            } else {
                // send mail using $newsletterRS['subject'] $newsletterRS['html'] $newsletterRS['text']
                if ($newsSubs['format'] == 'T' && $newsletterRS['format'] == 'H') {

                	$emailStat = "<b>".$newsSubs['email']."</b>&nbsp;----&nbsp;Skipped - Subscribed to receive text format only";

                } elseif ($newsSubs['format'] == 'T') {

                	mail($newsSubs['email'], $newsletterRS['subject'], $newsletterRS['body_text'], "From: ".$schedule['sender_name']." <".$schedule['sender_email'].">");
                	$emailStat = "<b>".$newsSubs['email']."</b>&nbsp;----&nbsp;Sent Successfully";
                	
                } else {
                	
                	mimeMail($newsSubs['email'], $newsletterRS['subject'], $newsletterRS['body_html'], $newsletterRS['body_text'], "", $schedule['sender_name']." <".$schedule['sender_email'].">");
                	$emailStat = "<b>".$newsSubs['email']."</b>&nbsp;----&nbsp;Sent Successfully";
                }

                $array = array("subscription_id"=>$newsSubs['id'], "newsletter_id"=>$newsletter_id, "schedule_id"=>$id, "date_sent"=>date("Y-m-d H:i:s"));
                $this->db->insert("newsletter_log", $array);

               	$sendArr['member_sent'] = $page+1;
            }
			}else{
			
			
			
			if ($this->db->get_var("SELECT member_id FROM newsletter_log WHERE member_id = '{$newsSubs['id']}' AND newsletter_id = '$newsletter_id'")) {
                $emailStat = $newsSubs['email']."&nbsp;----&nbsp;Already Sent";
            } else {
                // send mail using $newsletterRS['subject'] $newsletterRS['html'] $newsletterRS['text']
                if ($newsSubs['format'] == 'T') {

                	mail($newsSubs['email'], $newsletterRS['subject'], $newsletterRS['body_text'], "From: ".$schedule['sender_name']." <".$schedule['sender_email'].">");
                	$emailStat = "<b>".$newsSubs['email']."</b>&nbsp;----&nbsp;Sent Successfully";
                	
                } else {
                	
                	mimeMail($newsSubs['email'], $newsletterRS['subject'], $newsletterRS['body_html'], $newsletterRS['body_text'], "", $schedule['sender_name']." <".$schedule['sender_email'].">");
                	$emailStat = "<b>".$newsSubs['email']."</b>&nbsp;----&nbsp;Sent Successfully";
                }

                $array = array("member_id"=>$newsSubs['id'], "newsletter_id"=>$newsletter_id, "schedule_id"=>$id, "date_sent"=>date("Y-m-d H:i:s"));
                $this->db->insert("newsletter_log", $array);

               	$sendArr['member_sent'] = $page+1;
            }
			
			}//end of updatd all users
        }
        if(($count = $this->newsletterCount(array("list_id"=>$list_id, "email"=>$email, "format"=>$format, "confirmed"=>$confirmed, "active"=>$active, "country"=>$country, "all_users"=>$all_users))) > $page) {
            $sendArr['status'] = 'P';
            $this->db->update("newsletter_schedule", $sendArr, "id='$id'");
            $page++;
        } else {
            $sendArr['status'] = 'Y';
            $this->db->update("newsletter_schedule", $sendArr, "id='$id'");
            $page = 0;
        }

        return $page.'|'.$emailStat.'|'.$count;
    }

    /**
	 * Template Combo
	 *
	 */
    function templateCombo () {
        $sql		= "SELECT id, name FROM newsletter_template ORDER BY `name`";

        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['name'] = $this->db->get_col("", 1);
        return $rs;
    }

    function templateList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT *, DATE_FORMAT(date_created, '".$this->config['date_format'].' '.$this->config['time_format']."') AS date_created_f FROM newsletter_template WHERE 1";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy?$orderBy:"date_created:DESC");
        return $rs;
    }

    function templateAddEdit (&$req) {

        extract($req);
        if(!trim($name)) {
            $message = "Template Name is required";
        } elseif ($idChk = $this->db->get_var("SELECT id FROM newsletter_template WHERE name = '$name' AND id != '$id'")) {
            $message = "Template name is already in use. Please choose another name.";
        } else {
            $array = array("name"=>$name, "body_html"=>$body_html, "body_text"=>$body_text);
            if($id) {
                $this->db->update("newsletter_template", $array, "id='$id'");
            } else {
                $array["date_created"] = date("Y-m-d H:i:s");
                $this->db->insert("newsletter_template", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }

    function templateDelete ($id) {
        $this->db->query("DELETE FROM newsletter_template WHERE id='$id'");
    }

    function templateGet ($id) {
        $sql		= "SELECT * FROM newsletter_template WHERE id = '$id'";

        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    function logList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT ns.*,
                              n.name as newsletter,
                              ml.name as mailing_list, 
                              DATE_FORMAT(date_scheduled, '".$this->config['date_format'].' '.$this->config['time_format']."') AS date_created_f 
                         FROM newsletter_schedule ns, newsletter n, newsletter_mailing_list ml 
                        WHERE ns.newsletter_id = n.id
                          AND ns.list_id = ml.id";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy?$orderBy:"date_scheduled:DESC");
        return $rs;
    }

    function logDelete ($id) {
        $this->db->query("DELETE FROM newsletter_log WHERE schedule_id='$id'");
        $this->db->query("DELETE FROM newsletter_schedule WHERE id='$id'");
    }

    function logDetailList ($id, $pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT ns.email,
                              DATE_FORMAT(date_sent, '".$this->config['date_format'].' '.$this->config['time_format']."') AS date_created_f 
                         FROM newsletter_log nl, newsletter_subscription ns
                        WHERE nl.subscription_id = ns.id
                          AND nl.schedule_id = '$id'";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy?$orderBy:"date_sent:DESC");
        return $rs;
    }




  /*
  					###    Support Sub Menu
  */
  
   function supportList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT m.username,s.*, DATE_FORMAT(s.date_added, '".$this->config['date_format'].' '.$this->config['time_format']."') AS date_added_f FROM newsletter_support s,member_master m WHERE s.user_id = m.id ";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy?$orderBy:"date_added:DESC");
        return $rs;
    }

    function supportListGet ($id) {
        $sql		= "SELECT m.username,m.email,n.*,DATE_FORMAT(n.date_added, '".$this->config['date_format'].' '.$this->config['time_format']."') AS date_added_f FROM newsletter_support n, member_master m WHERE n.id = $id AND n.user_id = m.id";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    function supportDelete ($id) {
        $this->db->query("DELETE FROM newsletter_support WHERE id='$id'");
    }
	
	function deletefromnewsLetterGroups($user_id,$userEmail,$list_id)
	{
		$this->db->query("DELETE FROM newsletter_subscription where list_id='$list_id' AND member_id='$user_id' AND email='$userEmail'");
		return true;
	}
	
	function addtonewsLetterGroups($user_id,$userEmail,$list_id)
	{
		  $this->deletefromnewsLetterGroups($user_id,$userEmail,$list_id);
		  $cur_date	=	date("Y-m-d H:i:s");
	      $array = array("list_id"=>$list_id,"member_id"=>$user_id,"email"=>$userEmail,"date_subscribed"=>$cur_date,"format"=>"H","confirmed"=>"Y","active"=>"Y");
          $this->db->insert("newsletter_subscription", $array);
		  return true;
	}

}
?>