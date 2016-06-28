<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if (!empty($arResult)) { ?>
    <div class="col col-3">
    <?
    $countItem = count($arResult['SIDE']);
    $columnCount = ceil($countItem/2);
    for ($i=0; $i<$columnCount; $i++) {
        if ($arResult['SIDE'][$i]['COUNT'] > 1) {
        ?>
            <h4 class="footer-header"><?=$arResult['SIDE'][$i]['TEXT']?></h4>
            <ul class="footer-nav">
        <? foreach ($arResult['SIDE'][$i]['CHILDREN'] as $arItem) { ?>
           <li><a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a></li>
        <? } ?>
            </ul>
        <? } else { ?>
            <h4 class="footer-header"><a href="<?=$arItem['LINK']?>"><?=$arResult['SIDE'][$i]['TEXT']?></a></h4>
        <? }
    } ?>
    </div>
    <div class="col col-2">
        <? foreach ($arResult['CENTER'] as $arItem) { ?>
        <div class="mt30 ta-center">
            <a class="big-footer-icon" href="<?=$arItem['LINK']?>">
                <? if (!empty($arItem['PARAMS']['ICON']) && is_file($_SERVER['DOCUMENT_ROOT'] . $arItem['PARAMS']['ICON'])) { ?>
                <img src="<?= $arItem['PARAMS']['ICON'] ?>" alt="<?=$arItem['TEXT']?>">
                <? } ?>
                <?=$arItem['TEXT']?>
            </a>
        </div>
        <? } ?>
    </div>
    <div class="col col-3">
    <?
    for ($i=$columnCount; $i<$countItem; $i++) {
        if ($arResult['SIDE'][$i]['COUNT'] > 1) {
        ?>
            <h4 class="footer-header"><?=$arResult['SIDE'][$i]['TEXT']?></h4>
            <ul class="footer-nav">
        <? foreach ($arResult['SIDE'][$i]['CHILDREN'] as $arItem) { ?>
           <li><a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a></li>
        <? } ?>
            </ul>
        <? } else { ?>
            <h4 class="footer-header"><a href="<?=$arItem['LINK']?>"><?=$arResult['SIDE'][$i]['TEXT']?></a></h4>
        <? }
    } ?>
    </div>
<? } ?>