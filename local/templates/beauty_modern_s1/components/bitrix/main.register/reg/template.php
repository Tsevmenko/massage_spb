<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pwdwidget.css">
<style>
	.formcontainer
{
text-align:left;
width:330px;
border-top: 1px solid;
border-bottom: 1px solid;
padding:10px;
margin: auto;
}
.para
{
margin-bottom: 10px;
}
</style>
<div class="bx-auth-reg">

<?if($USER->IsAuthorized()):?>

<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>

<?else:?>
<?
if (count($arResult["ERRORS"]) > 0):
    foreach ($arResult["ERRORS"] as $key => $error)
        if (intval($key) == 0 && $key !== 0) 
            $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

    ShowError(implode("<br />", $arResult["ERRORS"]));

elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
?>
<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
<?endif?>

<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
<?
if($arResult["BACKURL"] <> ''):
?>
    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
endif;
?>

<table>
    <thead>
        <tr>
            <td colspan="2"><b><?=GetMessage("AUTH_REGISTER")?></b></td>
        </tr>
    </thead>
    <tbody>
<?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
    <?if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true):?>
        <tr>
            <td><?echo GetMessage("main_profile_time_zones_auto")?><?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="starrequired">*</span><?endif?></td>
            <td>
                <select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
                    <option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
                    <option value="Y"<?=$arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
                    <option value="N"<?=$arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?echo GetMessage("main_profile_time_zones_zones")?></td>
            <td>
                <select name="REGISTER[TIME_ZONE]"<?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>>
        <?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
                    <option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialcharsbx($tz_name)?></option>
        <?endforeach?>
                </select>
            </td>
        </tr>
    <?else:?>
        <tr>
            <td><?
    switch ($FIELD)
    {
        case "PASSWORD":
            ?>
            <div class="content">
				<div style="min-width: 220px; font-size: 17px;" class="col col-2"><span class="starrequired">*</span>Пароль:</div>
                <div class="col col-6">
                    <input class="input" type="password" id="regpwd" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" maxlength="50">
                </div>
				<div class='pwdwidgetdiv' id='thepwddiv'></div>
            </div>

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
<?
            break;
        case "CONFIRM_PASSWORD":
            ?>
            <div class="content">
                <div style="min-width: 220px; font-size: 17px;" class="col col-2"><span class="starrequired">*</span>Подтверждение пароля:</div>
                <div class="col col-6"><input class="input" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" maxlength="50"></div>
            </div>
            <?break;

        case "PERSONAL_GENDER":
            ?><select name="REGISTER[<?=$FIELD?>]">
                <option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
                <option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
                <option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
            </select><?
            break;

        case "PERSONAL_COUNTRY":
        case "WORK_COUNTRY":
            ?><select name="REGISTER[<?=$FIELD?>]"><?
            foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value)
            {
                ?><option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
            <?
            }
            ?></select><?
            break;
        case "UF_TYPE":?>


        	<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/regslide/style.css">
			<script src="<?=SITE_TEMPLATE_PATH?>/regslide/jquery.mask.js"></script>
			<script src="<?=SITE_TEMPLATE_PATH?>/regslide/main.js"></script>

            <div class="content">
                <div style="min-width: 220px; font-size: 17px;" class="col col-2"><span class="starrequired">*</span>Я:</div>
                <div class="fx-select" style="padding: 0px;">
					<span class="fx-title t1 active" style="width: 50px;">Клиент</span>
					<span class="fx-title t2" style="width:50px;float:left;margin-left:70px;">Массажист</span>
					<?if($_REQUEST["type"] == "client"):?>
						<span class="fx-slider" id="usertypeclient" style="margin:0;margin-left:75px;"><i class="left"></i></span>
						<input type="hidden" name="user_type_field" value="8">
					<?else:?>
						<span class="fx-slider" id="usertypeclient" style="margin:0;margin-left:75px;"><i class="right"></i></span>
						<input type="hidden" name="user_type_field" value="9">
					<?endif;?>
				</div>
				<?/*<div class="col col-3" style="float:left;">
					<input class="checkbox" type="checkbox" id="checkusertype1" value="8" <?if($_REQUEST["type"] == "client"):?>checked<?endif;?>/>&nbsp;
					<label for="checkusertype1"><span></span>Клиент</label>
				</div>
				<div class="col col-3" style="float:left;">
					<input class="checkbox" type="checkbox" id="checkusertype2" value="7" <?if($_REQUEST["type"] == "massagist"):?>checked<?endif;?>/>&nbsp;
					<label for="checkusertype2"><span></span>Массажист</label>
				</div>
				<div class="switch switch-info round switch-inline" style="display: none;">
  					<input id="exampleCheckboxSwitch6" type="checkbox" checked="">
  					<label for="exampleCheckboxSwitch6"></label>
				</div>*/?>

				<input type="hidden" id="user_type_field" name="REGISTER[UF_TYPE]" value="8" />&nbsp;

            </div>

			<div class="content">
				<div class="col col-8" style="margin-bottom: 15px;">
				<input class="checkbox" type="checkbox" id="confirm_politic" name="confirm_politic" value="N" />&nbsp;
				<label for="confirm_politic" id="confirm_politic_text"><span></span>Нажимая кнопку «Зарегистрироваться», я принимаю условия Пользовательского соглашения (ссылка) и даю своё согласие на обработку моих персональных данных</label></div>
			</div>

        <?break;
        case "PERSONAL_PHOTO":
        case "WORK_LOGO":
            ?><input size="30" type="file" name="REGISTER_FILES_<?=$FIELD?>" /><?
            break;

        case "PERSONAL_NOTES":
        case "WORK_NOTES":
            ?><textarea cols="30" rows="5" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea><?
            break;
        default:?>

            <div class="content">
                <div style="min-width: 220px; font-size: 17px;" class="col col-2"><?=GetMessage("REGISTER_FIELD_".$FIELD)?>:</div>
				<div class="col col-6"><input 
					<?if($FIELD == "PERSONAL_PHONE"):?>
						id="personal_phone_mask"
					<?endif;?>
					class="input" type="text" name="REGISTER[<?=$FIELD?>]" maxlength="50" value="<?=$arResult["VALUES"][$FIELD]?>"></div>
            </div>

    <?}?></td>
        </tr>
    <?endif?>
