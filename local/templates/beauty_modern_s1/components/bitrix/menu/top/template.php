<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if (!empty($arResult)) { ?>
    <nav>
        <ul class="root">
    <?
    $previousLevel = 0;
    $countItem = count($arResult);
    foreach($arResult as $i=>$arItem) {
        $class = array();
        if($arItem["SELECTED"]) {
            $class[] = 'active';
        }
    ?>
		<?if ($arItem["DEPTH_LEVEL"] > 2) continue;?>
        <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel) { ?>
            </ul></li>
        <? } ?>
        <? if ($arItem["IS_PARENT"]) {
            ?>
            <? if ($arItem["DEPTH_LEVEL"] == 1) {
                $class[] = 'parent';
                ?>
                <li<?=(!empty($class) ? ' class="'.implode(' ', $class).'"' : '')?>>
                    <a href="<?=$arItem["LINK"]?>" tabindex="1"><?=$arItem["TEXT"]?></a>
                    <ul>
            <? } else { ?>
                <li><a href="<?=$arItem["LINK"]?>" tabindex="1"><?=$arItem["TEXT"]?></a></li>
            <? } ?>
        <? } else {
            if ($countItem == $i+1) {
                $class[] = 'last';
            }
            ?>
            <li<?=(!empty($class) ? ' class="'.implode(' ', $class).'"' : '')?>><a href="<?=$arItem["LINK"]?>" tabindex="1"><?=$arItem["TEXT"]?></a></li>
        <? } ?>
        <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
    <? } ?>
    <? if ($previousLevel > 1) { ?>
        </ul></li>
    <? } ?>
        </ul>
    </nav>
<? } ?>