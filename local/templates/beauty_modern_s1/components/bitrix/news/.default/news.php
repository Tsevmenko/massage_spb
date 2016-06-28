<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?
$rssIcon = '';
if($arParams["USE_RSS"]=="Y"):
?>
	<?
	if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" />');
	?>
    <? $rssIcon = '<a href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" title="rss" target="_self"><img alt="RSS" src="'.$templateFolder.'/images/gif-light/feed-icon-16x16.gif" border="0" align="right" /></a>'; ?>
<? endif; ?>

<?if($arParams["USE_SEARCH"]=="Y"):?>
<?=GetMessage("SEARCH_LABEL")?><?$APPLICATION->IncludeComponent(
	"bitrix:search.form",
	"flat",
	Array(
		"PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"]
	),
	$component
);?>
<br />
<?endif?>
<?if($arParams["USE_FILTER"]=="Y"):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.filter",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
	),
	$component
);
?>
<br />
<?endif?>
<?$APPLICATION->IncludeComponent("bitrix:news.list", "services", Array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],	// Код информационного блока
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],	// Количество новостей на странице
		"SORT_BY1" => $arParams["SORT_BY1"],	// Поле для первой сортировки новостей
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],	// Направление для первой сортировки новостей
		"SORT_BY2" => $arParams["SORT_BY2"],	// Поле для второй сортировки новостей
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],	// Направление для второй сортировки новостей
		"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],	// Поля
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],	// Свойства
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],	// Устанавливать заголовок страницы
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],	// Устанавливать в заголовках ответа время модификации страницы
		"MESSAGE_404" => $arParams["MESSAGE_404"],	// Сообщение для показа (по умолчанию из компонента)
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],	// Устанавливать статус 404
		"SHOW_404" => $arParams["SHOW_404"],	// Показ специальной страницы
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],	// Включать инфоблок в цепочку навигации
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],	// Тип кеширования
		"CACHE_TIME" => $arParams["CACHE_TIME"],	// Время кеширования (сек.)
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],	// Учитывать права доступа
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],	// Выводить под списком
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],	// Название категорий
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],	// Шаблон постраничной навигации
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],	// Выводить всегда
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],	// Показывать ссылку "Все"
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],	// Включить обработку ссылок
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],	// Выводить дату элемента
		"DISPLAY_NAME" => "Y",	// Выводить название элемента
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],	// Выводить изображение для анонса
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],	// Выводить текст анонса
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],	// Формат показа даты
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],	// Фильтр
		"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],	// Скрывать ссылку, если нет детального описания
		"CHECK_DATES" => $arParams["CHECK_DATES"],	// Показывать только активные на данный момент элементы
		"RSS_ICON" => $rssIcon
	),
	false
);?>