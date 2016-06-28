<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>
<div class="white-box col-margin-top short-news-block clearfix">
    <?
    foreach ($arResult["ITEMS"] as $arItem) {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
        <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="short-news-item">
            <div class="short-news-date"><?= $arItem["DISPLAY_ACTIVE_FROM"]?></div>
            <div class="short-news-header">
                <a class="border-link-dark" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                    <? if($arParams['DISPLAY_PREVIEW_TEXT'] == 'Y' && !empty($arItem["PREVIEW_TEXT"])) {
                        echo ($arParams['DISPLAY_NAME'] == 'Y' ? $arItem["NAME"].'<br>' : '');
                        echo $arItem["PREVIEW_TEXT"];
                    } else {
                    echo $arItem["NAME"];
                    }
                    ?>
                </a>
            </div>
        </div> 
    <? } ?>
</div>
