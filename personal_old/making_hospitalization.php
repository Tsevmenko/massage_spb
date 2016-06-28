<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Запись на плановую госпитализацию");
?><?$APPLICATION->IncludeComponent("medsite:form.result.new", "making_hospitalization", array(
                                                                "WEB_FORM_ID"            => "1",
                                                                "QUESTION_IDS"           => array(
                                                                    0 => "17",
                                                                    1 => "18",
                                                                    2 => "19",
                                                                ),
                                                                "DEFAULTS"               => array(
                                                                    0 => "ФИО",
                                                                    1 => "email",
                                                                    2 => "телефон",
                                                                ),
                                                                "IGNORE_CUSTOM_TEMPLATE" => "N",
                                                                "USE_EXTENDED_ERRORS"    => "N",
                                                                "SEF_MODE"               => "N",
                                                                "SEF_FOLDER"             => "/personal/",
                                                                "CACHE_TYPE"             => "A",
                                                                "CACHE_TIME"             => "3600",
                                                                "LIST_URL"               => "",
                                                                "EDIT_URL"               => "",
                                                                "SUCCESS_URL"            => "",
                                                                "CHAIN_ITEM_TEXT"        => "",
                                                                "CHAIN_ITEM_LINK"        => "",
                                                                "VARIABLE_ALIASES"       => array(
                                                                    "WEB_FORM_ID" => "WEB_FORM_ID",
                                                                    "RESULT_ID"   => "RESULT_ID",
                                                                )
                                                            ),
                                   false
);?> <? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>