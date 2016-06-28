<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
$APPLICATION->IncludeComponent("bitrix:main.map", ".default", array(
                                                    "CACHE_TYPE"       => "A",
                                                    "CACHE_TIME"       => "36000000",
                                                    "SET_TITLE"        => "Y",
                                                    "LEVEL"            => "1",
                                                    "COL_NUM"          => "3",
                                                    "SHOW_DESCRIPTION" => "N"
                                                ),
                               false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>