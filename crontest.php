<?
//$_SERVER["DOCUMENT_ROOT"] = "/home/hosting/www"; 
//$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"]; 
//define("NO_KEEP_STATISTIC", true); 
//define("NOT_CHECK_PERMISSIONS", true); 
//set_time_limit(0); 
//define("LANG", "ru"); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

mail("antontsevmenko@mail.ru", 'title', 'message');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 
?>