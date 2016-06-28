<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<form name="<? echo $arResult["FILTER_NAME"]."_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get">
    <?foreach ($arResult["ITEMS"] as $arItem):
        if (array_key_exists("HIDDEN", $arItem)):
            echo $arItem["INPUT"];
        endif;
    endforeach;?>
	<div class="col-margin-bottom">
		<? foreach ($arResult["ITEMS"] as $arItem): ?>
            <? if (!array_key_exists("HIDDEN", $arItem)): ?>
				<div class="mb10">
					<?= GetMessage("IBLOCK_ADRESS") ?>:
				</div>
				<?= str_replace(array('<input','<select'),array('<input class="input col-6"'.$req,'<select class="styler col-3"'),$arItem["INPUT"]) ?>
            <? endif ?>
        <? endforeach; ?>
		<br>
		<input class="btn btn-blue btn-small" type="submit" name="set_filter" value="<?= GetMessage("IBLOCK_SET_FILTER") ?>"/><input
			type="hidden" name="set_filter" value="Y"/>
		<input class="btn btn-gray btn-small" type="submit" name="del_filter"
			   value="<?= GetMessage("IBLOCK_DEL_FILTER") ?>"/>
    </div>
</form>
