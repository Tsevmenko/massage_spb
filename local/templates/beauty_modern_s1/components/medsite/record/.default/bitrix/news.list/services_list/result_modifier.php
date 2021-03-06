<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$rsTree = CIBlockSection::GetTreeList(array('IBLOCK_ID'=>$arParams['IBLOCK_ID']));
while ($arTree = $rsTree->GetNext()) {
	$arResult['SectionTree'][$arTree['ID']] = array(
		'ID' => $arTree['ID'],
		'NAME' => $arTree['NAME'],
		'SORT' => $arTree['SORT'],
		'IBLOCK_SECTION_ID' => $arTree['IBLOCK_SECTION_ID'],
		'DEPTH_LEVEL' => $arTree['DEPTH_LEVEL'],
		'ITEMS' => array(),
		'ITEMS_COUNT' => 0,
	);
}
$arResult['SectionTree'][0] = array(
	'ITEMS' => array(),
	'ITEMS_COUNT' => 0,
);
function IncItemsCount($id,&$tree) {
	$tree[$id]['ITEMS_COUNT'] ++;
	if (intval($tree[$id]['IBLOCK_SECTION_ID'])>0) {
		IncItemsCount($tree[$id]['IBLOCK_SECTION_ID'],$tree);
	}
}
if (!IsModuleInstalled('sale')) {
	$arPrices = array();
	$rsPrices = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$arParams['PRICE_IB_ID']),false,false,array('ID','PROPERTY_SERVICE','PROPERTY_PRICE'));
	while($arPrice = $rsPrices->GetNext()) {
		if (!array_key_exists($arPrice['PROPERTY_SERVICE_VALUE'],$arPrices))
			$arPrices[$arPrice['PROPERTY_SERVICE_VALUE']] = $arPrice['PROPERTY_PRICE_VALUE'];
	}
}
else {
	$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
	$arConvertParams = array();
}
$arElemntSections = array();
$rsSections = CIBlockElement::GetElementGroups($GLOBALS[$arParams['FILTER_NAME']]['ID'], true,array('ID','IBLOCK_ELEMENT_ID'));
while ($arSection = $rsSections->GetNext()) {
	$arElemntSections[$arSection['IBLOCK_ELEMENT_ID']][] = intval($arSection['ID']);
}

if (!empty($arResult['ITEMS'])) {
	foreach ($arResult['ITEMS'] as $arItem) {
		$arItem['IBLOCK_SECTION_ID'] = intval($arItem['IBLOCK_SECTION_ID']);
		if (IsModuleInstalled('sale')) {
			$arItem["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
		} else {
			if (array_key_exists($arItem['ID'],$arPrices))
				$arItem["PRICES"][] = array('VALUE'=>$arPrices[$arItem['ID']]);
		}
		if ($arItem['IBLOCK_SECTION_ID']==0) {
			$arResult['SectionTree'][0]['ITEMS'][$arItem['ID']] =  array(
				'ID' => $arItem['ID'],
				'NAME' => $arItem['NAME'],
				'IBLOCK_SECTION_ID' => 0,
				'PRICES' => $arItem["PRICES"],
			);
			IncItemsCount(0,$arResult['SectionTree']);
		} else {
			foreach($arElemntSections[$arItem['ID']] as $sectionID) {
				$arResult['SectionTree'][$sectionID]['ITEMS'][$arItem['ID']] =  array(
					'ID' => $arItem['ID'],
					'NAME' => $arItem['NAME'],
					'IBLOCK_SECTION_ID' => $sectionID,
					'PRICES' => $arItem["PRICES"],
				);
				IncItemsCount($sectionID,$arResult['SectionTree']);
			}
		}
	}
}
foreach ($arResult['SectionTree'] as $id=>$arSection) {
	if ($arSection['ITEMS_COUNT'] == 0) 
		unset($arResult['SectionTree'][$id]);
}
if (!array_key_exists('WITH_VIDEO',$arParams) || !is_array($arParams['WITH_VIDEO']))
	$arParams['WITH_VIDEO'] = array();
?>