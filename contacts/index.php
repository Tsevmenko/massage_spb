<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Контакты");
$APPLICATION->SetPageProperty("description", "Контакты");
$APPLICATION->SetTitle("Контакты");
?>

<?if (IsModuleInstalled('bitrix.map')):?>
	<?$APPLICATION->IncludeComponent("bitrix:map.map", "contacts", Array(
	"IBLOCK_TYPE" => "foundations",	// Тип информационного блока (используется только для проверки)
	"IBLOCK_ID" => "4",	// Код информационного блока
	"ELEMENTS_COUNT" => "500",	// Максимальное количество элементов
	"SORT_SECTIONS_BY1" => "NAME",	// Поле для первой сортировки разделов
	"SORT_SECTIONS_ORDER1" => "ASC",	// Направление для первой сортировки разделов
	"SORT_SECTIONS_BY2" => "SORT",	// Поле для второй сортировки разделов
	"SORT_SECTIONS_ORDER2" => "ASC",	// Направление для второй сортировки разделов
	"SORT_BY1" => "NAME",	// Поле для первой сортировки элементов
	"SORT_ORDER1" => "ASC",	// Направление для первой сортировки элементов
	"SORT_BY2" => "SORT",	// Поле для второй сортировки элементов
	"SORT_ORDER2" => "ASC",	// Направление для второй сортировки элементов
	"FILTER_NAME" => "arClinicFilter",	// Фильтр
	"SECTION_FIELDS" => array(	// Свойства разделов
		0 => "",
		1 => "",
	),
	"FIELD_CODE" => array(	// Поля элементов
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(	// Свойства элементов
		0 => "",
		1 => "",
	),
	"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
	"DETAIL_URL" => '/contacts/organization/#ELEMENT_ID#/',	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"PREVIEW_TRUNCATE_LEN" => "100",	// Максимальная длина анонса для вывода (только для типа текст)
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
	"PARENT_SECTION" => "",	// ID раздела
	"PARENT_SECTION_CODE" => "",	// Код раздела
	"MAP_TYPE" => "google",	// Тип карты
	"DATA_TYPE" => "objects",	// Тип данных
	"REPLACE_RULES" => "Y",	// Заменять стандартную прокрутку
	"MAP_HEIGHT" => "550",	// Высота карты
	"ICONPOS_PROP_CODE" => "UF_ICON_POS",	// Позиция иконки
	"PARENT_PROP_CODE" => "",	// Родительский элемент
	"LATITUDE_PROP_CODE" => "LAT",	// Широта
	"LONGITUDE_PROP_CODE" => "LON",	// Долгота
	"ADDRESS_PROP_CODE" => "ADRESS",	// Адрес
	"PHONE_PROP_CODE" => "PHONE",	// Телефон
	"OPENING_PROP_CODE" => "WORK_TIME",	// Режим работы
	"NO_CAT_ICONS" => 'Y',
	"LINK_PROP_CODE" => "MEDSITE_ID",	// Сайт
	),
	false
);?>
<?else:?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"org_list",
		Array(
			"DISPLAY_TITLE" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"AJAX_MODE" => "N",
			"IBLOCK_TYPE" => 'foundations',
			"IBLOCK_ID" => '4',
			"NEWS_COUNT" => "32",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "arClinicFilter",
			"FIELD_CODE" => array("ID","CODE","XML_ID","NAME","TAGS","SORT","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE","DATE_ACTIVE_FROM","ACTIVE_FROM","DATE_ACTIVE_TO","ACTIVE_TO","SHOW_COUNTER","SHOW_COUNTER_START","IBLOCK_TYPE_ID","IBLOCK_ID","IBLOCK_CODE","IBLOCK_NAME","IBLOCK_EXTERNAL_ID","DATE_CREATE","CREATED_BY","CREATED_USER_NAME","TIMESTAMP_X","MODIFIED_BY","USER_NAME"),
			"PROPERTY_CODE" => array("ADRESS","MEDSITE_ID","PHONE"),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => '/contacts/organization/#ELEMENT_ID#/',
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_NOTES" => "",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"AJAX_OPTION_SHADOW" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			'HIDE_ER_ICON' => 'Y',
			"AJAX_OPTION_ADDITIONAL" => ""
		),
		$component,
		Array("HIDE_ICONS"=>"Y")
	);?>
<?endif?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>