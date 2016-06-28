<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle('Клиника');//COption::GetOptionString("bitrix.sitemedicine", "site_personal_name"));
global $USER;
?>

    <h3 class="ta-center">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."mobile_app/include/mobileMainTop.php"),
            false
        );
        ?>
    </h3>
    <div class="negative-margin">
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "slider_main",
            array(
                "SET_TITLE" => "N",
                "COMPONENT_TEMPLATE" => "slider_main",
                "IBLOCK_TYPE" => "additional_info",
                "IBLOCK_ID" => "#GALLERY_BLOCK_ID#",
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
    </div>
    <div class="clearfix btn-holder">
        <div class="fleft">
            <div class="btn btn-green" onclick="BXMobileApp.PageManager.loadPageStart({url: '/mobile_app/schedule/'});">Запись на приём</div>
        </div>
        <?if (!$USER->IsAuthorized()):?>
            <div class="fright">
                <div onclick="BXMobileApp.PageManager.loadPageModal({url: '/mobile_app/auth/'});" class="btn btn-login">Войти</div>
            </div>
        <?endif?>
    </div>
    <div class="negative-margin">
        <section>
            <h3 class="content-header arr arr-down hideShowNext">Описание клиники</h3>
            <p class="well hideShow well">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR."mobile_app/include/mobileMainAbout.php"),
                    false
                );
                ?>
            </p>
        </section>
        <section>
            <h3 class="content-header arr arr-down hideShowNext">Объявления</h3>
            <div class="hideShow">
                <?$APPLICATION->IncludeComponent("bitrix:news.list", "advert_list", array(
                    "IBLOCK_TYPE" => "news",
                    "IBLOCK_ID" => "2",
                    "NEWS_COUNT" => "3",
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
                    "DETAIL_URL" => "/mobile_app/advert/#ELEMENT_ID#/",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "FULL",
                    "SET_TITLE" => "N",
                    "SET_STATUS_404" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                    "ADD_SECTIONS_CHAIN" => "Y",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Объявления",
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
            </div>
        </section>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>