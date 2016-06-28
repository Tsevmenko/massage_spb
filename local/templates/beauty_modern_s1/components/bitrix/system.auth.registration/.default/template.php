<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2013 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="bx-auth">
<?/*if (!empty($arParams["~AUTH_RESULT"])) {?>
    <div class="alert alert-error"><?= ShowMessage($arParams["~AUTH_RESULT"]); ?></div>
<? }*/ ?>
<?ShowMessage($arParams["~AUTH_RESULT"]); ?>
<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<p><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
<?endif?>
<noindex>
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />

<h3><?=GetMessage("AUTH_REGISTER")?>123</h3>
<div class="content">
    <div class="col col-2"><?=GetMessage("AUTH_NAME")?></div>
    <div class="col col-6"><input class="input" type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>" /></div>
</div>
<div class="content">
    <div class="col col-2"><?=GetMessage("AUTH_LAST_NAME")?></div>
    <div class="col col-6"><input class="input" type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>" /></div>
</div>
<div class="content">
    <div class="col col-2"><span class="starrequired">*</span><?=GetMessage("AUTH_LOGIN_MIN")?></div>
    <div class="col col-6"><input class="input" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" /></div>
</div>
<div class="content">
    <div class="col col-2"><span class="starrequired">*</span><?=GetMessage("AUTH_PASSWORD_REQ")?></div>
    <div class="col col-6">
        <input class="input" type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" />
        <?if($arResult["SECURE_AUTH"]):?>
                    <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
                        <div class="bx-auth-secure-icon"></div>
                    </span>
                    <noscript>
                    <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
                        <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                    </span>
                    </noscript>
        <script type="text/javascript">
        document.getElementById('bx_auth_secure').style.display = 'inline-block';
        </script>
        <?endif?>
    </div>
</div>
<div class="content">
    <div class="col col-2"><span class="starrequired">*</span><?=GetMessage("AUTH_CONFIRM")?></div>
    <div class="col col-6"><input class="input" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" /></div>
</div>
<div class="content">
    <div class="col col-2"><span class="starrequired">*</span><?=GetMessage("AUTH_EMAIL")?></div>
    <div class="col col-6"><input class="input" type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" /></div>
</div>

<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<div class="content"><div class="col col-3"><?=strLen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></div></div>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
	<div class="content">
        <div class="col col-2"><?if ($arUserField["MANDATORY"]=="Y"):?><span class="required">*</span><?endif;?>
		<?=$arUserField["EDIT_FORM_LABEL"]?>:</div>
        <div class="col col-6">
			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?></div></div>
	<?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>
        <div class="content">
            <div class="col col-6"><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></div>
        </div>
        <div class="content">
            <div class="col col-2"><span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:</div>
            <div class="col col-6">
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                <br>
                <input class="input mt10" style="width:100px" type="text" name="captcha_word" maxlength="50" value="" />
            </div>
        </div>
		<?
	}
	/* CAPTCHA */
	?>
	<input class="btn" type="submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>

<p>
<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_AUTH")?></b></a>
</p>

</form>
</noindex>
<script type="text/javascript">
document.bform.USER_NAME.focus();
</script>

<?endif?>
</div>