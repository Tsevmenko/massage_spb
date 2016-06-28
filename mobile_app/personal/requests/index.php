<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("BodyClass", "mt");
$APPLICATION->SetTitle("Мои обращения");
?><input class="btn btn-block" type="button" onclick="BXMobileApp.PageManager.loadPageStart({url: '<?=SITE_DIR?>mobile_app/feedback/'});" name="iblock_submit" value="Новое обращение"/>
<?$APPLICATION->IncludeComponent(
	"bitrix:news",
	"personal_feedback",
	Array(
		"DISPLAY_DATE"                    => "Y",
		"DISPLAY_PICTURE"                 => "Y",
		"DISPLAY_PREVIEW_TEXT"            => "Y",
		"USE_SHARE"                       => "N",
		"SEF_MODE"                        => "Y",
		"SEF_FOLDER"                      => "/mobile_app/personal/requests/",
		"AJAX_MODE"                       => "N",
		"IBLOCK_TYPE" => "registry",
		"IBLOCK_ID" => "16",
		"NEWS_COUNT"                      => "20",
		"USE_SEARCH"                      => "N",
		"USE_RSS"                         => "Y",
		"USE_RATING"                      => "N",
		"USE_CATEGORIES"                  => "N",
		"USE_REVIEW"                      => "N",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "arrFilter",
		"SORT_BY1"                        => "ACTIVE_FROM",
		"SORT_ORDER1"                     => "DESC",
		"SORT_BY2"                        => "SORT",
		"SORT_ORDER2"                     => "ASC",
		"CHECK_DATES"                     => "Y",
		"PREVIEW_TRUNCATE_LEN"            => "",
		"LIST_ACTIVE_DATE_FORMAT"         => "d.m.Y",
		"LIST_FIELD_CODE"                 => array(
		0 => "PREVIEW_TEXT",
		1 => "DATE_ACTIVE_TO",
		2 => "ACTIVE_TO",
		3 => "",
		),
		"LIST_PROPERTY_CODE"              => array(
		0 => "STATUS",
		1 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL"        => "N",
		"DISPLAY_NAME"                    => "N",
		"META_KEYWORDS"                   => "-",
		"META_DESCRIPTION"                => "-",
		"BROWSER_TITLE"                   => "-",
		"DETAIL_ACTIVE_DATE_FORMAT"       => "d.m.Y",
		"DETAIL_FIELD_CODE"               => array(),
		"DETAIL_PROPERTY_CODE"            => array(
		0 => "STATUS",
		1 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER"        => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER"     => "Y",
		"DETAIL_PAGER_TITLE"              => "Страница",
		"DETAIL_PAGER_TEMPLATE"           => "",
		"DETAIL_PAGER_SHOW_ALL"           => "Т",
		"SET_TITLE"                       => "Т",
		"SET_STATUS_404"                  => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN"       => "Т",
		"ADD_SECTIONS_CHAIN"              => "Т",
		"USE_PERMISSIONS"                 => "N",
		"CACHE_TYPE"                      => "A",
		"CACHE_TIME"                      => "3600",
		"CACHE_NOTES"                     => "",
		"CACHE_FILTER"                    => "N",
		"CACHE_GROUPS"                    => "Y",
		"DISPLAY_TOP_PAGER"               => "N",
		"DISPLAY_BOTTOM_PAGER"            => "Y",
		"PAGER_TITLE"                     => "Обращения",
		"PAGER_SHOW_ALWAYS"               => "N",
		"PAGER_TEMPLATE"                  => "",
		"PAGER_DESC_NUMBERING"            => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL"                  => "N",
		"VARIABLE_ALIASES"                => Array(
			"SECTION_ID" => "SECTION_ID",
			"ELEMENT_ID" => "ID"
		),
		"AJAX_OPTION_SHADOW"              => "Y",
		"AJAX_OPTION_JUMP"                => "N",
		"AJAX_OPTION_STYLE"               => "Y",
		"AJAX_OPTION_HISTORY"             => "N",
		"AJAX_OPTION_ADDITIONAL"          => "",
		"SEF_URL_TEMPLATES"               => array(
			"news"    => "",
			"section" => "",
			"detail"  => "#ELEMENT_ID#/",
		)
	),
	false
);?>


<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>