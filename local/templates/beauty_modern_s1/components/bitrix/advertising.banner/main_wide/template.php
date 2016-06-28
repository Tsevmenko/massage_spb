<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (empty($arResult['BANNER_PROPERTIES']['NAME'])) return;
$frame = $this->createFrame()->begin("");
?>
<div class="h-block"<? if ($arResult['BANNER_PROPERTIES']['IMAGE_ID']): ?> style="background-image: url('<?= CFile::GetPath($arResult['BANNER_PROPERTIES']['IMAGE_ID']); ?>');"<? endif; ?>>
    <div class="h-block-inner green">
        <div class="content">
            <div class="col col-10 equal">
                <div class="h1 h-block-header"><?= $arResult['BANNER_PROPERTIES']['NAME']; ?></div>
                <div class="fz18">
                     <?= $arResult['BANNER_PROPERTIES']['CODE']; ?>
                </div>
            </div>
            <? if (!empty($arResult['BANNER_PROPERTIES']['URL'])): ?>
            <div class="col col-2 equal va-middle">
                <a href="<?= $arResult['BANNER_PROPERTIES']['URL']; ?>" class="btn btn-big"><b><?= GetMessage('BTN_MORE'); ?></b></a>
            </div>
            <? endif; ?>
        </div>
    </div>
</div>
<?
$frame->end();