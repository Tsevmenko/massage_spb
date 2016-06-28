<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>
	<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>

    <div class="content">
        <div class="col col-6"><b><?=GetMessage("AUTH_GET_CHECK_STRING")?></b></div>
    </div>
    <div class="content">
        <div class="col col-2"><?=GetMessage("AUTH_LOGIN")?></div>
        <div class="col col-6"><input class="input" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />&nbsp;<?=GetMessage("AUTH_OR")?></div>
    </div>
    <div class="content">
        <div class="col col-2"><?=GetMessage("AUTH_EMAIL")?></div>
        <div class="col col-6"><input class="input" type="text" name="USER_EMAIL" maxlength="255" /></div>
    </div>
    <div class="content mt10">
        <div class="col col-2"></div>
        <div class="col col-6"><input class="btn" type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" /></div>
    </div>
    <div class="content mt10">
        <div class="col col-2"></div>
		<div class="col col-6"><a href="/auth/"><b><?=GetMessage("AUTH_AUTH")?></b></a></div>
    </div>
</form>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
