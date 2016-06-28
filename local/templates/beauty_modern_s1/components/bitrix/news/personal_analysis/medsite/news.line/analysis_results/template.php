<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<div class="news-line">
    <?if (!$arResult['ITEMS']):
        echo GetMessage('no_analysis');
    else:?>
        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>
        <table class="form-filter-table data-table">
            <thead>
            <tr>
                <td><?= GetMessage("FORM_F_NUMBER") ?></td>
                <td><?= GetMessage("FORM_F_NAME") ?></td>
                <td><?= GetMessage("FORM_F_DATE_CREATE") ?></td>
                <td><?= GetMessage("FORM_F_TIMESTAMP") ?></td>
            </tr>
            </thead>
            <tbody>
            <?foreach ($arResult["ITEMS"] as $arItem):
                $tmp = explode(" ", $arItem['DATE_CREATE']);
                $arItem['DATE_CREATE'] = $tmp[0];
                $tmp = explode(" ", $arItem['TIMESTAMP_X']);
                $arItem['TIMESTAMP_X'] = $tmp[0];?>
                <tr>
                    <td>
                        <?= $arItem['ID'] ?>
                    </td>
                    <td>
                        <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><? echo $arItem["NAME"] ?></a>
                    </td>
                    <td>
                        <?= $arItem['DATE_CREATE'] ?>
                    </td>
                    <td>
                        <?= $arItem['TIMESTAMP_X'] ?>
                    </td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    <?endif ?>
</div>
