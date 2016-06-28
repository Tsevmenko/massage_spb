<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();?><?
?>
<?= ShowError($arResult["strProfileError"]); ?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>
<script type="text/javascript">
    <!--
    var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
    //-->
    var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>
<form id="ms-user-profile" method="post" name="form1" action="<?= $arResult["FORM_TARGET"] ?>?" enctype="multipart/form-data">
    <?= $arResult["BX_SESSION_CHECK"] ?>
    <input type="hidden" name="lang" value="<?= LANG ?>"/>
    <input type="hidden" name="ID" value=<?= $arResult["ID"] ?>/>
    <input type="hidden" name="LOGIN" value=<?= $arResult["arUser"]["LOGIN"] ?>/>

    <div class="profile-block-<?= strpos($arResult["opened"], "reg") === false ? "hidden" : "shown" ?>"
         id="user_div_reg">
        <table class="form-table profile-table data-table">
            <thead>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
					<p class="form-label">
						<?= GetMessage('NAME') ?>
					</p>
                    <input class="input" type="text" name="NAME" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>"/></td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
						<?= GetMessage('LAST_NAME') ?>
					</p>
                <input class="input" type="text" name="LAST_NAME" maxlength="50" value="<?= $arResult["arUser"]["LAST_NAME"] ?>"/>
                </td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage('SECOND_NAME') ?></font>
					</p>
                <input class="input" type="text" name="SECOND_NAME" maxlength="50"
                           value="<?= $arResult["arUser"]["SECOND_NAME"] ?>"/></td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage('EMAIL') ?><span class="req">*</span>
					</p>
                <input class="input" type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"] ?>"/>
                </td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage("USER_ADRESS") ?>
					</p>
                <textarea class="input" cols="30" rows="5"
                              name="PERSONAL_STREET"><?= $arResult["arUser"]["PERSONAL_STREET"] ?></textarea></td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage('USER_PHONE') ?>
					</p>
                <input class="input" type="text" name="PERSONAL_PHONE" maxlength="255"
                           value="<?= $arResult["arUser"]["PERSONAL_PHONE"] ?>"/></td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage('USER_MOBILE_PHONE') ?>
					</p>
                <input class="input" type="text" name="PERSONAL_MOBILE" maxlength="255"
                           value="<?= $arResult["arUser"]["PERSONAL_MOBILE"] ?>"/></td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage('NEW_PASSWORD_REQ') ?>
					</p>
                <input class="input" type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off"/></td>
            </tr>
            <tr>
                <td>
					<p class="form-label">
					<?= GetMessage('NEW_PASSWORD_CONFIRM') ?>
					</p>
                <input class="input" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off"/></td>
            </tr>
            </tbody>
        </table>
    </div>

    <? // ********************* User properties ***************************************************?>
	<? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
    <div class="profile-link profile-user-div-link"><a title="<?= GetMessage("USER_SHOW_HIDE") ?>"
                                                       href="javascript:void(0)"
                                                       OnClick="javascript: SectionClick('user_properties')"><?= strLen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB") ?></a>
    </div>
    <div id="user_div_user_properties"
         class="profile-block-<?= strpos($arResult["opened"], "user_properties") === false ? "hidden" : "shown" ?>">
        <table class="data-table profile-table">
            <thead>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            </thead>
            <tbody>
            <? $first = true; ?>
		<? foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): 
            if ($arUserField["USER_TYPE"]["USER_TYPE_ID"] == 'ms_user_log') continue;
        ?>
            <tr>
                <td class="field-name">
                    <? if ($arUserField["MANDATORY"]=="Y"): ?>
                        <span class="starrequired">*</span>
                    <? endif; ?>
			<?= $arUserField["EDIT_FORM_LABEL"] ?>:
                </td>
                <td class="field-value">
                    <?$APPLICATION->IncludeComponent(
                    "bitrix:system.field.edit",
                    $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                    array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField), null, array("HIDE_ICONS" => "Y"));
                    ?></td>
            </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>
    <? endif; ?>
	<? // ******************** /User properties ***************************************************?>
    <p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>

    <p><input class="btn" type="submit" name="save"
              value="<?= (($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>">
</form>