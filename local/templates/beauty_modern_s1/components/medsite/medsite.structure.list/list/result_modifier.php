<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$arResult['USER_PROP'] = array();
$arRes = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("USER", 0, LANGUAGE_ID);
if (!empty($arRes)) {
    foreach ($arRes as $key => $val) {
        $arResult['USER_PROP'][$val["FIELD_NAME"]] = (strLen($val["EDIT_FORM_LABEL"]) > 0 ? $val["EDIT_FORM_LABEL"] : $val["FIELD_NAME"]);
    }
}
if (!CModule::IncludeModule('extranet') || !CExtranet::IsExtranetSite()) {
    if ($arResult['bAdmin']):
        global $INTRANET_TOOLBAR;
        __IncludeLang(dirname(__FILE__).'/lang/'.LANGUAGE_ID.'/'.basename(__FILE__));


    endif;
}
global $USER;
$arParams["SELECT"] = array("UF_FIELD");
$arParams["FIELDS"] = array("ID", "NAME", "EMAIL", "PERSONAL_PHONE", "WORK_POSITION", "PERSONAL_BIRTHDAY", "PERSONAL_PHOTO");

$filter = array("UF_TYPE" => 7);
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, $arParams);

$arResult["USERS"] = array();

while($user = $rsUsers->GetNext()){ $arResult["USERS"][] = $user; }

?>