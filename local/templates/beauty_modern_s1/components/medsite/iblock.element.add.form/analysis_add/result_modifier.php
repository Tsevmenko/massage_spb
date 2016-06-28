<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if (CModule::IncludeModule('iblock')) {
    $arFilter = Array(
        "IBLOCK_ID" => 25,
        "ACTIVE"    => "Y",
    );
    $res = CIBlockElement::GetList(Array("SORT" => "ASC", "NAME" => "ASC"), $arFilter, false, false, array('ID', 'NAME', 'DETAIL_TEXT'));
    $arResult["PATTERNS"] = array();
    $arResult["PATTERNS"][] = array("ID" => 0, "NAME" => "Не выбрано", "DETAIL_TEXT" => "");
    while ($ar_fields = $res->GetNext()) {
        $arResult["PATTERNS"][] = $ar_fields;
    }
}
