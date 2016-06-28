<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
Bitrix\Main\Loader::includeModule("bitrix.map");

$Dir = dirname(__FILE__);
$Dir = str_replace('\\', '/', $Dir);
$templatePath = substr($Dir, strrpos($Dir, '/bitrix/templates/'));

$APPLICATION->AddHeadString("<script src=\"" . $templatePath . "/js/common.js\" charset=\"utf-8\"></script>");
?>