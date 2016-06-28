<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
<? $APPLICATION->SetTitle("Оказанные услуги"); ?>

<?$APPLICATION->IncludeComponent("medsite:talon", "personal_services", array(
                                                    "SHOW_PROPERTIES" => array(
                                                        0 => "EMPLOYEE",
                                                        1 => "DEPARTAMENT",
                                                        2 => "SERVICE",
                                                        3 => "ORGANIZATION",
                                                        4 => "PLACE",
                                                        5 => "",
                                                        6 => "",
                                                    ),
	"SHOW_SEARCH" => "N",
	"DETAIL_URL_SERVICES" => "/m/services/#SECTION_ID#/#ELEMENT_ID#/"
                                                ),
                                 false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>