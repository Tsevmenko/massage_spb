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
    <?= $arParams['~RSS_ICON']; ?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
        <div class="news-item-image">
        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
                    src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                    /></a>
        <?else:?>
            <img
            src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
            alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
            title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
            />
        <?endif;?>
        </div>
    <?endif?>
        <div class="news-item-content">
            <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                    <h2 class="news-item-header"><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h2>
                <?else:?>
                    <h2 class="news-item-header"><?echo $arItem["NAME"]?></h2>
                <?endif;?>
            <?endif;?>
            <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                <div class="news-item-date"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></div>
            <?endif?>
            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                <div class="news-item-text"><?echo $arItem["PREVIEW_TEXT"];?></div>
            <?endif;?>
            <? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>

                <?= $arProperty["NAME"] ?>:&nbsp;
                <? if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                    <?= implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); ?>
                <? else: ?>
                    <?= $arProperty["DISPLAY_VALUE"]; ?>
                <? endif ?>
                <br/>
            <? endforeach; ?>
        </div>
	</div>
<?endforeach;?>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
