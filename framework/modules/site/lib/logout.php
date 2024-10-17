<?php
session_destroy();
redirect(makeLink(array('pg'=>"login")));
?>