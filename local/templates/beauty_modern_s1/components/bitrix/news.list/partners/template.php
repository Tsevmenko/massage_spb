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
<div class="footer-carousel-wrapper">
    <div class="footer-carousel">
        <? foreach ($arResult["ITEMS"] as $arItem) { ?>
            <? if (!empty($arItem['PROPERTIES']['LINK']['VALUE'])) { ?>
                <div class="carousel-item">
                    <a class="equal" href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>">
                    <? if (is_array($arItem['PICTURE'])) { ?><span class="carousel-item-image"><img src="<?=$arItem['PICTURE']['src']?>" alt=""></span><? } ?>
                    <?= $arItem['NAME'] ?></a>
                </div>
            <? } ?>
        <? } ?>
    </div>
</div>