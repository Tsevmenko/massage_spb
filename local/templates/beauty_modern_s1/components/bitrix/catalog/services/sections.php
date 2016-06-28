<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?
$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
$arResult['PRICES_ALLOW'] = CIBlockPriceTools::GetAllowCatalogPrices($arResult["PRICES"]);
$bIBlockCatalog = false;
$arCatalog = false;
$boolNeedCatalogCache = false;
$bCatalog = \Bitrix\Main\Loader::includeModule('catalog');
if ($bCatalog) {
	$arResultModules['catalog'] = true;
	$arCatalog = CCatalogSKU::GetInfoByIBlock($arParams["IBLOCK_ID"]);
	if (!empty($arCatalog) && is_array($arCatalog)) {
		$bIBlockCatalog = $arCatalog['CATALOG_TYPE'] != CCatalogSKU::TYPE_PRODUCT;
		$boolNeedCatalogCache = true;
	}
}
$arSelect = array('NAME','ID');
foreach($arResult["PRICES"] as &$value)
{
	if (!$value['CAN_VIEW'] && !$value['CAN_BUY'])
		continue;
	$arSelect[] = $value["SELECT"];
}
$arPriceTypeID = array();
global $arServicesFilter;
$arServicesFilter = array(
	'PROPERTY_NOT_IN_CATALOG' => false,
);
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"services_list",
	Array(
		"IBLOCK_TYPE"                     => 'medservices',
		"IBLOCK_ID"                       => $arParams['IBLOCK_ID'],
		"NEWS_COUNT"                      => 2000,
		"SORT_BY1"                        => "SORT",
		"SORT_ORDER1"                     => "ASC",
		"SORT_BY2"                        => "NAME",
		"SORT_ORDER2"                     => "ASC",
		"FILTER_NAME"                     => "arServicesFilter",
		"ACTIVE_DATE_FORMAT"			  => '',
		"FIELD_CODE" => $arSelect,
		"PROPERTY_CODE" => array(),
		'SET_TITLE' => 'N',
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
		"PRICE_IB_ID" => $arParams['PRICE_IB_ID'],
		"CACHE_GROUPS" => "Y",
		'COUNT_ELEMENTS' => 'N',
		'ADD_SECTIONS_CHAIN' => 'N',
		'PAGER_SHOW_ALL' => 'N',
		"DISPLAY_TOP_PAGER"	=>	'N',
		"DISPLAY_BOTTOM_PAGER"	=>	'N',
		"PAGER_SHOW_ALWAYS"	=>	'N',
//		'DETAIL_URL' => $APPLICATION->GetCurPageParam('STEP=service&SERVICE=#SERVICE_ID#',array('WEEK_START','STEP','SHOW','SPECIALITY','SERVICE','EMPLOYEE')),
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
	),
	$component,
	Array("HIDE_ICONS"=>"Y")
);?>