<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc as Loc;
$fPath = str_replace('\\','/',__FILE__);
Loc::loadLanguageFile($fPath);

$append = strpos($arParams['RECORD_WIZARD_LINK'], SITE_DIR) === 0 ? '/' : SITE_DIR;
if (strpos($arParams['RECORD_WIZARD_LINK'], '/') === 0) {
    $arParams['RECORD_WIZARD_LINK'] = substr($append, 0, -1) . $arParams['RECORD_WIZARD_LINK'];
} else {
    $arParams['RECORD_WIZARD_LINK'] = $append . $arParams['RECORD_WIZARD_LINK'];
}

$this->initComponentTemplate();
if (!empty($arResult['NAME'])) { ?>
    <?$this->__template->SetViewTarget('bx_head_content');?>
    <div class="col col-9 mb20">
        <h1><?= $arResult['NAME'] ?></h1>
    </div>
    
    <div class="col col-3 ta-right mb20">
        <div style="font-weight: bold;">
        <?if (array_key_exists('MIN_PRICE',$arResult) && !empty($arResult['MIN_PRICE']['VALUE'])):?>
            <?if ($arResult['MIN_PRICE']['CURRENCY']!="RUB"):?>
                <?=$arResult['MIN_PRICE']['PRINT_VALUE']?>
            <?else:?>
                <span class="price"><? echo $arResult['MIN_PRICE']['VALUE']; ?> <span class="rub">р</span></span>
            <?endif?>
        <?endif?>
        </div>
        <? if ($arResult['CAN_RECORD']):?>
        <a href="<?=$arParams['RECORD_WIZARD_LINK'].'?SERVICE='.$arResult['ID']?>" class="btn btn-big"><?= Loc::getMessage('M_BTN_RECORD_WIZARD'); ?></a>
        <? endif; ?>
    </div>
    <?$this->__template->EndViewTarget();?>
<? } ?>
<?
global $APPLICATION;
if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
if (isset($templateData['JS_OBJ'])) {
?>
<script type="text/javascript">
BX.ready(
	BX.defer(function(){
		if (!!window.<? echo $templateData['JS_OBJ']; ?>)
		{
			window.<? echo $templateData['JS_OBJ']; ?>.allowViewedCount(true);
		}
	})
);
</script>
<? } ?>