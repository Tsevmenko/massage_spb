<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Специалисты");?><?
?> <?
if (!isset($_GET['employee'])||!is_numeric($_GET['employee']))
{
LocalRedirect('/mobile_app/employees/');
}
?>

<div class="negative-margin clearfix">
	<section>
						
		<?$APPLICATION->IncludeComponent("medsite:medsite.system.person", "personal_page", array(
	"USER" => $_GET["employee"],
	"IBLOCK_ID" => "8",
	"SHOW_SERVICES" => "Y",
	"USER_INFO_LINK" => "/mobile_app/employees/personal_info.php",
	"USER_PROPERTY" => array(
		0 => "EMAIL",
		1 => "WORK_PHONE",
	),
	"SHEDULES_LINK" => "/mobile_app/schedule/?STEP=service&EMPLOYEE=#ID#"
	),
	false
);
			?>

	</section>			
</div> <!-- .negative-margin -->
<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>