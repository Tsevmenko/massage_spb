<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$arUserFieldNames = array('PERSONAL_PHOTO', 'FULL_NAME', 'ID', 'LOGIN', 'NAME', 'SECOND_NAME', 'LAST_NAME', 'EMAIL', 'DATE_REGISTER', 'PERSONAL_PROFESSION', 'PERSONAL_WWW', 'PERSONAL_BIRTHDAY', 'PERSONAL_ICQ', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'PERSONAL_FAX', 'PERSONAL_MOBILE', 'PERSONAL_PAGER', 'PERSONAL_STREET', 'PERSONAL_MAILBOX', 'PERSONAL_CITY', 'PERSONAL_STATE', 'PERSONAL_ZIP', 'PERSONAL_COUNTRY', 'PERSONAL_NOTES', 'WORK_POSITION', 'WORK_COMPANY', 'WORK_PHONE', 'ADMIN_NOTES', 'XML_ID');
$userProp = array();
foreach ($arUserFieldNames as $name) {
    $userProp[$name] = GetMessage('ISL_'.$name);
    if (($name <> 'FULL_NAME') and ($name <> 'PERSONAL_PHOTO')) {
        $m_userProp[$name] = GetMessage('ISL_'.$name);
    }
}
$arRes = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("USER", 0, LANGUAGE_ID);
if (!empty($arRes)) {
    foreach ($arRes as $key => $val) {
        $userProp[$val["FIELD_NAME"]] = '* '.(strlen($val["EDIT_FORM_LABEL"]) > 0 ? $val["EDIT_FORM_LABEL"] : $val["FIELD_NAME"]);
    }
}
$s_type = array(
    'ASC'  => GetMessage('ISL_UP1'),
    'DESC' => GetMessage('ISL_DOWN1')
);
$arTemplateParameters = array(
    "DEFAULT_VIEW"        => array(
        "NAME"     => GetMessage('ISL_PARAM_DEFAULT_VIEW'),
        "TYPE"     => "LIST",
        "VALUES"   => array('list' => GetMessage('ISL_PARAM_DEFAULT_VIEW_VALUE_list')),
        "MULTIPLE" => "N",
        "DEFAULT"  => 'list',
    ),
    "USER_PROPERTY_TABLE" => array(
        "NAME"     => GetMessage('ISL_PARAM_USER_PROPERTY_TABLE'),
        "TYPE"     => "LIST",
        "VALUES"   => $userProp,
        "MULTIPLE" => "Y",
        "DEFAULT"  => array('FULL_NAME', 'PERSONAL_PHONE', 'EMAIL', 'WORK_POSITION', 'UF_DEPARTMENT'),
    ),
    "USER_PROPERTY_EXCEL" => array(
        "NAME"     => GetMessage('ISL_PARAM_USER_PROPERTY_TABLE_EXCEL'),
        "TYPE"     => "LIST",
        "VALUES"   => $userProp,
        "MULTIPLE" => "Y",
        "DEFAULT"  => array('FULL_NAME', 'PERSONAL_PHONE', 'EMAIL', 'WORK_POSITION', 'UF_DEPARTMENT'),
    ),
    "SHOW_SERVICES"       => array(
        "NAME"    => GetMessage('ISL_PARAM_USER_SHOW_SERVICES'),
        "TYPE"    => "CHECKBOX",
        "DEFAULT" => 'N',
    ),
    "DEFAULT_SERVICE"     => array(
        "NAME"    => GetMessage('ISL_PARAM_USER__ID'),
        "TYPE"    => "STRING",
        "DEFAULT" => '1',
    ),
    "USER_SORT"           => array(
        "NAME"     => GetMessage('ISL_SORT'),
        "TYPE"     => "LIST",
        "VALUES"   => $m_userProp,
        "MULTIPLE" => "N",
        "DEFAULT"  => array('LAST_NAME'),
    ),
    "SORT_TYPE"           => array(
        "NAME"     => GetMessage('ISL_SORT_TYPE1'),
        "TYPE"     => "LIST",
        "VALUES"   => $s_type,
        "MULTIPLE" => "N",
        "DEFAULT"  => array('ASC'),
    ),
);
if ($arCurrentValues['LIST_VIEW'] == 'list') {
    $arTemplateParameters['USER_PROPERTY_LIST'] = array(
        "NAME"     => GetMessage('ISL_PARAM_USER_PROPERTY_LIST'),
        "TYPE"     => "LIST",
        "VALUES"   => $userProp,
        "MULTIPLE" => "Y",
        "DEFAULT"  => array('UF_DEPARTMENT', 'PERSONAL_PHONE', 'PERSONAL_MOBILE', 'WORK_PHONE', 'EMAIL'),
    );
    if (CModule::IncludeModule('extranet'))
        $arTemplateParameters['EXTRANET_TYPE'] = array(
            "NAME"     => GetMessage('ISL_PARAM_EXTRANET_TYPE'),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => "",
        );
}
else {
    $arTemplateParameters['USER_PROPERTY_GROUP'] = array(
        "NAME"     => GetMessage('ISL_PARAM_USER_PROPERTY_GROUP'),
        "TYPE"     => "LIST",
        "VALUES"   => $userProp,
        "MULTIPLE" => "Y",
        "DEFAULT"  => array('PERSONAL_PHONE', 'PERSONAL_MOBILE', 'WORK_PHONE', 'EMAIL'),
    );
}
$arTemplateParameters['SPEC_IBLOCK_ID'] = array(
    "NAME"     => GetMessage('PARAM_SPEC_IBLOCK_ID'),
    "TYPE"     => "STRING",
    "DEFAULT"  => "",
);
?>