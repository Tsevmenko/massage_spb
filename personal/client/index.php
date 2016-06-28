<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><br/>
<link rel="stylesheet" href="style.css">
<?
global $arrFilter;
global $USER;
$arrFilter = array("PROPERTY_CLIENT" => $USER->GetID());
$APPLICATION->IncludeComponent("bitrix:news.list", "client_orders", Array(
  "ACTIVE_DATE_FORMAT" => "d.m.Y",  // Формат показа даты
    "ADD_SECTIONS_CHAIN" => "Y",  // Включать раздел в цепочку навигации
    "AJAX_MODE" => "N", // Включить режим AJAX
    "AJAX_OPTION_ADDITIONAL" => "", // Дополнительный идентификатор
    "AJAX_OPTION_HISTORY" => "N", // Включить эмуляцию навигации браузера
    "AJAX_OPTION_JUMP" => "N",  // Включить прокрутку к началу компонента
    "AJAX_OPTION_STYLE" => "Y", // Включить подгрузку стилей
    "CACHE_FILTER" => "N",  // Кешировать при установленном фильтре
    "CACHE_GROUPS" => "Y",  // Учитывать права доступа
    "CACHE_TIME" => "36000000", // Время кеширования (сек.)
    "CACHE_TYPE" => "A",  // Тип кеширования
    "CHECK_DATES" => "Y", // Показывать только активные на данный момент элементы
    "DETAIL_URL" => "", // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
    "DISPLAY_BOTTOM_PAGER" => "Y",  // Выводить под списком
    "DISPLAY_DATE" => "Y",  // Выводить дату элемента
    "DISPLAY_NAME" => "Y",  // Выводить название элемента
    "DISPLAY_PICTURE" => "Y", // Выводить изображение для анонса
    "DISPLAY_PREVIEW_TEXT" => "Y",  // Выводить текст анонса
    "DISPLAY_TOP_PAGER" => "N", // Выводить над списком
    "FIELD_CODE" => array(  // Поля
      0 => "",
      1 => "ID",
      2 => "CODE",
      3 => "XML_ID",
      4 => "NAME",
      5 => "TAGS",
      6 => "SORT",
      7 => "PREVIEW_TEXT",
      8 => "PREVIEW_PICTURE",
      9 => "DETAIL_TEXT",
      10 => "DETAIL_PICTURE",
      11 => "DATE_ACTIVE_FROM",
      12 => "ACTIVE_FROM",
      13 => "DATE_ACTIVE_TO",
      14 => "ACTIVE_TO",
      15 => "SHOW_COUNTER",
      16 => "SHOW_COUNTER_START",
      17 => "IBLOCK_TYPE_ID",
      18 => "IBLOCK_ID",
      19 => "IBLOCK_CODE",
      20 => "IBLOCK_NAME",
      21 => "IBLOCK_EXTERNAL_ID",
      22 => "DATE_CREATE",
      23 => "CREATED_BY",
      24 => "CREATED_USER_NAME",
      25 => "TIMESTAMP_X",
      26 => "MODIFIED_BY",
      27 => "USER_NAME",
      28 => "",
    ),
    "FILTER_NAME" => "arrFilter", // Фильтр
    "HIDE_LINK_WHEN_NO_DETAIL" => "N",  // Скрывать ссылку, если нет детального описания
    "IBLOCK_ID" => "29",  // Код информационного блока
    "IBLOCK_TYPE" => "massagist", // Тип информационного блока (используется только для проверки)
    "INCLUDE_IBLOCK_INTO_CHAIN" => "Y", // Включать инфоблок в цепочку навигации
    "INCLUDE_SUBSECTIONS" => "Y", // Показывать элементы подразделов раздела
    "MESSAGE_404" => "",  // Сообщение для показа (по умолчанию из компонента)
    "NEWS_COUNT" => "20", // Количество новостей на странице
    "PAGER_BASE_LINK_ENABLE" => "N",  // Включить обработку ссылок
    "PAGER_DESC_NUMBERING" => "N",  // Использовать обратную навигацию
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000", // Время кеширования страниц для обратной навигации
    "PAGER_SHOW_ALL" => "N",  // Показывать ссылку "Все"
    "PAGER_SHOW_ALWAYS" => "N", // Выводить всегда
    "PAGER_TEMPLATE" => ".default", // Шаблон постраничной навигации
    "PAGER_TITLE" => "Новости", // Название категорий
    "PARENT_SECTION" => "", // ID раздела
    "PARENT_SECTION_CODE" => "",  // Код раздела
    "PREVIEW_TRUNCATE_LEN" => "", // Максимальная длина анонса для вывода (только для типа текст)
    "PROPERTY_CODE" => array( // Свойства
      0 => "",
      1 => "TIME",
      2 => "DATE",
      3 => "CLIENT_EMAIL",
      4 => "CLIENT_PHONE",
      5 => "CLIENT_NAME",
      6 => "MASSAGIST",
      7 => "SMS_CODE",
      8 => "PRICE",
      9 => "",
    ),
    "SET_BROWSER_TITLE" => "Y", // Устанавливать заголовок окна браузера
    "SET_LAST_MODIFIED" => "N", // Устанавливать в заголовках ответа время модификации страницы
    "SET_META_DESCRIPTION" => "Y",  // Устанавливать описание страницы
    "SET_META_KEYWORDS" => "Y", // Устанавливать ключевые слова страницы
    "SET_STATUS_404" => "N",  // Устанавливать статус 404
    "SET_TITLE" => "Y", // Устанавливать заголовок страницы
    "SHOW_404" => "N",  // Показ специальной страницы
    "SORT_BY1" => "ACTIVE_FROM",  // Поле для первой сортировки новостей
    "SORT_BY2" => "SORT", // Поле для второй сортировки новостей
    "SORT_ORDER1" => "DESC",  // Направление для первой сортировки новостей
    "SORT_ORDER2" => "ASC", // Направление для второй сортировки новостей
    "COMPONENT_TEMPLATE" => ".default"
  ),
  false
);?><br>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>