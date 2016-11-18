<?php
/**
 * API (GET)
 * @param int item   N or empty for first
 * @param int count   N or empty for all
 * @param string type   text, plain, json or empty
 * @param string option   all, talk, min and empty for part, joins and quits only
 * @param string sort   desc or asc and empty for asc
 * @param string callback   callbackFunction for type=json only
 * @param int data   yyyymmdd or empty
 * @param string search   search Query
 * @author Simon Gattner
 */

/*** APP ***/
define('APP_PATH','../no_www/app/');
/*** LOG ***/
define('LOG_PATH', 'C:/xampp/htdocs/her0/api/no_www/log/');
// logfile LOG_FILE_PREFIX . $date . LOG_FILE_SURFIX
define('LOG_FILE_PREFIX', '#test_');
define('LOG_FILE_SURFIX', '.log');
// includes
require_once(APP_PATH.'model/Log.php');
require_once(APP_PATH.'controlle/log.php');
?>
