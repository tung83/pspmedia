<?php
@ob_start();
@session_start();
session_cache_expire(0);
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Asia/Saigon');

//Remove slash for get_magic_quote_gpc
//if ( in_array( strtolower( ini_get( 'magic_quotes_gpc' ) ), array( '1', 'on' ) ) )
//{
//    $_POST = array_map( 'stripslashes', $_POST );
//    $_GET = array_map( 'stripslashes', $_GET );
//    $_COOKIE = array_map( 'stripslashes', $_COOKIE );
//}
$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
while (list($key, $val) = each($process)) {
    foreach ($val as $k => $v) {
        unset($process[$key][$k]);
        if (is_array($v)) {
            $process[$key][str_replace(array('\\'), '', $k)] = $v;
            $process[] = &$process[$key][str_replace(array('\\'), '', $k)];
        } else {
            $process[$key][str_replace(array('\\'), '', $k)] = str_replace(array('\\'), '', $v);
        }
    }
}
unset($process);


//define area
define('_hostName'  , 'localhost');	

//define('_userName'  , 'psmedia_mekong');	
//define('_dbName'    , 'psmedia_mekong');	
//define('_pass'      , 'RrcUEaZ4');


define('_userName'  , 'pspmedia');	
define('_dbName'    , 'pspmedia');	
define('_pass'      , 't80vyBMVTe54bkhR');

//define('_userName'  , 'root');	
//define('_dbName'    , 'mekogas');	
//define('_pass'      , '');

define('myWeb'      , '/');
define('myPath'     , '../file/upload/');
define('webPath'    , '/file/upload/');
define('selfPath'   ,'/file/self/');

define('phpLib'     , 'object/');
define('jsLib'      , myWeb.'js/');


//define area end

//include area
include_once phpLib.'MysqliDb.php';
include_once phpLib.'Pagination.php';
include_once phpLib.'wideimage/WideImage.php';
include_once phpLib.'common.php';

global $db;
$db = new MysqliDb(_hostName,_userName,_pass,_dbName);
$db->connect();
//include area end

//set default variable
$lang=isset($_GET['lang'])?$_GET['lang']:'vi';
$view=isset($_GET['view'])?$_GET['view']:'trang-chu';
//set default variable end
?>