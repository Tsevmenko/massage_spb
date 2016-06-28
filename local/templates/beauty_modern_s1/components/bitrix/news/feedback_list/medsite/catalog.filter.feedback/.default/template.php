<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?$arTitles = array(
	'DETAIL_TEXT' => GetMessage('AVAIL_ANSWER'),
	'DATE_ACTIVE_FROM' => GetMessage('RQ_DATE_ACTIVE_FROM'),
)?>
<form name="<? echo $arResult["FILTER_NAME"]."_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get">
    <?foreach ($arResult["ITEMS"] as $arItem):
        if (array_key_exists("HIDDEN", $arItem)):
            echo $arItem["INPUT"];
        endif;
    endforeach;?>
	<? foreach ($arResult["ITEMS"] as $key=>$arItem): ?>
		<? if (!array_key_exists("HIDDEN", $arItem)): ?>
		<div class="col-margin-bottom">
			<div class="mb10">
				<?if (array_key_exists($key,$arTitles)):?> <?=$arTitles[$key]?>:
				<?else:?><?= $arItem["NAME"] ?>:
				<?endif;?>
			</div>
			<?= str_replace(array('<input','<select'),array('<input class="input col-2"','<select class="styler col-4"'), $arItem["INPUT"]) ?>
		  </div>
		<? endif ?>
	<? endforeach; ?>
	<div class="col-margin-bottom">
		<input class="btn" type="submit" name="set_filter" value="<?= GetMessage("IBLOCK_SET_FILTER") ?>"/><input
			type="hidden" name="set_filter" value="Y"/>&nbsp;&nbsp;<input class="btn btn-blue btn-small" type="submit" name="del_filter"
																		 value="<?= GetMessage("IBLOCK_DEL_FILTER") ?>"/>
    </div>
</form>
