<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"BLOCK_TITLE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOCK_TITLE"),
		"TYPE" => "string",
		"DEFAULT" => GetMessage("T_IBLOCK_DESC_BLOCK_TITLE_DEF"),
	),
	"EREG_LINK" => Array(
		"NAME" => GetMessage("EREG_LINK"),
		"TYPE" => "string",
		"DEFAULT" => "/public/record_wizard.php",
	),
	"DISPLAY_TITLE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TITLE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_NAME" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"HIDE_ER_ICON" => Array(
		"NAME" => GetMessage("HIDE_ER_ICON"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
?>
