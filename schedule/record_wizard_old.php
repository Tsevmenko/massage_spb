<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Мастер записи");
$APPLICATION->SetPageProperty("description", "Мастер записи");
$APPLICATION->SetTitle("Запись на прием");
?>
<?$APPLICATION->IncludeComponent("medsite:record_wizard", "modern", array(
		"IBLOCK_TYPE"               => "registry",
		"IBLOCK_ID"                 => "8",
		"ORG_IB_ID"                 => "4",
		"SPEC_IB_ID"                => "5",
		"REC_WARNING"               => "Уважаемый пользователь! Нажав на кнопку «Все верно» вы автоматически даете согласие на обработку ваших персональных данных. Кроме того, вы принимаете ответственность за правильность предоставления данных.",
		"WIZ_BANNER"                => "N",
		"SHOW_STEPS_INFO"           => "Y",
		"WIZ_LINK"                  => "/schedule/record_wizard.php",
		"WIZ_HELP_LINK"             => "new_record_wizard_help.php",
		"DAY_START"                 => "8",
		"WEEK_LEAF"                 => "1",
		"DAY_END"                   => "17",
		"WEB_STEP"                  => "60",
		"SERVICES_SHOW"             => "Y",
		"ORG_STEP_SHOW"             => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN"        => "Y",
		"USER_LINK"                 => "/employees/personal_info.php?empl=#ID#",
		"SERV_LINK"                 => "/schedule/calendar.php?serv=#ID#",
		"STRUCTURE_LINK"            => "/employees/structure.php",
		"STRUCTURE_FILTER"          => "structure",
		"FORM_PURPOSE_OF_VISIT"     => "Y",
		"FORM_OMS_POLISY"           => "Y",
		"FORM_ADRESS"               => "Y",
		"SPECIALIST_SELECT_TYPE"    => "radio",
		"SERVICE_SELECT_TYPE"       => "select",
		"USE_HTML_EDITOR"           => "N",
		"HIDE_PROPERTIES"           => array(
			0 => "PATIENT",
			1 => "RECEPTION",
		),
		"PROPERTY_PHONE"            => "MOBILE_PHONE",
		"PHONE_REQUIRED"            => "NOT_REQUIRED",
		"MAX_FILE_SIZE"             => "0",
		"CHECK_EMAIL"               => "EMAIL",
		"USER_GROUP_PATIENT"        => array(),
		'RECEPTION_GROUP' => array(
			1 => "7",
		),
		"GROUPS"                    => array(
			0 => "1",
			1 => "7",
		)
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>