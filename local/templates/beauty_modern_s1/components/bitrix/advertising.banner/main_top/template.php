<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (empty($arResult['BANNER_PROPERTIES']['NAME'])) return;
$frame = $this->createFrame()->begin("");
?>
<div class="col col-6">
    <div class="h-block"<? if ($arResult['BANNER_PROPERTIES']['IMAGE_ID']): ?> style="background-image: url('<?= CFile::GetPath($arResult['BANNER_PROPERTIES']['IMAGE_ID']); ?>');"<? endif; ?><? if (!empty($arResult['BANNER_PROPERTIES']['URL'])): ?> data-target-self="<?=$arResult['BANNER_PROPERTIES']['URL']?>"<? endif; ?>>
        <div class="h-block-inner<?=($arResult['BANNER_PROPERTIES']['GROUP_SID']) ? ' '.$arResult['BANNER_PROPERTIES']['GROUP_SID'] : ' violet'?> equal">
            <div class="h3 h-block-header"><?= $arResult['BANNER_PROPERTIES']['NAME']; ?></div>
            <?= $arResult['BANNER_PROPERTIES']['CODE']; ?>
        </div> <!-- .h-block-inner violet equal -->
    </div> <!-- .h-block -->
</div>
<?
$frame->end();