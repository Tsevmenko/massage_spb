<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>

<?= $arResult["FORM_NOTE"] ?>

<?if ($arResult["isFormNote"] != "Y") {
    ?>
    <?= $arResult["FORM_HEADER"] ?>

    <table>
        <?
        if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y") {
            ?>
            <tr>
                <td><?
                    /***********************************************************************************
                    form header
                     ***********************************************************************************/
                    if ($arResult["isFormTitle"]) {
                        ?>
                        <h3><?= $arResult["FORM_TITLE"] ?></h3>
                    <?
                    } //endif ;

                    if ($arResult["isFormImage"] == "Y") {
                        ?>
                        <a href="<?= $arResult["FORM_IMAGE"]["URL"] ?>" target="_blank"
                           alt="<?= GetMessage("FORM_ENLARGE") ?>"><img src="<?= $arResult["FORM_IMAGE"]["URL"] ?>"
                                                                        <? if ($arResult["FORM_IMAGE"]["WIDTH"] > 300): ?>width="300"
                                                                        <? elseif ($arResult["FORM_IMAGE"]["HEIGHT"] > 200): ?>height="200"<? else: ?><?=
                                $arResult["FORM_IMAGE"]["ATTR"] ?><?
                            endif;
                            ?> hspace="3" vscape="3" border="0"/></a>
                    <?
                    } //endif
                    ?>

                    <p><?= $arResult["FORM_DESCRIPTION"] ?></p>
                </td>
            </tr>
        <?
        } // endif
        ?>
    </table>
    <br/>
    <?
    /***********************************************************************************
    form questions
     ***********************************************************************************/
    ?>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
		?>
		<div class="col-margin-bottom">
			<? if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])): ?>
				<span class="error-fld" title="<?= $arResult["FORM_ERRORS"][$FIELD_SID] ?>"></span>
			<? endif; ?>
				<div class="mb10">						<?= $arQuestion["CAPTION"] ?>
				<? if ($arQuestion["REQUIRED"] == "Y"): ?>
					<span class="req">*</span>
					<?$req = " required "?>
				<?else:?>
					<?$req = ""?>
				<? endif; ?>
				<?= $arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : "" ?>
			</div>
			<?= str_replace(array('<input','<select'),array('<input class="input col-6"'.$req,'<select class="styler col-3"'),$arQuestion["HTML_CODE"]) ?>
		</div>
	<?
	} //endwhile
	?>
	<?
	if ($arResult["isUseCaptcha"] == "Y") {
		?>
		<div class="col-margin-bottom">
			<div class="mb10">
				<b><?= GetMessage("FORM_CAPTCHA_TABLE_TITLE") ?></b>
			</div>
			<div class="mb10">
				<?= GetMessage("FORM_CAPTCHA_FIELD_TITLE") ?> <span class="req">*</span>
			</div>
			<div class="mb10">
				<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/>
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
					 width="180" height="40"/>
			</div>
			<input class="input" type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext"/>
		</div>
	<?
	} // isUseCaptcha
	?>
	<div class="col-margin-bottom">
			<input class="btn btn-blue" <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?> type="submit"
				   name="web_form_submit"
				   value="<?= strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]; ?>"/>
			&nbsp;<input class="btn btn-gray" type="reset" value="<?= GetMessage("FORM_RESET"); ?>"/>
	</div>
    <p>
        <?= $arResult["REQUIRED_SIGN"]; ?> - <?= GetMessage("FORM_REQUIRED_FIELDS") ?>
    </p>
    <?= $arResult["FORM_FOOTER"] ?>
<?
}
?>