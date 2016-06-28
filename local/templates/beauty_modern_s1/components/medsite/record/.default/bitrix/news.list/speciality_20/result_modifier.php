<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['LETTERS'] = array();
foreach($arResult["ITEMS"] as $arSection){
	$arResult['LETTERS'][substr($arSection['NAME'],0,1)][] = $arSection ;
}
ksort($arResult['LETTERS']);
$arParams['USER_INFO_BASE_LINK'] = $APPLICATION->GetCurPageParam('STEP=service&SHOW=speciality',array('WEEK_START','STEP','SHOW','SPECIALITY','SERVICE','EMPLOYEE'))
?>