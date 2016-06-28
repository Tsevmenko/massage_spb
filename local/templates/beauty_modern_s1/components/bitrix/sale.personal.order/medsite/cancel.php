<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.cancel",
	"",
	array(
		"PATH_TO_LIST" => $arParams["PATH_TO_STAMPS"],
		"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
		"SET_TITLE" =>$arParams["SET_TITLE"],
		"ID" => $arResult["VARIABLES"]["ID"],
	),
	$component
);
?>
