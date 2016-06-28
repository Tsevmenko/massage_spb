<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Адреса и участки");?>

<?$APPLICATION->IncludeComponent("bitrix:catalog.filter", "areas_filter", Array(
                                                            "IBLOCK_TYPE"     => "foundations", // Тип инфо-блока
                                                            "IBLOCK_ID"       => "6", // Инфо-блок
                                                            "FILTER_NAME"     => "arrFilter", // Имя выходящего массива для фильтрации
                                                            "FIELD_CODE"      => array( // Поля
                                                                0 => "",
                                                                1 => "",
                                                            ),
                                                            "PROPERTY_CODE"   => array( // Свойства
                                                                0 => "ADDRESS",
                                                                1 => "",
                                                            ),
                                                            "LIST_HEIGHT"     => "5", // Высота списков множественного выбора
                                                            "TEXT_WIDTH"      => "20", // Ширина однострочных текстовых полей ввода
                                                            "NUMBER_WIDTH"    => "5", // Ширина полей ввода для числовых интервалов
                                                            "CACHE_TYPE"      => "A", // Тип кеширования
                                                            "CACHE_TIME"      => "3600", // Время кеширования (сек.)
                                                            "CACHE_GROUPS"    => "N", // Учитывать права доступа
                                                            "SAVE_IN_SESSION" => "N", // Сохранять установки фильтра в сессии пользователя
                                                            "PRICE_CODE"      => "", // Тип цены
                                                        ),
                                 false
);?>




<?$APPLICATION->IncludeComponent("medsite:medsite.catalog.sections.top", ".default", array(
                                                                           "IBLOCK_TYPE"               => "foundations",
                                                                           "IBLOCK_ID"                 => "6",
                                                                           "SECTION_SORT_FIELD"        => "name",
                                                                           "SECTION_SORT_ORDER"        => "asc",
                                                                           "ELEMENT_SORT_FIELD"        => "sort",
                                                                           "ELEMENT_SORT_ORDER"        => "asc",
                                                                           "FILTER_NAME"               => "arrFilter",
                                                                           "PROPERTY_CODE"             => array(
                                                                               0 => "ADDRESS",
                                                                               1 => "",
                                                                           ),
                                                                           "SECTION_URL"               => "/employees/search_area.php",
                                                                           "DETAIL_URL"                => "",
                                                                           "BASKET_URL"                => "/personal/basket.php",
                                                                           "ACTION_VARIABLE"           => "action",
                                                                           "PRODUCT_ID_VARIABLE"       => "id",
                                                                           "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                                                           "PRODUCT_PROPS_VARIABLE"    => "prop",
                                                                           "SECTION_ID_VARIABLE"       => "SECTION_ID",
                                                                           "CACHE_TYPE"                => "A",
                                                                           "CACHE_TIME"                => "3600",
                                                                           "CACHE_FILTER"              => "N",
                                                                           "CACHE_GROUPS"              => "Y",
                                                                           "DISPLAY_COMPARE"           => "N",
                                                                           "PRICE_CODE"                => array(),
                                                                           "USE_PRICE_COUNT"           => "N",
                                                                           "SHOW_PRICE_COUNT"          => "1",
                                                                           "PRICE_VAT_INCLUDE"         => "N",
                                                                           "PRODUCT_PROPERTIES"        => array(),
                                                                           "USE_PRODUCT_QUANTITY"      => "N"
                                                                       ),
                                 false
);?>


<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>