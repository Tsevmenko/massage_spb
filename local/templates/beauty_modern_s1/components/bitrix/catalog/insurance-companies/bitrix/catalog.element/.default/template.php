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
$strMainID = $this->GetEditAreaId($arResult['ID']);
?>
<div id="<?= $strMainID ?>">
    <? if (!empty($arResult['DETAIL_PICTURE'])) { ?>
        <img class="detail_picture"
			src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
			width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
			height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
			alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
			title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
			/>
    <? } ?>
        <div>
            <?
            if ('' != $arResult['DETAIL_TEXT']) {
                if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
                    echo $arResult['DETAIL_TEXT'];
                } else {
                    ?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
                }
            }
            ?>
        </div>
</div>
