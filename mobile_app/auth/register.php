<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация нового пользователя");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"register",
	Array(
		"USER_PROPERTY_NAME" => "",
		"SHOW_FIELDS" => array("NAME", "PERSONAL_PHONE"),
		"REQUIRED_FIELDS" => array(),
		"AUTH" => "Y",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => "",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array()
	),
false
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>