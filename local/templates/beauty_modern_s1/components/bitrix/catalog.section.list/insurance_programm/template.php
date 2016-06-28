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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

if (0 < $arResult["SECTIONS_COUNT"]) {
?>
   <div class="main-expand-wrapper main-expand-wrapper-insurance col-margin-top clearfix">
        <ul class="main-insurance-list">
<?
$i = 1;
    foreach ($arResult['SECTIONS'] as &$arSection)
    {
        if (!empty($arParams['MAX_COUNT_SECTION']) && $i > $arParams['MAX_COUNT_SECTION']) break;
        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
        ?>
        <li id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
            <a href="<?= $arSection['SECTION_PAGE_URL']; ?>">
                <? if (is_array($arSection['PICTURE'])) { ?>
                <img src="<?= $arSection['PICTURE']['SRC'] ?>" alt="<?= $arSection["NAME"] ?>">
                <? } else { ?>
                <?= $arSection["NAME"] ?>
                <? } ?>
            </a>
        </li>
    <?
        $i++;
    }
    ?>
        </ul>
    </div>
<? } ?>