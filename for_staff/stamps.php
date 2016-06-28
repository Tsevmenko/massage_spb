<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
<? $APPLICATION->SetTitle("Талоны"); ?>

<?$APPLICATION->IncludeComponent("medsite:talon_admin", ".default", array(
		"IBLOCK_TYPE"         => "foundations",
		'IBLOCK_ID'          => '6',
		"SHOW_PROPERTIES"        => array(
			0 => "EMPLOYEE",
			1 => "DEPARTAMENT",
			2 => "SERVICE",
			3 => "ORGANIZATION",
			4 => "PLACE",
			5 => "4",
			6 => "5",
		),
		"EMAIL_PROPERTY"         => "5",
		'SHOW_PER_PAGE'          => '10',
		"CAN_SEE_FOREIGN_GROUPS" => array(
			0 => "1",
			1 => "7",
		),
		"STAUS_PROPERTY"         => "1"
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>