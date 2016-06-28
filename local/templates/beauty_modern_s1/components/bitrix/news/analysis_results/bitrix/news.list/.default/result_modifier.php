<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
foreach ($arResult["ITEMS"] as $key => $arItem) {
    $rsUser = CUser::GetByID($arItem["DISPLAY_PROPERTIES"]['PATIENT_ID']['VALUE']);
    $arUser = $rsUser->Fetch();
    $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]['PATIENT_ID']['DISPLAY_VALUE'] = $arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']." (".$arItem['PROPERTIES']['PATIENT_ID']['VALUE'].")";
}
