<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<? if (count($arResult["ITEMS"]) > 0): ?>
    <h3>Очередь на госпитализацию</h3>
    <div class="news-line">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <div class="item">
                <h4><?= $arItem['SECTION_NAME'] ?></h4>
                Ваша позиция в очереди: <?= $arItem['ORDER'] ?><br>
                <? if ($arItem["DISPLAY_ACTIVE_FROM"]): ?>
                    <span
                        class="news-date-time">Предполагаемая дата госпитализации: <? echo $arItem["DISPLAY_ACTIVE_FROM"] ?>
                        &nbsp;&nbsp;</span>
                <? endif ?>
                <br/>
            </div>
        <? endforeach; ?>
    </div>
<? else: ?>
    Вы не состоите в очереди на госпитализацию.
<?endif ?>
