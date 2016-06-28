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
<? foreach ($arResult['SERVICES'] as $idService => $arService): ?>
<div class="step-service-item">
	<div class="h3 col-margin-top mb20"><?= $arService['NAME'] ?></div>
    <? if (!empty($arService['ITEMS']) && is_array($arService['ITEMS'])): ?>
	<div class="white-content-box">
		<ul class="in-page-nav in-page-nav-noborder">
        <? foreach ($arService['ITEMS'] as $idItem): ?>
			<li><a href="<?= $arResult['ITEMS'][$idItem]['DETAIL_PAGE_URL'] ?>"><?= $arResult['ITEMS'][$idItem]['NAME'] ?><? if ($arResult['ITEMS'][$idItem]['MIN_PRICE'] > 0): ?><span class="fl-r"><?= $arResult['ITEMS'][$idItem]['MIN_PRICE'] ?> руб.</span><? endif; ?></a></li>
        <? endforeach; ?>
		</ul>
	</div> <!-- .white-content-box -->
    <? endif; ?>
</div> <!-- .step-service-item -->
<? endforeach; ?>


<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>