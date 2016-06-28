<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Мастер записи");
$APPLICATION->SetPageProperty("description", "Мастер записи");
$APPLICATION->SetTitle("Запись на прием");
?>
<?$APPLICATION->IncludeComponent("medsite:record", "", array(
		"SERVICE_IBLOCK_TYPE" => "medservices",
		"IBLOCK_ID"         => "8",
		"ORG_IBLOCK_TYPE" => "foundations",
		"ORG_IB_ID"         => "4",
		"SPEC_IB_ID"        => "18",
		"SECTOR_IB_ID" => "6",
		"PLACEMENT_IB_ID" => "7",
		"DEPARTMENTS_IB_ID" => "5",
		"WIZ_HELP_LINK"     => "how_to_record.php",
		"PHONE_MASK" => "8 (999) 999-99-99",
		"PRICE_CODE" => array(
			0 => "DEF",
		),
		"PRICE_IBLOCK_TYPE" => "prices",
		"PRICE_IB_ID" => "9",
		"PRICE_VAT_INCLUDE" => "Y",
		"WEEK_LEAF"         => "1",
		"ORG_STEP_SHOW"     => "Y",
		'SHOW_SPECIALITY_SELECTION' => 'Y',
		'SHOW_SERVICE_SELECTION' => 'Y',
		'RECEPTION_GROUP' => array(
			0 => "7",
		),
		"GROUPS"            => array(
			0 => "1",
			1 => "7",
		),
		"BASKET_URL" => '/'.'personal/cart/',
		"EMPLOYES_GROUP"    => array(
			0 => "6",
		)
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>