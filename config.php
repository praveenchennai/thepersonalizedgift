<?php

/**
 * Configuration File...
 *
 * @author sajith
 * @package NewAgeSME FrameWork
 */

/**
 * HTTP URL of the site
 */
if(stristr($_SERVER['HTTP_HOST'],'thepersonalizedgift.com') || stristr($_SERVER['HTTP_HOST'],'apersonalizedgift.com')) {
	define( "HTTP_PROTOCOL", 'https://' );
}
else{
	define( "HTTP_PROTOCOL", 'http://' );
}
define("SITE_URL", HTTP_PROTOCOL.$_SERVER['HTTP_HOST']);
define("SITE_URL_OLD", "http://".$_SERVER['HTTP_HOST']);
//define("SITE_URL", "http://www.newagesme.com/theunionshop");

// This checks whether Product Details need to be SEO friendly
define("SEO_PRODUCT","true");


define("SITE_URL_CONST", HTTP_PROTOCOL.$_SERVER['HTTP_HOST']);
define("SSL_SITE_URL", "https://".$_SERVER['HTTP_HOST']);

define("HTTP_PROTOCOL_OLD",'http://');

if(stristr($_SERVER['HTTP_HOST'],'www.'))
	define("WWW_STR",'');
else
	define("WWW_STR",'www.');
/**
 * Absolute (full) path to the framework folder of this site
 */
define("FRAMEWORK_PATH", dirname(__FILE__)."/framework");

/**
 * Absolute (full) path to the root folder of this site
 */
define("SITE_PATH", dirname(__FILE__));

/**
 * Mysql DB Username
 */
define("DB_USER", "theperson");

/**
 * Mysql Db Password
 */
define("DB_PASSWORD", "perso213gift");

/**
 * Mysql Db Name
 */
define("DB_NAME", "thepersonalizedgift_new");

/**
 * Mysql Server Host
 */
define ("DB_HOST", "localhost");

?>
