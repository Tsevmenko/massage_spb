<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("запись на прием");
?><?$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    "",
    Array(
        "SEF_MODE"               => "N",
        "WEB_FORM_ID"            => "1",
        "LIST_URL"               => "result_list.php",
        "EDIT_URL"               => "result_edit.php",
        "SUCCESS_URL"            => "",
        "CHAIN_ITEM_TEXT"        => "",
        "CHAIN_ITEM_LINK"        => "",
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "USE_EXTENDED_ERRORS"    => "N",
        "CACHE_TYPE"             => "A",
        "CACHE_TIME"             => "3600",
        "VARIABLE_ALIASES"       => Array(
            "WEB_FORM_ID" => "WEB_FORM_ID",
            "RESULT_ID"   => "RESULT_ID"
        )
    ),
    false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>