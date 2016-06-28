<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<script type="text/javascript">
    BXMobileApp.onCustomEvent('onUserAuthChange');
    app.closeModalDialog();
</script>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>