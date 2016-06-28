<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("События");
?>
<?$APPLICATION->IncludeComponent("bitrix:map.map", ".default", array(
	"IBLOCK_TYPE" => "bx_map",
	"IBLOCK_ID" => "21",
	"ELEMENTS_COUNT" => "500",
	"ICONPOS_PROP_CODE" => "UF_ICON_POS",
	"LATITUDE_PROP_CODE" => "LAT",
	"LONGITUDE_PROP_CODE" => "LNG",
	"ADDRESS_PROP_CODE" => "ADDRESS",
	"PHONE_PROP_CODE" => "PHONE",
	"OPENING_PROP_CODE" => "OPENING",
	"LINK_PROP_CODE" => "LINK",
	"SORT_SECTIONS_BY1" => "NAME",
	"SORT_SECTIONS_ORDER1" => "ASC",
	"SORT_SECTIONS_BY2" => "SORT",
	"SORT_SECTIONS_ORDER2" => "ASC",
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "NAME",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"SECTION_FIELDS" => array(
		0 => "",
		1 => "",
	),
	"SECTION_USER_FIELDS" => array(
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
	"CHECK_DATES" => "N",
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
	"MAP_TYPE" => "google",
	"DATA_TYPE" => "events",
	"REPLACE_RULES" => "Y",
	"MAP_HEIGHT" => "550"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>