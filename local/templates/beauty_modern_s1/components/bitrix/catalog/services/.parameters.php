<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Loader as ModuleLoader;

if (!\Bitrix\Main\Loader::includeModule('iblock'))
	return;
$boolCatalog = \Bitrix\Main\Loader::includeModule('catalog');

$displayPreviewTextMode = array(
	'H' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_HIDE'),
	'E' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_EMPTY_DETAIL'),
	'S' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_SHOW')
);

$arTemplateParameters['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE'),
	'TYPE' => 'LIST',
	'VALUES' => $displayPreviewTextMode,
	'DEFAULT' => 'E'
);

$arTemplateParameters['RECORD_WIZARD_LINK'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_RECORD_WIZARD_LINK'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_RECORD_WIZARD_LINK_DEFAULT')
);

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-" => " "));
$selectTypes = array("radio" => GetMessage("RADIO"), "select" => GetMessage("SELECT"));
$db_iblock = CIBlock::GetList(Array("SORT" => "ASC"), Array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["ORG_IBLOCK_TYPE"] != "-" ? $arCurrentValues["ORG_IBLOCK_TYPE"] : "foundations")));
while ($arRes = $db_iblock->Fetch())
	$arIBlocksOrg[$arRes["ID"]] = $arRes["NAME"];

$db_iblock = CIBlock::GetList(Array("SORT" => "ASC"), Array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["PRICE_IBLOCK_TYPE"] != "-" ? $arCurrentValues["PRICE_IBLOCK_TYPE"] : "prices")));
while ($arRes = $db_iblock->Fetch())
	$arIBlocksPrice[$arRes["ID"]] = $arRes["NAME"];

$arTemplateParameters['ORG_IBLOCK_TYPE'] = array(
	"PARENT"  => "BASE",
	"NAME"    => GetMessage("T_MEDSITE_DESC_ORG_TYPE"),
	"TYPE"    => "LIST",
	"VALUES"  => $arTypesEx,
	"DEFAULT" => "news",
	"REFRESH" => "Y",
);

$arTemplateParameters['DEP_IB_ID'] = array(
	"PARENT"            => "BASE",
	"NAME"              => GetMessage("T_MEDSITE_DESC_DEP_ID"),
	"TYPE"              => "LIST",
	"VALUES"            => $arIBlocksOrg,
	"DEFAULT"           => '={$_REQUEST["ID"]}',
	"ADDITIONAL_VALUES" => "Y",
	"REFRESH"           => "N",
);

if (!IsModuleInstalled('sale')) {
	$arTemplateParameters['PRICE_IBLOCK_TYPE'] = array(
		"PARENT"  => "BASE",
		"NAME"    => GetMessage("T_MEDSITE_DESC_PRICE_TYPE"),
		"TYPE"    => "LIST",
		"VALUES"  => $arTypesEx,
		"DEFAULT" => "news",
		"REFRESH" => "Y",
	);

	$arTemplateParameters['PRICE_IB_ID'] = array(
		"PARENT"            => "BASE",
		"NAME"              => GetMessage("T_MEDSITE_DESC_PRICE_ID"),
		"TYPE"              => "LIST",
		"VALUES"            => $arIBlocksPrice,
		"DEFAULT"           => '={$_REQUEST["ID"]}',
		"ADDITIONAL_VALUES" => "Y",
		"REFRESH"           => "N",
	);
}

if (ModuleLoader::includeModule('form')) {

    $arWebForm = array(0=>' - ');
    if (ModuleLoader::IncludeModule('form')) {
        $arFilter = array();
        $rsForms = CForm::GetList($by="s_id", $order="desc", $arFilter, $is_filtered);
        while ($arForm = $rsForms->Fetch()) {
            $arWebForm[$arForm['ID']] = $arForm['NAME'];
        }
    }

    $arTemplateParameters['REVIEW_FORM_ID'] = array(
		"PARENT"            => "ADDITIONAL_SETTINGS",
		"NAME"              => GetMessage("T_MEDSITE_REVIEW_FORM_ID"),
		"TYPE"              => "LIST",
        "VALUES"            => $arWebForm,
        "DEFAULT"           => '={$_REQUEST["REVIEW_FORM_ID"]}',
	);
    $arTemplateParameters['REVIEW_URL'] = array(
		"PARENT"            => "ADDITIONAL_SETTINGS",
		"NAME"              => GetMessage("T_MEDSITE_REVIEW_URL"),
		"TYPE"              => "STRING",
        "DEFAULT"           => SITE_DIR.'/employees/#user_id#/review/',
	);
}