<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$rsUser = CUser::GetByID($arResult['PROPERTIES']['PATIENT_ID']['VALUE']);
$arUser = $rsUser->Fetch();
$arResult["DISPLAY_PROPERTIES"]['PATIENT_ID']['DISPLAY_VALUE'] = $arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME'];

