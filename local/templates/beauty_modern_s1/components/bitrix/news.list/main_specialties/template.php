<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if (count($arResult["ITEMS"]) == 0 ) return;
?>
<div class="main-expand-wrapper">
    <ul class="main-specialty-list clearfix">
    <?
    foreach ($arResult["ITEMS"] as $arItem) {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
        <li id="<?=$this->GetEditAreaId($arItem['ID']);?>"><a href="<?= SITE_DIR . 'employees/?users_UF_SPECIALITY='. $arItem["ID"] ?>"><?= $arItem["NAME"] ?></a></li>
    <? } ?>
    </ul>
</div>
<div class="main-expand-footer-block">
    <span class="btn main-expand-btn"><?=  GetMessage('T_EXPAND_LIST')?></span>
</div>