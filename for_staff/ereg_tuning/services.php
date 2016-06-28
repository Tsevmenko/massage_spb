<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог услуг");
?><?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "services", array(
                                                                    "IBLOCK_TYPE"          => "medservices",
                                                                    "IBLOCK_ID"            => "8",
                                                                    "SECTION_ID"           => $_REQUEST["SECTION_ID"],
                                                                    "SECTION_CODE"         => "",
                                                                    "SERVICES_IBLOCK_TYPE" => "medservices",
                                                                    "SRVICES_IBLOCK_ID"    => "8",
                                                                    "COUNT_ELEMENTS"       => "N",
                                                                    "TOP_DEPTH"            => "1",
                                                                    "SECTION_FIELDS"       => array(
                                                                        0 => "",
                                                                        1 => "",
                                                                    ),
                                                                    "SECTION_USER_FIELDS"  => array(
                                                                        0 => "",
                                                                        1 => "",
                                                                    ),
                                                                    "SECTION_URL"          => "",
                                                                    "CACHE_TYPE"           => "N",
                                                                    "CACHE_TIME"           => "3600",
                                                                    "CACHE_GROUPS"         => "Y",
                                                                    "ADD_SECTIONS_CHAIN"   => "N"
                                                                ),
                                   false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>