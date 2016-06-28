<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
foreach ($arResult["ITEMS"] as &$arItem) {
    $arItem['PICTURE'] = !empty($arItem['PREVIEW_PICTURE']['ID']) 
        ? CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>250, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true)
        : '';
}