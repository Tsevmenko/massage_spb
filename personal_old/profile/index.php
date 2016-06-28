<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки пользователя");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.profile", 
	"myprofile", 
	array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(
			0 => "UF_QUALIFICATION",
		),
		"SEND_INFO" => "N",
		"CHECK_RIGHTS" => "N",
		"USER_PROPERTY_NAME" => "",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "myprofile"
	),
	false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>