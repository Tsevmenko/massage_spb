<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)) { ?>
<div class="col col-12">
    <div class="doctor-menu col-margin-top">
        <ul>
        <?
        foreach($arResult as $i=>$arItem) {
        ?>
            <li>
                <a<?=($arItem["SELECTED"] ? ' class="active"' : '')?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
            </li>
        <? } ?>
        </ul>
    </div> <!-- .doctor-menu col-margin-top -->
</div> <!-- .col col-12 -->
<? } ?>