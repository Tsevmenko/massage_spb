<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Прсмотр результатов анализа");
?><?$APPLICATION->IncludeComponent("bitrix:news.detail", "analysis_detail", array(
                                                           "IBLOCK_TYPE"               => "personal_card",
                                                           "IBLOCK_ID"                 => "13",
                                                           "ELEMENT_ID"                => $_REQUEST["ELEMENT_ID"],
                                                           "ELEMENT_CODE"              => "",
                                                           "CHECK_DATES"               => "Y",
                                                           "FIELD_CODE"                => array(
                                                               0 => "DATE_CREATE",
                                                               1 => "",
                                                           ),
                                                           "PROPERTY_CODE"             => array(
                                                               0 => "PATIENT_ID",
                                                               1 => "",
                                                           ),
                                                           "IBLOCK_URL"                => "analysis_results.php",
                                                           "AJAX_MODE"                 => "N",
                                                           "AJAX_OPTION_SHADOW"        => "Y",
                                                           "AJAX_OPTION_JUMP"          => "N",
                                                           "AJAX_OPTION_STYLE"         => "Y",
                                                           "AJAX_OPTION_HISTORY"       => "N",
                                                           "CACHE_TYPE"                => "A",
                                                           "CACHE_TIME"                => "3600",
                                                           "CACHE_GROUPS"              => "Y",
                                                           "META_KEYWORDS"             => "-",
                                                           "META_DESCRIPTION"          => "-",
                                                           "BROWSER_TITLE"             => "NAME",
                                                           "SET_TITLE"                 => "Y",
                                                           "SET_STATUS_404"            => "N",
                                                           "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                           "ADD_SECTIONS_CHAIN"        => "N",
                                                           "ACTIVE_DATE_FORMAT"        => "d.m.Y",
                                                           "USE_PERMISSIONS"           => "N",
                                                           "DISPLAY_TOP_PAGER"         => "N",
                                                           "DISPLAY_BOTTOM_PAGER"      => "Y",
                                                           "PAGER_TITLE"               => "Страница",
                                                           "PAGER_TEMPLATE"            => "",
                                                           "PAGER_SHOW_ALL"            => "Y",
                                                           "DISPLAY_DATE"              => "N",
                                                           "DISPLAY_NAME"              => "Y",
                                                           "DISPLAY_PICTURE"           => "Y",
                                                           "DISPLAY_PREVIEW_TEXT"      => "Y",
                                                           "USE_SHARE"                 => "N",
                                                           "AJAX_OPTION_ADDITIONAL"    => ""
                                                       ),
                                   false
);?> <? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>