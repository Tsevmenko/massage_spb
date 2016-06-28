<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<div class="news-list">
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?><br/>
    <? endif; ?>
    <?
    $month = array
    (
        1  => GetMessage('MONTH_1'),
        2  => GetMessage('MONTH_2'),
        3  => GetMessage('MONTH_3'),
        4  => GetMessage('MONTH_4'),
        5  => GetMessage('MONTH_5'),
        6  => GetMessage('MONTH_6'),
        7  => GetMessage('MONTH_7'),
        8  => GetMessage('MONTH_8'),
        9  => GetMessage('MONTH_9'),
        10 => GetMessage('MONTH_10'),
        11 => GetMessage('MONTH_11'),
        12 => GetMessage('MONTH_12')
    );
    $week = array
    (
        0 => GetMessage('DAY_0'),
        1 => GetMessage('DAY_1'),
        2 => GetMessage('DAY_2'),
        3 => GetMessage('DAY_3'),
        4 => GetMessage('DAY_4'),
        5 => GetMessage('DAY_5'),
        6 => GetMessage('DAY_6')
    );?>
   
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <p class="news-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>

				<a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><b><?=$arItem["FIELDS"]["DATE_ACTIVE_FROM"]?>, <?=$arItem['NAME']?></b></a><br/>


                    <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                              <b><?=GetMessage('QUESTION')?></b> <? echo $arItem["PREVIEW_TEXT"]; ?>
				<? endif; ?><br/>

			

<?if ($arItem["DETAIL_TEXT"]):?>
				<b><?=GetMessage('DATE_ANSWER')?></b> <?=$arItem["FIELDS"]["TIMESTAMP_X"]?><br/>
				<b><?=GetMessage('ANSWER')?></b> <?=$arItem["DETAIL_TEXT"]?>
	<?else:?>
				<b><?=GetMessage('DATE_END')?></b> <?=$arItem["FIELDS"]["DATE_ACTIVE_TO"]?><br/>
<?endif;?>


            </p>
        <? endforeach; ?>
 
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <br/><?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
</div>
