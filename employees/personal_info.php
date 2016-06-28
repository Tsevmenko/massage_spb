<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Информация о сотруднике");?><?
?> <?
if (!isset($_GET['employee'])||!is_numeric($_GET['employee']))
{
LocalRedirect('/employees/');
}
?>
<?$APPLICATION->IncludeComponent("medsite:medsite.system.person", "personal_list1", Array(
	"USER" => $_GET["employee"],	// Пользователь
		"IBLOCK_ID" => "8",	// Инфоблок услуг
		"SHOW_SERVICES" => "Y",	// Показать список услуг
		"SCHEDULE_LINK" => "/schedule/record_wizard.php?STEP=service&SHOW=employee&EMPLOYEE=#ID#",	// Ссылка на расписание сотрудника
		"USER_PROPERTY" => array(	// Вывести пользовательские свойства
			0 => "FULL_NAME",
			1 => "PERSONAL_PHONE",
			2 => "PERSONAL_NOTES",
			3 => "UF_DEPARTMENT",
		),
		"SHEDULES_BLOCK" => "#SHEDULES_BLOCK_ID#",
		"DEFAULT_SERVICE" => ""
	),
	false
);
?>

<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>