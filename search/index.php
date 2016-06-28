<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск");
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:search.page",
    "clear",
    Array(
        "AJAX_MODE"           => "N",
        "RESTART"             => "N",
        "CHECK_DATES"         => "N",
        "USE_TITLE_RANK"      => "N",
        "arrWHERE"            => Array("iblock_news", "iblock_library", "blog"),
        "arrFILTER"           => Array(),
        "SHOW_WHERE"          => "N",
        "PAGE_RESULT_COUNT"   => "50",
        "CACHE_TYPE"          => "A",
        "CACHE_TIME"          => "36000000",
        "PAGER_TITLE"         => "Результаты поиска",
        "PAGER_SHOW_ALWAYS"   => "N",
        "PAGER_TEMPLATE"      => "",
        "AJAX_OPTION_SHADOW"  => "Y",
        "AJAX_OPTION_JUMP"    => "N",
        "AJAX_OPTION_STYLE"   => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    )
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>