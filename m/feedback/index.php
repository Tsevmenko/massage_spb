<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Создать обращение");
?>

<?$APPLICATION->IncludeComponent("bitrix:iblock.element.add.form", "mobile_feedback", array(
		"IBLOCK_TYPE"                   => "registry",
		"IBLOCK_ID"                     => "16",
		"STATUS_NEW"                    => "N",
		"LIST_URL"                      => "",
		"USE_CAPTCHA"                   => "N",
		"USER_MESSAGE_EDIT"             => "",
		"USER_MESSAGE_ADD"              => "Спасибо, ваш вопрос добавлен",
		"DEFAULT_INPUT_SIZE"            => "50",
		"RESIZE_IMAGES"                 => "N",
		"PROPERTY_CODES"                => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "32",
			3 => "31",
			4 => "DATE_ACTIVE_FROM",
			5 => "DATE_ACTIVE_TO",
		),
		"PROPERTY_CODES_REQUIRED"       => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "32",
		),
		"GROUPS"                        => array(
			0 => "2",
		),
		"STATUS"                        => "ANY",
		"ELEMENT_ASSOC"                 => "CREATED_BY",
		"MAX_USER_ENTRIES"              => "100000",
		"MAX_LEVELS"                    => "100000",
		"LEVEL_LAST"                    => "Y",
		"MAX_FILE_SIZE"                 => "0",
		"PREVIEW_TEXT_USE_HTML_EDITOR"  => "N",
		"DETAIL_TEXT_USE_HTML_EDITOR"   => "N",
		"SEF_MODE"                      => "N",
		"SEF_FOLDER"                    => "/personal/",
		"CUSTOM_TITLE_NAME"             => "ФИО",
		"CUSTOM_TITLE_TAGS"             => "",
		"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
		"CUSTOM_TITLE_DATE_ACTIVE_TO"   => "",
		"CUSTOM_TITLE_IBLOCK_SECTION"   => "",
		"CUSTOM_TITLE_PREVIEW_TEXT"     => "Ваш вопрос",
		"CUSTOM_TITLE_PREVIEW_PICTURE"  => "",
		"CUSTOM_TITLE_DETAIL_TEXT"      => "",
		"CUSTOM_TITLE_DETAIL_PICTURE"   => "",
		"FIELDS_ORDER"                  => array(
			0 => "NAME",
			1 => "32",
			2 => "31",
			3 => "PREVIEW_TEXT",
			4 => "",
		)
	),
	false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>