<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
$oneNews = false;
?>
<div class="white-box col-margin-top short-news-block clearfix">
    <?
    foreach ($arResult["ITEMS"] as $arItem) {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
        <?if (is_array($arItem['PREVIEW_PICTURE']) || is_array($arItem['DETAIL_PICTURE'])) {
            $oneNews = true;
        ?>
            <? if (is_array($arItem['PREVIEW_PICTURE'])) {?>
                <div class="short-news-image">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><img src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $arItem["NAME"] ?>"></a>
                </div>
            <? } elseif(is_array($arItem['DETAIL_PICTURE'])) { ?>
                <div class="short-news-image">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><img src="<?= $arItem['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $arItem["NAME"] ?>"></a>
                </div>
            <? } ?>
        <? } ?>
        <div class="short-news-item short-news-item-big">
            <div class="short-news-date"><?= $arItem["DISPLAY_ACTIVE_FROM"]?></div>
            <div class="short-news-header">
                <a class="border-link-dark" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                    <?= $arItem["NAME"]; ?>
                </a>
            </div>
            <? if($arParams['DISPLAY_PREVIEW_TEXT'] == 'Y' && !empty($arItem["PREVIEW_TEXT"])) {?>
                <div class="short-news-text">
                <?= $arItem["PREVIEW_TEXT"]; ?>
                </div>
            <? } ?>
        </div> 
        <?if ($oneNews) break;?>
    <? } ?>
</div>