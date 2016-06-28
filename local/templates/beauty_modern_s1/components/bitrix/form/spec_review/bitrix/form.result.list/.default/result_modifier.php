<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult['questionCode'] = array('fio'=>0, 'review_text'=>0);
if (is_array($arResult["arrColumns"])) {
    foreach ($arResult["arrColumns"] as $arColumn) {
        if (isset($arResult['questionCode'][$arColumn['SID']])) {
            $arResult['questionCode'][$arColumn['SID']] = $arColumn['ID'];
        }
    }
}
?>

