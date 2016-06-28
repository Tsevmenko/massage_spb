<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редактор расписания");
?>
<?$APPLICATION->IncludeComponent("medsite:medsite.edit_calendar", "modern", array(
                                                                    "IBLOCK_TYPE"           => "medservices",
                                                                    "IBLOCK_ID"             => "8",
                                                                    "SECT_IBLOCK_ID"        => "6",
                                                                    "BUILD_IBLOCK_ID"       => "7",
                                                                    "ORGANIZTION_IBLOCK_ID" => "4",
                                                                    "STRUCTURE_IBLOCK_ID"   => "5",
                                                                    "EDIT_GROUPS"           => array(
                                                                        0 => "1",
																		1 => "7",
																		2 => "9",
																		3 => "10",
                                                                    ),
                                                                    "SHOW_FROM_GROUPS"      => array(
                                                                        0 => "6",
                                                                    ),
                                                                    "DAY_START"             => "8",
                                                                    "DAY_END"               => "18",
                                                                    "WEB_STEP"              => "60"
                                                                ),
                                 false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>