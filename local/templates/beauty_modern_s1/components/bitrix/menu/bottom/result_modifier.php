<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!empty($arResult)) {
    $arSideItem = array();
    $arCenterItem = array();
    $column = 0;
    $i = 0;
    foreach($arResult as $arItem) {
        if (!empty($arItem['PARAMS']['CENTER'])) {
            $arCenterItem[] = $arItem;
            $id = false;
            continue;
        }

        if ($arItem["IS_PARENT"]) {
            $id = $i;
            $arSideItem[$id] = array_merge($arItem, array('CHILDREN' => array(), 'COUNT' => 1));
            $i++;
        } elseif ($arItem["DEPTH_LEVEL"] > 1 && $id !== false) {
            $arSideItem[$id]['CHILDREN'][] = $arItem;
            $arSideItem[$id]['COUNT']++;
        } else {
            $id = false;
            $arSideItem[$i] = $arItem;
            $i++;
        }
        
    }
    $arResult = array();
    $arResult['SIDE'] = $arSideItem;
    $arResult['CENTER'] = $arCenterItem;
}