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
?>
<div class="white-content-box col-margin-top news-list">
<? if (!empty($arResult['ITEMS'])) { ?>
    <? foreach ($arResult['ITEMS'] as $key => $arItem) {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
        $strMainID = $this->GetEditAreaId($arItem['ID']);
    ?>
    <div class="news-item" id="<? echo $strMainID; ?>">
		<? if (is_array($arItem['PREVIEW_PICTURE'])) { ?>
        <div class="news-item-image">
            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"><img src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt=""></a>
        </div>
        <? } ?>
        <div class="news-item-content">
            <h2 class="news-item-header"><a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"><?= $arItem['NAME']; ?></a></h2>
            <div class="news-item-text">
                <?
                if ('' != $arItem['PREVIEW_TEXT'])
                {
                    echo $arItem['PREVIEW_TEXT'];
                }
                ?>
            </div>
        </div>
	</div>
    <? } ?>
<? } ?>
</div>
<?= $arResult["NAV_STRING"]; ?>