<?endforeach?>
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
    <tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
    <?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
    <tr><td><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></td><td>
            <?$APPLICATION->IncludeComponent(
                "bitrix:system.field.edit",
                $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
    <?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************?>
<?
/* CAPTCHA */
if ($arResult["USE_CAPTCHA"] == "Y")
{
    ?>
        <tr>
            <td colspan="2"><b><?=GetMessage("REGISTER_CAPTCHA_TITLE")?></b></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
            </td>
        </tr>
        <tr>
            <td><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></td>
            <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
        </tr>
    <?
}
/* !CAPTCHA */
?>
    </tbody>
    <tfoot>
        <tr>
            <td>
                <button data-action="clear" id="register_submit_button" name="register_submit_button" class="bxmap-clear-button btn btn-blue" value="<?=GetMessage("AUTH_REGISTER")?>">Зарегистрироваться</button>
            </td>
        </tr>
    </tfoot>
</table>

</form>
<?endif?>
</div>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/pwdwidget.js"></script>
<script>
	$(function(){

		var pwdwidget = new PasswordWidget('thepwddiv','regpwd');
		pwdwidget.MakePWDWidget();

		$("#regpwd").keyup(function(){
			$("#regpwd_id").val($("#regpwd").val());
			$("#regpwd_id").trigger("keyup");
		});
		$(".pwdopsdiv").css("display", "none");
		$("#regpwd_id").css("display", "none");
		$("#regpwd_strength_div").css("margin-bottom", "5px");
		$("#thepwddiv").css("margin-left", "245px");
	});

	$("#personal_phone_mask").mask("+7(999) 999-99-99");
	$("#register_submit_button").on("click", function(){
		if(!document.getElementById("confirm_politic").checked) {
			$("#confirm_politic_text").css("color", "red");
			return false;
		}
		else { $("#confirm_politic_text").css("color", "black"); }
	});

	$("#usertypeclient").on("click", function(){
		if($("#usertypeclient").find(".left").length)
			$("#user_type_field").val(7);
		else
			$("#user_type_field").val(8);
	
	});
</script>