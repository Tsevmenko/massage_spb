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
if (count($arResult["ITEMS"]) == 0 ) {
	return;
}
?>
<div class="big-slider">
    <?
    foreach ($arResult["ITEMS"] as $arItem) {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        
		if (is_array($arItem["DISPLAY_PROPERTIES"]['REAL_PICTURE']['FILE_VALUE'])) {
            $arImg = array(
                'src' => $arItem["DISPLAY_PROPERTIES"]['REAL_PICTURE']['FILE_VALUE']["SRC"],
                'width' => $arItem["DISPLAY_PROPERTIES"]['REAL_PICTURE']['FILE_VALUE']["WIDTH"],
                'height' => $arItem["DISPLAY_PROPERTIES"]['REAL_PICTURE']['FILE_VALUE']["HEIGHT"],
            );
        } elseif(is_array($arItem["PREVIEW_PICTURE"])) {
            $arImg = array(
                'src' => $arItem["PREVIEW_PICTURE"]["SRC"],
                'width' => $arItem["PREVIEW_PICTURE"]["WIDTH"],
                'height' => $arItem["PREVIEW_PICTURE"]["HEIGHT"],
            );
        } else {
            continue;
        }
    ?>
        <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="slider-item" <?=(!empty($arImg) ? 'style="background-image: url('.$arImg['src'].');"' : '')?>>
            <div class="slider-item-text">
                <? if (($arItem["PREVIEW_TEXT"] && $arParams["DISPLAY_PREVIEW_TEXT"] != "N") || ($arItem["DETAIL_TEXT"])) { ?>
                    <? if (!empty($arItem["PROPERTIES"]["LINK"]["VALUE"])) { ?>
						<a tabindex="-1" href="<?= $arItem["PROPERTIES"]["LINK"]["VALUE"] ?>">
					<? } ?>
					<? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && !empty($arItem["PREVIEW_TEXT"])) {
                        echo $arItem["PREVIEW_TEXT"];
                    } elseif (!empty($arItem["DETAIL_TEXT"])) {
                        echo $arItem["DETAIL_TEXT"];
                    } else {
                        echo $arItem["NAME"];
                    } ?>
					<? if (!empty($arItem["PROPERTIES"]["LINK"]["VALUE"])) { ?>
						</a>
					<? } ?>
                <? } ?>
                <div class="big-slider-nav"></div>
            </div> <!-- .slider-item-text -->
        </div> <!-- .slider-item -->
    <? } ?>
</div> <!-- .big-slider -->