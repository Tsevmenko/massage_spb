<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поля карточки пациента");
?>
<?$APPLICATION->IncludeComponent("medsite:edit_user_fields", ".default", array(
                                                               "IBLOCK_TYPE" => "medservices",
                                                               "IBLOCK_ID"   => "8",
                                                               "DAY_START"   => "8",
                                                               "DAY_END"     => "18",
                                                               "WEB_STEP"    => "60"
                                                           ),
                                 false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>