<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
$this->setFrameMode(true);
?>
<div class="services-list-content services-list-gray d-b">
    <ul class="unstyled">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <li id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                    <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><? echo $arItem["NAME"] ?></a>
                <? else: ?>
                    <? echo $arItem["NAME"] ?>
                <?endif; ?>
            </li>
        <? endforeach; ?>
    </ul>
</div>
<p>
    <a href="<?= SITE_DIR . 'services/' ?>" class="btn btn-link"><?= GetMessage('LINK_ALL_SERVICES'); ?></a>
</p>