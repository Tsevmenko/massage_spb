<?
if (isset($_POST['AJAX']) && $_POST['AJAX']=='Y') {
	define("PUBLIC_AJAX_MODE", true);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Мастер записи");
$APPLICATION->SetPageProperty("description", "Мастер записи");
$APPLICATION->SetTitle("Запись на прием");
?> <?$APPLICATION->IncludeComponent(
	"medsite:record.infomat.ajax",
	"",
	Array(
		"SERVICE_IBLOCK_TYPE" => "medservices",
		"IBLOCK_ID" => "8",
		"ORG_IBLOCK_TYPE" => "foundations",
		"ORG_IB_ID" => "4",
		"SPEC_IB_ID" => "18",
		"DEPARTMENTS_IB_ID" => "5",
		"ORG_STEP_SHOW" => "Y",
		'ORG_ADDRESS' => '5',
		'ORG_LAT' => '10',
		'ORG_LON' => '11',
		'ORG_TRANSPORT' => '12',
		"MAIN_PAGE_LINK" => "/infomat/",
		"WEEK_LEAF" => "3",
		"REQUIRED_PROPERTIES" => array(
			0 => "NAME",
			1 => "LAST_NAME",
			2 => "PHONE",
		),
		"GROUPS"            => array(
			0 => "1",
			1 => "#REG_GROUP_ID#",
		),
		"EMPLOYES_GROUP"    => array(
			0 => "#EMPLOYEE_GROUP_ID#",
		),
		"PHONE_MASK" => "8 (999) 999-99-99"
	)
);?>

<?
if (isset($_POST['AJAX']) && $_POST['AJAX']=='Y') {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>