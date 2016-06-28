<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?require(dirname(__FILE__).'/header.php')?>
<?global $arClinicFilter;?>

<?if (IsModuleInstalled('bitrix.map')):?>
	<?
		$arClinicFilter = array(
			'PROPERTY_EL_VALUE' => 'Y',
		);
		if (!empty ($arResult['AVIABLE_COMPANIES']) && !in_array(0,$arResult['AVIABLE_COMPANIES'])) {
			$arClinicFilter['ID'] = $arResult['AVIABLE_COMPANIES'];
		}
	?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:map.map", 
	".default", 
	array(
		"IBLOCK_TYPE" => "-",
		"IBLOCK_ID" => $arParams["ORG_IB_ID"],
		"ELEMENTS_COUNT" => "500",
		"SORT_SECTIONS_BY1" => "NAME",
		"SORT_SECTIONS_ORDER1" => "ASC",
		"SORT_SECTIONS_BY2" => "SORT",
		"SORT_SECTIONS_ORDER2" => "ASC",
		"SORT_BY1" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arClinicFilter",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => $APPLICATION->GetCurPageParam($arResult["STEP_LINKS_TEMPLATE"][$arResult["STEP"]+1+$arResult["STEP_TWO_CORECTION"]]."&COMPANY=#ID#",array("STEP","COMPANY","DEPARTMENT","SPECIALITY")),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "100",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"MAP_TYPE" => "yandex",
		"DATA_TYPE" => "objects",
		"REPLACE_RULES" => "Y",
		"MAP_HEIGHT" => "550",
		"ICONPOS_PROP_CODE" => "UF_ICON_POS",
		"PARENT_PROP_CODE" => "",
		"LATITUDE_PROP_CODE" => "LAT",
		"LONGITUDE_PROP_CODE" => "LON",
		"ADDRESS_PROP_CODE" => "ADRESS",
		"PHONE_PROP_CODE" => "PHONE",
		"OPENING_PROP_CODE" => "OPENING",
		"NO_CAT_ICONS" => "Y",
		"LINK_PROP_CODE" => "MEDSITE_ID",
		"COMPONENT_TEMPLATE" => ".default",
		"QUERY_SECTION" => "",
		"QUERY_OBJECTS" => "",
		"AJAX_PATH" => "/bitrix/components/bitrix/map.map/ajax.php",
		"MAP_NARROW_WIDTH" => "900",
		"TITLE_MAP" => "",
		"NO_CATS" => "N",
		"LOAD_ITEMS" => "Y",
		"FULLSCREEN_SLIDE" => "N",
		"UNIVERSAL_MARKER" => "N",
		"OLD_DATA_MODE" => "N",
		"NAME_PROP_CODE" => "",
		"DESCRIPTION_PROP_CODE" => "",
		"EMAIL_PROP_CODE" => "",
		"PICTURE_PROP_CODE" => "",
		"FULLSCREEN_SHOW" => "N",
		"CUSTOM_VIEW" => "N"
	),
	false
);?>
<?else:?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.filter", "org_catalog", array(
			"IBLOCK_TYPE" => $arParams['ORG_IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['ORG_IB_ID'],
			"FILTER_NAME" => "arClinicFilter",
			"FIELD_CODE" => array(
				0 => "NAME",
				2 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "ADRESS",
				1 => "",
			),
			"LIST_HEIGHT" => "5",
			"TEXT_WIDTH" => "20",
			"NUMBER_WIDTH" => "5",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_GROUPS" => "Y",
			"SAVE_IN_SESSION" => "N",
			"PRICE_CODE" => array(
			),
			"ACTION_URL" => $APPLICATION->GetCurPageParam(),
		),
		$component,
		Array("HIDE_ICONS"=>"Y")
	);?>
	<?
		$arClinicFilter['PROPERTY_EL_VALUE'] = 'Y';
		if (!empty ($arResult['AVIABLE_COMPANIES']) && !in_array(0,$arResult['AVIABLE_COMPANIES'])) {
			$arClinicFilter['ID'] = $arResult['AVIABLE_COMPANIES'];
		}
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"org_list",
		Array(
			"DISPLAY_TITLE" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "N",
			"AJAX_MODE" => "N",
			"IBLOCK_TYPE" => $arParams['ORG_IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['ORG_IB_ID'],
			"NEWS_COUNT" => "32",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "arClinicFilter",
			"FIELD_CODE" => array("ID","CODE","XML_ID","NAME","TAGS","SORT","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE","DATE_ACTIVE_FROM","ACTIVE_FROM","DATE_ACTIVE_TO","ACTIVE_TO","SHOW_COUNTER","SHOW_COUNTER_START","IBLOCK_TYPE_ID","IBLOCK_ID","IBLOCK_CODE","IBLOCK_NAME","IBLOCK_EXTERNAL_ID","DATE_CREATE","CREATED_BY","CREATED_USER_NAME","TIMESTAMP_X","MODIFIED_BY","USER_NAME"),
			"PROPERTY_CODE" => array("ADRESS","MEDSITE_ID","PHONE"),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => $APPLICATION->GetCurPageParam($arResult['STEP_LINKS_TEMPLATE'][$arResult['STEP']+1+$arResult['STEP_TWO_CORECTION']].'&COMPANY=#ID#',array('STEP','COMPANY','SPECIALITY')),
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_NOTES" => "",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"AJAX_OPTION_SHADOW" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			'HIDE_ER_ICON' => 'Y',
			"AJAX_OPTION_ADDITIONAL" => ""
		),
		$component,
		Array("HIDE_ICONS"=>"Y")
	);?>
<?endif?>