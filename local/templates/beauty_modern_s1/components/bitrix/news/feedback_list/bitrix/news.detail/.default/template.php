<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
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
<div class="news-detail">

<b><?=$arResult["FIELDS"]["DATE_ACTIVE_FROM"]?>,
	<? if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]): ?>
	<?= $arResult["NAME"] ?>
	<? endif; ?></b><br/><br/>



 <b><?=GetMessage('QUESTION')?></b> <?=$arResult["PREVIEW_TEXT"]; ?><br/>


<?if ($arResult["DETAIL_TEXT"]):?>
				<b><?=GetMessage('DATE_ANSWER')?></b> <?=$arResult["FIELDS"]["TIMESTAMP_X"]?><br/>
	<?else:?>
				<b><?=GetMessage('DATE_END')?></b> <?=$arResult["FIELDS"]["DATE_ACTIVE_TO"]?><br/>
<?endif;?>

   <br/>
</div>
