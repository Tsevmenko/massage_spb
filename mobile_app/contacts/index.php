<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>


<?if (IsModuleInstalled('bitrix.map')):?><div class="map-contacts-fix">
	<?$APPLICATION->IncludeComponent("bitrix:map.objects.mobile", ".default", array(
	"IBLOCK_TYPE" => "foundations",
	"IBLOCK_ID" => "#ORG_IBLOCK_ID#",
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
	"DETAIL_URL" => '/mobile_app/schedule/?STEP=where_to_record&COMPANY=#ID#',
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "100",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"MAP_TYPE" => "google",
	"DATA_TYPE" => "objects",
	"AJAX_PATH" => "/bitrix/components/bitrix/map.map/ajax.php",
	"ICONPOS_PROP_CODE" => "UF_ICON_POS",
	"LATITUDE_PROP_CODE" => "LAT",
	"LONGITUDE_PROP_CODE" => "LON",
	"ADDRESS_PROP_CODE" => "ADRESS",
	"PHONE_PROP_CODE" => "PHONE",
	"OPENING_PROP_CODE" => "WORK_TIME",
	"LINK_PROP_CODE" => "MEDSITE_ID",
	"BAR_HEIGHT" => "0"
	),
	false
);?></div>
<?else:?>
   <?$APPLICATION->IncludeComponent("bitrix:news.list", "contact_list", array(
	"IBLOCK_TYPE" => "foundations",
	"IBLOCK_ID" => "#ORG_IBLOCK_ID#",
	"NEWS_COUNT" => "20",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "ADRESS",
		1 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "/mobile_app/contacts/detail.php?ID=#ID#",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
	"ADD_SECTIONS_CHAIN" => "Y",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "Y",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<?endif?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>