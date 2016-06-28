<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вопросы пациентов");
?> <?$APPLICATION->IncludeComponent("bitrix:news", "feedback_list", array(
	"IBLOCK_TYPE" => "registry",
	"IBLOCK_ID" => "16",
	"STATE_FIELD" => '33',
	"NEWS_COUNT" => "20",
	"USE_SEARCH" => "N",
	"USE_RSS" => "N",
	"USE_RATING" => "N",
	"USE_CATEGORIES" => "N",
	"USE_REVIEW" => "N",
	"USE_FILTER" => "Y",
	"FILTER_NAME" => "arrFilter",
	"FILTER_FIELD_CODE" => array(
		0 => "DETAIL_TEXT",
		1 => "DATE_ACTIVE_FROM",
		2 => "",
	),
	"FILTER_PROPERTY_CODE" => array(
		0 => "STATUS",
		1 => "",
	),
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"CHECK_DATES" => "N",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "/for_staff/feedback/",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"USE_PERMISSIONS" => "N",
	"PREVIEW_TRUNCATE_LEN" => "",
	"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
	"LIST_FIELD_CODE" => array(
		0 => "DATE_ACTIVE_FROM",
		1 => "DATE_ACTIVE_TO",
		2 => "TIMESTAMP_X",
		3 => "",
	), 
	"LIST_PROPERTY_CODE" => array(
		0 => "STATUS",
		1 => "",
	),
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"DISPLAY_NAME" => "Y",
	"META_KEYWORDS" => "-",
	"META_DESCRIPTION" => "-",
	"BROWSER_TITLE" => "-",
	"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
	"DETAIL_FIELD_CODE" => array(
		0 => "DATE_ACTIVE_FROM",
		1 => "DATE_ACTIVE_TO",
		2 => "TIMESTAMP_X",
		3 => "",
	),
	"DETAIL_PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"DETAIL_DISPLAY_TOP_PAGER" => "N",
	"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
	"DETAIL_PAGER_TITLE" => "Страница",
	"DETAIL_PAGER_TEMPLATE" => "",
	"DETAIL_PAGER_SHOW_ALL" => "Y",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "Вопросы",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_PICTURE" => "N",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"USE_SHARE" => "N",
	"AJAX_OPTION_ADDITIONAL" => "",
	"PROPERTY_CODES" => array(0=>"DETAIL_TEXT",1=>"33"),
	"EDIT_GROUPS"          => array(
		'1',
		'6',
		'7',
	),
	"VARIABLE_ALIASES" => array(
		"SECTION_ID" => "SECTION_ID",
		"ELEMENT_ID" => "CODE",
	)
	),
	false
);?><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>