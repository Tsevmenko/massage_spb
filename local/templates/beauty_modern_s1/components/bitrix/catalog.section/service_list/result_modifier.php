<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['SERVICES'] = array();
$arItems = array();


foreach ($arResult['ITEMS'] as $arElement) {
    if (!IsModuleInstalled('sale')) {

    }
    $arItems[$arElement['ID']] = $arElement;
    $arRootService = CIBlockSection::GetNavChain(false,$arElement['IBLOCK_SECTION_ID'])->Fetch();
    if (is_array($arRootService)) {
        $arResult['SERVICES'][$arRootService['ID']]['NAME'] = $arRootService['NAME'];
        $arResult['SERVICES'][$arRootService['ID']]['ITEMS'][] = $arElement['ID'];
    }

}
$arResult['ITEMS'] = $arItems;
unset($arItems);

$IDs = array_keys($arResult['ITEMS']);

if (!IsModuleInstalled('sale')) {
	$arPrices = array();
	$rsPrices = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$arParams['PRICE_IB_ID'], 'PROPERTY_SERVICE_VALUE'=>$IDs),false,false,array('ID','PROPERTY_SERVICE','PROPERTY_PRICE'));
	while($arPrice = $rsPrices->GetNext()) {
		if (!array_key_exists($arPrice['PROPERTY_SERVICE_VALUE'],$arPrices))
			$arPrices[$arPrice['PROPERTY_SERVICE_VALUE']] = $arPrice['PROPERTY_PRICE_VALUE'];
	}
    if (count($arPrices) > 0) {
        foreach ($arResult['ITEMS'] as &$arElement) {
            if (isset($arPrices[$arElement['ID']])){
                $arElement['MIN_PRICE'] = $arPrices[$arElement['ID']];
            }
        }
    }
}
