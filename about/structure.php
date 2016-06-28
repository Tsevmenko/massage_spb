<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Организационная структура");
?> <?$APPLICATION->IncludeComponent("medsite:medsite.structure.visual", "modern", array(
                                                                          "IBLOCK_TYPE" => "structure",
                                                                          "IBLOCK_ID"   => "15"
                                                                      ),
                                    false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>