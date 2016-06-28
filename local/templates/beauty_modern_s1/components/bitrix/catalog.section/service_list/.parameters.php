<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!\Bitrix\Main\Loader::includeModule('iblock'))
	return;
$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-" => " "));

$db_iblock = CIBlock::GetList(Array("SORT" => "ASC"), Array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["PRICE_IBLOCK_TYPE"] != "-" ? $arCurrentValues["PRICE_IBLOCK_TYPE"] : "prices")));
while ($arRes = $db_iblock->Fetch())
	$arIBlocksPrice[$arRes["ID"]] = $arRes["NAME"];

if (!IsModuleInstalled('sale')) {
	$arTemplateParameters['PRICE_IBLOCK_TYPE'] = array(
		"PARENT"  => "BASE",
		"NAME"    => GetMessage("T_MEDSITE_DESC_PRICE_TYPE"),
		"TYPE"    => "LIST",
		"VALUES"  => $arTypesEx,
		"DEFAULT" => "news",
		"REFRESH" => "Y",
	);

	$arTemplateParameters['PRICE_IBLOCK_ID'] = array(
		"PARENT"            => "BASE",
		"NAME"              => GetMessage("T_MEDSITE_DESC_PRICE_ID"),
		"TYPE"              => "LIST",
		"VALUES"            => $arIBlocksPrice,
		"DEFAULT"           => '={$_REQUEST["ID"]}',
		"ADDITIONAL_VALUES" => "Y",
		"REFRESH"           => "N",
	);
}
