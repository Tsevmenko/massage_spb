<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сегодня работают");
?><?$APPLICATION->IncludeComponent("medsite:working_today", "working_today", array(
                                                              "IBLOCK_TYPE"                     => "registry",
                                                              "IBLOCK_ID"                       => "#SHEDULES_BLOCK_ID#",
															  'SCHEDULE_LINK' => '/schedule/record_wizard.php?STEP=service&SHOW=employee&EMPLOYEE=#ID#',
                                                              "PER_ID"                          => "#PERIODS_BLOCK_ID#",
                                                              "PERSON_COUNT"                    => "20",
                                                              "PERSON_LINK"                     => "/employees/personal_info.php",
                                                              "STRUCTURE_LINK"                  => "/employees/index.php",
                                                              "STRUCTURE_FILTER"                => "users",
                                                              "DISPLAY_TOP_PAGER"               => "N",
                                                              "DISPLAY_BOTTOM_PAGER"            => "N",
                                                              "PAGER_TITLE"                     => "Сотрудники",
                                                              "PAGER_SHOW_ALWAYS"               => "N",
                                                              "PAGER_TEMPLATE"                  => "",
                                                              "PAGER_DESC_NUMBERING"            => "N",
                                                              "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                              "PAGER_SHOW_ALL"                  => "Y",
                                                              "DISPLAY_DATE"                    => "Y",
                                                              "DISPLAY_NAME"                    => "Y",
                                                              "DISPLAY_PICTURE"                 => "Y",
                                                              "DISPLAY_PREVIEW_TEXT"            => "Y",
                                                              "SHOW_SERVICES"                   => "N",
                                                              "DEFAULT_SERVICE"                 => ""
                                                          ),
                                   false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>