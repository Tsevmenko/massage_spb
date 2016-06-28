<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
<? $APPLICATION->SetTitle("Талоны"); ?>

<?$APPLICATION->IncludeComponent("medsite:talon", ".default", array(
		"SHOW_PROPERTIES" => array(
			0 => "EMPLOYEE",
			1 => "DEPARTAMENT",
			2 => "SERVICE",
			3 => "ORGANIZATION",
			4 => "PLACE",
			5 => "",
			6 => "",
		),
		"RECORD_URL" => '/'.'services/list/',
		"BASKET_URL" => '/'.'personal/cart/',
		"ORDER_URL" => '/'.'personal/order/detail/#ORDER_ID#/',

	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>