<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if (CModule::IncludeModule('iblock')) {
    foreach ($arResult["ITEMS"] as $key => $arItem) {
        $res = CIBlockSection::GetByID($arItem["IBLOCK_SECTION_ID"]);
        if ($ar_res = $res->GetNext()) {
            $arResult["ITEMS"][$key]['SECTION_NAME'] = $ar_res['NAME'];
        }
    }
}
