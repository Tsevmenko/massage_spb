<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle('Клиника');//COption::GetOptionString("bitrix.sitemedicine", "site_personal_name"));
global $USER;
?>
    <h3 class="ta-center">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."m/include/mobileMainTop.php"),
            false
        );
        ?>
    </h3>
    <div class="clearfix">
        <div class="fleft">
            <a class="btn btn-green" href="/m/schedule/">Записаться на приём</a>
        </div>
        <?if (!$USER->IsAuthorized()):?>
            <div class="fright">
                <a href="/m/auth/" class="btn btn-login">Войти</a>
            </div>
        <?endif?>
    </div>
    <div class="menu content-menu clearfix">
        <?$APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
            "ROOT_MENU_TYPE" => "mobile-main",	// Тип меню для первого уровня
            "MENU_CACHE_TYPE" => "N",	// Тип кеширования
            "MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
            "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
            "MAX_LEVEL" => "1",	// Уровень вложенности меню
            "CHILD_MENU_TYPE" => "mobile-main",	// Тип меню для остальных уровней
            "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
            "DELAY" => "N",	// Откладывать выполнение шаблона меню
            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
            ),
            false
        );?>
    </div> <!-- .content-menu -->
    <div class="negative-margin">
        <section>
            <h3 class="content-header arr arr-down hideShowNext">Описание клиники</h3>
            <p class="well hideShow well">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR."m/include/mobileMainAbout.php"),
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
                        "DETAIL_URL" => "/m/advert/#ELEMENT_ID#/",
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