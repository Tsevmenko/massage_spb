<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$this->setFrameMode(true);
?>
<div class="col col-5">
    <div class="doctors-search">
            <input class="input input-search input-round-search" type="text" name="<?= $arParams['FILTER_NAME'] ?>_LAST_NAME" value=
            "<?= substr($arResult['FILTER_VALUES'][$arParams['FILTER_NAME'].'_LAST_NAME'], 0, strlen($arResult['FILTER_VALUES'][$arParams['FILTER_NAME'].'_LAST_NAME']) - 1) ?>" placeholder="<?=GetMessage('TPL_PLACEHOLDER_FIO')?>">
            <button class="btn btn-round-search"><?=GetMessage('TPL_BTN_SEARCH')?></button>
    </div>
</div>
