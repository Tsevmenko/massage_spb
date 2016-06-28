<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("BodyClass", "menu-page");?>
<script type="text/javascript">
    BXMobileApp.UI.Page.Scroll.setEnabled(false);
</script>
<?
$APPLICATION->IncludeComponent(
	'bitrix:mobileapp.menu',
	'mobile',
	array("MENU_FILE_PATH"=>SITE_DIR."mobile_app/.mobile_menu.php", "MENU_ID" => "left-menu"),
	false,
	Array('HIDE_ICONS' => 'Y'));
?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>