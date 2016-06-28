<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<form name="<? echo $arResult["FILTER_NAME"]."_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get">
    <?foreach ($arResult["ITEMS"] as $arItem):
        if (array_key_exists("HIDDEN", $arItem)):
            echo $arItem["INPUT"];
        endif;
    endforeach;?>
	<?foreach ($arResult["ITEMS"] as $arItem):
		?>
		<? if (!array_key_exists("HIDDEN", $arItem)): ?>
		<div class="col-margin-bottom">
			<div class="mb10"><?= $arItem["NAME"] ?>:</div>
				<?if ($arItem['INPUT_NAME'] == 'arrFilter_pf[PATIENT_ID]'):
					$resUsers = CUser::GetList(($by="LAST_NAME"), ($order="ASC"),array('GROUPS_ID'=>array(intval($arParams['PACIENT_GROUP']))),array('FIELDS'=>array('LAST_NAME','NAME','SECOND_NAME','ID')));?>
					<select class="styler col-6" name="<?= $arItem['INPUT_NAME'] ?>">
						<option value=""><?= GetMessage("IBLOCK_FORM_NOT_SELECT") ?></option>
						<?
						$userFilter = array("LOGIC" => "OR",);
						while ($arUsers = $resUsers->GetNext()) {
							?>
							<option value="<?= $arUsers['ID'] ?>"
									<? if ($arItem["INPUT_VALUE"] == $arUsers['ID']): ?>selected="selected"<? endif ?>><?= $arUsers['LAST_NAME']." ".$arUsers['NAME']." ".$arUsers['SECOND_NAME'] ?></option>
						<? } ?>
					</select>
				<? else: ?>
					<?= str_replace('<input','<input class="input col-6"',$arItem["INPUT"]) ?>
				<?endif ?>
		</div>
		<? endif ?>
	<? endforeach; ?>
	<input class="btn" type="submit" name="set_filter" value="<?= GetMessage("IBLOCK_SET_FILTER") ?>"/><input
                    type="hidden" name="set_filter" value="Y"/>&nbsp;&nbsp;<input class="btn btn-blue btn-small" type="submit" name="del_filter"
                                                                                  value="<?= GetMessage("IBLOCK_DEL_FILTER") ?>"/>
</form>
