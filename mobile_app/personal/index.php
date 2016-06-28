<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Кабинет");
$APPLICATION->SetPageProperty("description", "Кабинет");
$APPLICATION->SetTitle("Кабинет");
?>
<?$APPLICATION->IncludeComponent("medsite:talon", ".default", array(
	"SHOW_PROPERTIES" => array(
		0 => "EMPLOYEE",
		1 => "DEPARTAMENT",
		2 => "SERVICE",
		3 => "ORGANIZATION",
		4 => "PLACE",
	),
	"SHOW_SEARCH" => "N"
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>