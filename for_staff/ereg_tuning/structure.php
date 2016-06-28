<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Структура организации и сотрудники");
?> <?$APPLICATION->IncludeComponent("medsite:medsite.organiztion_structure", ".default", array(
                                                                               "IBLOCK_TYPE"            => "foundations",
                                                                               "ORGANIZTION_IBLOCK_ID"  => "4",
                                                                               "STRUCTURE_IBLOCK_ID"    => "5",
                                                                               "AJAX_MODE"              => "N",
                                                                               "AJAX_OPTION_JUMP"       => "N",
                                                                               "AJAX_OPTION_STYLE"      => "Y",
                                                                               "AJAX_OPTION_HISTORY"    => "N",
                                                                               "PM_URL"                 => "/messages/form/#USER_ID#/",
                                                                               "EC_URL"                 => "/for_staff/ereg_tuning/schedules.php?empl=#USER_ID#",
                                                                               "USER_PROPERTY"          => array(),
                                                                               "SHOW_FROM_GROUPS"       => array(
                                                                                   0 => "6",
                                                                               ),
                                                                               "DISPLAY_DATE"           => "Y",
                                                                               "DISPLAY_NAME"           => "Y",
                                                                               "DISPLAY_PICTURE"        => "Y",
                                                                               "DISPLAY_PREVIEW_TEXT"   => "Y",
                                                                               "AJAX_OPTION_ADDITIONAL" => ""
                                                                           ),
                                    false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>