<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "DISPLAY_DATE"              => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME"              => $arParams["DISPLAY_NAME"],
        "DISPLAY_PICTURE"           => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT"      => $arParams["DISPLAY_PREVIEW_TEXT"],
        "IBLOCK_TYPE"               => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID"                 => $arParams["IBLOCK_ID"],
        "FIELD_CODE"                => $arParams["DETAIL_FIELD_CODE"],
        "PROPERTY_CODE"             => $arParams["DETAIL_PROPERTY_CODE"],
        "DETAIL_URL"                => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL"               => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "META_KEYWORDS"             => $arParams["META_KEYWORDS"],
        "META_DESCRIPTION"          => $arParams["META_DESCRIPTION"],
        "BROWSER_TITLE"             => $arParams["BROWSER_TITLE"],
        "DISPLAY_PANEL"             => $arParams["DISPLAY_PANEL"],
        "SET_TITLE"                 => $arParams["SET_TITLE"],
        "SET_STATUS_404"            => $arParams["SET_STATUS_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "ADD_SECTIONS_CHAIN"        => $arParams["ADD_SECTIONS_CHAIN"],
        "ACTIVE_DATE_FORMAT"        => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
        "CACHE_TYPE"                => $arParams["CACHE_TYPE"],
        "CACHE_TIME"                => $arParams["CACHE_TIME"],
        "CACHE_GROUPS"              => $arParams["CACHE_GROUPS"],
        "USE_PERMISSIONS"           => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS"         => $arParams["GROUP_PERMISSIONS"],
        "DISPLAY_TOP_PAGER"         => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER"      => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE"               => $arParams["DETAIL_PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS"         => "N",
        "PAGER_TEMPLATE"            => $arParams["DETAIL_PAGER_TEMPLATE"],
        "PAGER_SHOW_ALL"            => $arParams["DETAIL_PAGER_SHOW_ALL"],
        "CHECK_DATES"               => $arParams["CHECK_DATES"],
        "ELEMENT_ID"                => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE"              => $arResult["VARIABLES"]["ELEMENT_CODE"],
        "IBLOCK_URL"                => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
        "USE_SHARE"                 => $arParams["USE_SHARE"],
        "SHARE_HIDE"                => $arParams["SHARE_HIDE"],
        "SHARE_TEMPLATE"            => $arParams["SHARE_TEMPLATE"],
        "SHARE_HANDLERS"            => $arParams["SHARE_HANDLERS"],
        "SHARE_SHORTEN_URL_LOGIN"   => $arParams["SHARE_SHORTEN_URL_LOGIN"],
        "SHARE_SHORTEN_URL_KEY"     => $arParams["SHARE_SHORTEN_URL_KEY"],
    ),
    $component
);?>

<?	if(!is_array($arParams["GROUPS"]))
		$arParams["GROUPS"] = array();

	$arGroups = $USER->GetUserGroupArray();
	$bAllowAccess = count(array_intersect($arGroups, $arParams["GROUPS"])) > 0 || $USER->IsAdmin();
?>
<?if ($bAllowAccess):?>
<?$APPLICATION->IncludeComponent("medsite:iblock.element.add.form.feedback", ".default", array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"STATUS_NEW" => "N",
	"LIST_URL" => "",
	"USE_CAPTCHA" => "N",
	"USER_MESSAGE_EDIT" => "",
	"USER_MESSAGE_ADD" => "",
	"DEFAULT_INPUT_SIZE" => "30",
	"RESIZE_IMAGES" => "N",
	"PROPERTY_CODES" => array(
		0 => "DETAIL_TEXT",
		1 => $arParams['STATE_FIELD'],
	),
	"PROPERTY_CODES_REQUIRED" => array(
	),
	"GROUPS" => $arParams['EDIT_GROUPS'],
	"STATUS" => "ANY",
	"ELEMENT_ASSOC" => "CREATED_BY",
	"MAX_USER_ENTRIES" => "100000",
	"MAX_LEVELS" => "100000",
	"LEVEL_LAST" => "Y",
	"MAX_FILE_SIZE" => "0",
	"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
	"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => SITE_DIR."for_staff/feedback/",
	"CUSTOM_TITLE_NAME" => "ФИО задавшего вопрос",
	"CUSTOM_TITLE_TAGS" => "",
	"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
	"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
	"CUSTOM_TITLE_IBLOCK_SECTION" => "",
	"CUSTOM_TITLE_PREVIEW_TEXT" => "Вопрос",
	"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
	"CUSTOM_TITLE_DETAIL_TEXT" => "Ответ",
	"CUSTOM_TITLE_DETAIL_PICTURE" => ""
	),
	$component
);?>
<?endif;?>

<p><a href="<?= $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"] ?>"><?= GetMessage("T_NEWS_DETAIL_BACK") ?></a>
</p>

