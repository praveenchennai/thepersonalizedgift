<?php

define("MSG_ERROR", 	1);
define("MSG_SUCCESS", 	2);
define("MSG_INFO",		3);

class Message {
	var $message;
	var $type;

	function Message($message="", $type=MSG_ERROR) {
		$this->setMessage($message, $type);
	}

	function setMessage ($message, $type) {
		$this->message 	= 	$message;
		$this->type 	=	$type;
	}

	function getMessage () {
		return $this->message;
	}
}

?>