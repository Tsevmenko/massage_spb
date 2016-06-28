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
?>
<div class="white-content-box col-margin-top news-list">
<?
if (0 < $arResult["SECTIONS_COUNT"])
{
    foreach ($arResult['SECTIONS'] as &$arSection)
    {
        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
        ?>
        <div class="news-item" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
            <? if (is_array($arSection['PICTURE'])) { ?>
            <div class="news-item-image">
                <a href="<?= $arSection['SECTION_PAGE_URL']; ?>"><img src="<?= $arSection['PICTURE']['SRC'] ?>" alt=""></a>
            </div>
            <? } ?>
            <div class="news-item-content">
                <h2 class="news-item-header"><a href="<?= $arSection['SECTION_PAGE_URL']; ?>"><?= $arSection['NAME']; ?></a></h2>
                <div class="news-item-text">
                    <?
                    if ('' != $arSection['DESCRIPTION'])
                    {
                        echo $arSection['DESCRIPTION'];
                    }
                    ?>
                </div>
            </div>
        </div>
    <?
    }
}
?>
</div>