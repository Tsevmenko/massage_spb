<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Объекты");
?>
<?$APPLICATION->IncludeComponent("bitrix:map.map", "modern", array(
	"IBLOCK_TYPE" => "bx_map",
	"IBLOCK_ID" => "20",
	"SORT_SECTIONS_BY1" => "NAME",
	"SORT_SECTIONS_ORDER1" => "ASC",
	"SORT_SECTIONS_BY2" => "SORT",
	"SORT_SECTIONS_ORDER2" => "ASC",
	"SORT_BY1" => "NAME",
	"SORT_ORDER1" => "ASC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "100",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"MAP_TYPE" => "yandex",
	"DATA_TYPE" => "objects",
	"REPLACE_RULES" => "Y",
	"MAP_HEIGHT" => "550",
	"ICONPOS_PROP_CODE" => "UF_ICON_POS",
	"PARENT_PROP_CODE" => "PARENT",
	"LATITUDE_PROP_CODE" => "LAT",
	"LONGITUDE_PROP_CODE" => "LNG",
	"ADDRESS_PROP_CODE" => "ADDRESS",
	"PHONE_PROP_CODE" => "PHONE",
	"OPENING_PROP_CODE" => "OPENING",
	"LINK_PROP_CODE" => "LINK",
	"AJAX_PATH" => "/bitrix/components/bitrix/map.map/ajax.php",
    "MAP_NARROW_WIDTH" => "900",
    "NO_CATS" => "N",
	"LOAD_ITEMS" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>