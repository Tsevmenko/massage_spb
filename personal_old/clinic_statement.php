<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заявление на прикрепление к клинике");
?><?$APPLICATION->IncludeComponent("bitrix:form.result.new", "clinic_statement", Array(
                                                               "WEB_FORM_ID"            => "2", // ID веб-формы
                                                               "IGNORE_CUSTOM_TEMPLATE" => "N", // Игнорировать свой шаблон
                                                               "USE_EXTENDED_ERRORS"    => "N", // Использовать расширенный вывод сообщений об ошибках
                                                               "SEF_MODE"               => "N", // Включить поддержку ЧПУ
                                                               "SEF_FOLDER"             => "/personal/", // Каталог ЧПУ (относительно корня сайта)
                                                               "CACHE_TYPE"             => "A", // Тип кеширования
                                                               "CACHE_TIME"             => "3600", // Время кеширования (сек.)
                                                               "LIST_URL"               => "result_list.php", // Страница со списком результатов
                                                               "EDIT_URL"               => "result_edit.php", // Страница редактирования результата
                                                               "SUCCESS_URL"            => "", // Страница с сообщением об успешной отправке
                                                               "CHAIN_ITEM_TEXT"        => "", // Название дополнительного пункта в навигационной цепочке
                                                               "CHAIN_ITEM_LINK"        => "", // Ссылка на дополнительном пункте в навигационной цепочке
                                                               "VARIABLE_ALIASES"       => array(
                                                                   "WEB_FORM_ID" => "WEB_FORM_ID",
                                                                   "RESULT_ID"   => "RESULT_ID",
                                                               )
                                                           ),
                                   false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>