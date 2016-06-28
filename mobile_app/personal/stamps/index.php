<? define("NEED_AUTH", true);require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Талоны"); ?>
<?$APPLICATION->IncludeComponent("medsite:talon", ".default", array(
	"SHOW_PROPERTIES" => array(
		0 => "EMPLOYEE",
		1 => "DEPARTAMENT",
		2 => "SERVICE",
		3 => "ORGANIZATION",
		4 => "PLACE",
	),
	"SHOW_SEARCH" => "Y"
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>