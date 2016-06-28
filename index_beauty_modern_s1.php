<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Главная страница");
?>
<div class="white-box col-margin-top">
    <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"slider_main",
	array(
		"SET_TITLE" => "N",
		"COMPONENT_TEMPLATE" => "slider_main",
		"IBLOCK_TYPE" => "additional_info",
		"IBLOCK_ID" => "17",
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
			0 => "",
			1 => "REAL_PICTURE",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>

</div> <!-- .white-box col-margin-top -->

<div class="white-box col-margin-top">
    <div class="tabs">
        <ul class="tabs-switchers">
            <li class="tabs-switcher active">Специальности</li>
            <li class="tabs-switcher">Направления</li>
            <li class="tabs-switcher">Услуги</li>
        </ul>
        <div class="tabs-item active">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:news.list", "main_specialties",
                array(
                "SET_TITLE" => "N",
                "COMPONENT_TEMPLATE" => "slider_main",
                "IBLOCK_TYPE" => "foundations",
                "IBLOCK_ID" => "18",
                "NEWS_COUNT" => "50",
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
                    0 => "",
                ),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_BROWSER_TITLE" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_LAST_MODIFIED" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "SET_STATUS_404" => "N",
                "SHOW_404" => "N",
                "MESSAGE_404" => ""
                ), false
            );
            ?>
        </div>
        <div class="tabs-item">
            <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH"           => SITE_DIR."includes/our_destinations.php"),
            false);?>
        </div>
        <div class="tabs-item">
            <?
                $GLOBALS['arrServiceFilter'] = array('!PROPERTY_SHOW_IN_MAIN'=>false)
            ?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "main_service",
                Array(
                    "IBLOCK_TYPE"                     => 'medservices',
                    "IBLOCK_ID"                       => "8",
                    "NEWS_COUNT"                      => 5,
                    "SORT_BY1"                        => "SORT",
                    "SORT_ORDER1"                     => "ASC",
                    "SORT_BY2"                        => "NAME",
                    "SORT_ORDER2"                     => "ASC",
                    "FILTER_NAME"                     => "arrServiceFilter",
                    "FIELD_CODE"                      => array(0 => "", 1 => "", 2 => "",),
                    "PROPERTY_CODE"                   => array(0 => "", 1 => "", 2 => "",),
                    "AJAX_MODE"                       => "N",
                    "AJAX_OPTION_SHADOW"              => "N",
                    "AJAX_OPTION_JUMP"                => "N",
                    "AJAX_OPTION_STYLE"               => "N",
                    "AJAX_OPTION_HISTORY"             => "N",
                    "CACHE_TYPE"                      => "A",
                    "CACHE_TIME"                      => 36000000,
                    "CACHE_FILTER"                    => "N",
                    "PREVIEW_TRUNCATE_LEN"            => "",
                    "ACTIVE_DATE_FORMAT"              => "d.m.Y",
                    "DISPLAY_PANEL"                   => "N",
                    "SET_TITLE"                       => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
                    "ADD_SECTIONS_CHAIN"              => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
                    "PARENT_SECTION"                  => "",
                    "DISPLAY_TOP_PAGER"               => "N",
                    "DISPLAY_BOTTOM_PAGER"            => "N",
                    "PAGER_TITLE"                     => "",
                    "PAGER_SHOW_ALWAYS"               => "N",
                    "PAGER_TEMPLATE"                  => "",
                    "PAGER_DESC_NUMBERING"            => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "DISPLAY_DATE"                    => "N",
                    "DISPLAY_NAME"                    => "Y",
                    "DISPLAY_PICTURE"                 => "N",
                    "DISPLAY_PREVIEW_TEXT"            => "N",
                    "INTRANET_TOOLBAR"                => "N"
                ),
                false
            );?>
        </div>
    </div>
</div>

<div class="content col-margin-top">
    <?$APPLICATION->IncludeComponent(
        "bitrix:advertising.banner",
        "main_top",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "TYPE" => "MAIN_TOP",
            "NOINDEX" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0"
        ),
        false
    );?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:advertising.banner",
        "main_top",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "TYPE" => "MAIN_TOP",
            "NOINDEX" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0"
        ),
        false
    );?>
</div> <!-- .content col-margin-top -->

<div class="content mt30">
    <div class="col col-6">
        <div class="h3 m0">Новости и объявления</div>
    </div> <!-- .col col-6 -->
    <div class="col col-6 ta-right">
        <a href="<?= SITE_DIR ?>about/news/" class="border-link fz16">Новости</a>
        <a href="<?= SITE_DIR ?>about/advert/" class="border-link fz16 ml20">Объявления</a>
    </div> <!-- .col col-6 ta-rigth -->
</div> <!-- .content mt30 -->

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"advert_main", 
	array(
		"COMPONENT_TEMPLATE" => "advert_main",
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "2",
		"NEWS_COUNT" => "2",
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
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Объявления",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"news_main",
	array(
		"COMPONENT_TEMPLATE" => "advert_main",
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "1",
		"NEWS_COUNT" => "2",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>

<div class="mt30">
    <?$APPLICATION->IncludeComponent(
        "bitrix:advertising.banner",
        "main_wide",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "TYPE" => "MAIN_WIDE",
            "NOINDEX" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0"
        ),
        false
    );?>
</div> <!-- .mt30 -->


<div class="content col-margin-top">
    <div class="col col-6">
        <div class="h3 m0">Страховые программы</div>
    </div> <!-- .col col-6 -->
    <div class="col col-6 ta-right">
        <a href="<?= SITE_DIR ?>personal/insurance-companies/" class="border-link fz16">О страховых медицинских организациях</a>
    </div> <!-- .col col-6 ta-rigth -->
</div> <!-- .content col-margin-top -->
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"insurance_programm",
	array(
		"IBLOCK_TYPE" => "additional_info",
		"IBLOCK_ID" => "25",
		"COMPONENT_TEMPLATE" => "insurance_programm",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"COUNT_ELEMENTS" => "N",
		"TOP_DEPTH" => "1",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"MAX_COUNT_SECTION" => "8"
	),
	false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>