<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<? /*?><br/>
<a href="analysis_add.php" title="<?=GetMessage("ITEM_ADD")?>"><?=GetMessage("ITEM_ADD")?></a>
<br/>
<br/><?*/
?>
<div class="news-list">
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?><br/>
    <? endif; ?>
    <?foreach ($arResult["ITEMS"] as $arItem):
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
    <p class="news-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
        <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><img class="preview_picture" border="0"
                                                             src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                                             width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                                             height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                                             alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>"
                                                             style="float:left"/></a>
        <? else: ?>
            <img class="preview_picture" border="0" src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                 width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>" height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                 alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" style="float:left"/>
        <?endif; ?>
    <? endif ?>
        <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
        <span class="news-date-time"><? echo $arItem["DISPLAY_ACTIVE_FROM"] ?></span>
    <? endif ?>
        <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
        <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
            <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><b><? echo $arItem["NAME"] ?></b></a><br/>
        <? else: ?>
            <b><? echo $arItem["NAME"] ?></b><br/>
        <?endif; ?>
    <? endif; ?>
        <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
        <? echo $arItem["PREVIEW_TEXT"]; ?>
    <? endif; ?>
        <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
        <div style="clear:both"></div>
    <? endif ?>
        <? foreach ($arItem["FIELDS"] as $code => $value): ?>
        <small>
            <?= GetMessage("IBLOCK_FIELD_".$code) ?>:&nbsp;<?= $value; ?>
        </small><br/>
    <? endforeach; ?>
        <? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
        <small>
            <?= $arProperty["NAME"] ?>:&nbsp;
            <? if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                <?= implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); ?>
            <? else: ?>
                <?= $arProperty["DISPLAY_VALUE"]; ?>
            <?endif ?>
        </small><br/>
    <? endforeach; ?>
        <? /*?><a href="analysis_add.php?ID=<?=$arItem["ID"]?>" title="<?=GetMessage("ITEM_EDIT")?>"><?=GetMessage("ITEM_EDIT")?></a>
		<br/>
		<a href="javascript:if(confirm('<?=GetMessage("FORM_CONFIRM_DELETE")?>')) window.location='analysis_results.php?del_id=<?=$arItem["ID"]?>'" title="<?=GetMessage("DELETE_ORDER_TITLE")?>"><?=GetMessage("DELETE_ORDER")?></a>
		<?*/
        ?>
        </p>
    <? endforeach; ?>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <br/><?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
</div>
<? /*?><br/>
<a href="analysis_add.php" title="<?=GetMessage("ITEM_ADD")?>"><?=GetMessage("ITEM_ADD")?></a><?*/
?>
