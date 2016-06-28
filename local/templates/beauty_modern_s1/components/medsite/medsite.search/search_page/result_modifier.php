<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arParams['SPEC_IBLOCK_ID']) && intval($arParams['SPEC_IBLOCK_ID']) > 0 && CModule::IncludeModule('iblock')) {
    $rsEl = CIBlockElement::GetList(
        array('SORT'=>'asc', 'NAME'=>'asc'),
        array('IBLOCK_ID'=>intval($arParams['SPEC_IBLOCK_ID']), 'ACTIVE'=>'Y'),
        false,
        false,
        array('ID', 'NAME')
    );
    while ($arEl = $rsEl->Fetch()) {
        $arResult['SPECIALTIES'][$arEl['ID']] = $arEl['NAME'];
    }
}