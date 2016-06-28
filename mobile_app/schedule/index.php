<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Мастер записи");
$APPLICATION->SetPageProperty("description", "Мастер записи");
$APPLICATION->SetTitle("Запись на приём");
?>
<?$APPLICATION->IncludeComponent("medsite:record", "mobile", array(
		"SERVICE_IBLOCK_TYPE" => "medservices",
		"IBLOCK_TYPE"       => "registry",
		"IBLOCK_ID"         => "8",
		"ORG_IBLOCK_TYPE" => "foundations",
        "ORG_IB_ID"         => "#ORG_IBLOCK_ID#",
        "SPEC_IB_ID"        => "#SPEC_IBLOCK_ID#",
        "DEPARTMENTS_IB_ID" => "#DEPARTMENT_IBLOCK_ID#",
		"WIZ_HELP_LINK"     => "#",
		"WEEK_LEAF"         => "3",
		"PHONE_MASK" => "8 (999) 999-99-99",
		"ORG_STEP_SHOW"     => "Y",
        "GROUPS"            => array(
            0 => "1",
            1 => "7",
        ),
        "EMPLOYES_GROUP"    => array(
            0 => "6",
        )
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>