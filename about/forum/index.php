<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Форум");
?><?$APPLICATION->IncludeComponent("bitrix:forum", ".default", array(
                                                     "THEME"                        => "white",
                                                     "SHOW_TAGS"                    => "Y",
                                                     "SHOW_AUTH_FORM"               => "Y",
                                                     "SHOW_NAVIGATION"              => "Y",
                                                     "TMPLT_SHOW_ADDITIONAL_MARKER" => "",
                                                     "SMILES_COUNT"                 => "100",
                                                     "USE_LIGHT_VIEW"               => "Y",
                                                     "FID"                          => array('1'),
                                                     "FILES_COUNT"                  => "5",
                                                     "SEF_MODE"                     => "Y",
                                                     'FORUM_ID'                     => "1",
                                                     "SEF_FOLDER"                   => "/about/forum/",
                                                     "CACHE_TYPE"                   => "A",
                                                     "CACHE_TIME"                   => "3600",
                                                     "CACHE_TIME_USER_STAT"         => "60",
                                                     "FORUMS_PER_PAGE"              => "10",
                                                     "TOPICS_PER_PAGE"              => "10",
                                                     "MESSAGES_PER_PAGE"            => "10",
                                                     "TIME_INTERVAL_FOR_USER_STAT"  => "10",
                                                     "IMAGE_SIZE"                   => "500",
                                                     "SET_TITLE"                    => "Y",
                                                     "USE_RSS"                      => "N",
                                                     "SHOW_VOTE"                    => "N",
                                                     "SHOW_RATING"                  => "N",
                                                     "SHOW_SUBSCRIBE_LINK"          => "N",
                                                     "SHOW_LEGEND"                  => "Y",
                                                     "SHOW_STATISTIC"               => "Y",
                                                     "SHOW_NAME_LINK"               => "Y",
                                                     "SHOW_FORUMS"                  => "Y",
                                                     "SHOW_FIRST_POST"              => "N",
                                                     "SHOW_AUTHOR_COLUMN"           => "N",
                                                     "PATH_TO_SMILE"                => "/bitrix/images/forum/smile/",
                                                     "PATH_TO_ICON"                 => "/bitrix/images/forum/icon/",
                                                     "PAGE_NAVIGATION_TEMPLATE"     => "forum",
                                                     "PAGE_NAVIGATION_WINDOW"       => "5",
                                                     "WORD_WRAP_CUT"                => "23",
                                                     "WORD_LENGTH"                  => "50",
                                                     "SEO_USER"                     => "N",
                                                     "USER_PROPERTY"                => array(),
                                                     "HELP_CONTENT"                 => "",
                                                     "RULES_CONTENT"                => "",
                                                     "CHECK_CORRECT_TEMPLATES"      => "Y",
                                                     "PATH_TO_AUTH_FORM"            => "",
                                                     "DATE_FORMAT"                  => "d.m.Y",
                                                     "DATE_TIME_FORMAT"             => "d.m.Y H:i:s",
                                                     "SEND_MAIL"                    => "E",
                                                     "SEND_ICQ"                     => "A",
                                                     "SET_NAVIGATION"               => "Y",
                                                     "SET_PAGE_PROPERTY"            => "Y",
                                                     "SHOW_FORUM_ANOTHER_SITE"      => "Y",
                                                     "SEF_URL_TEMPLATES"            => array(
                                                         "index"        => "index.php",
                                                         "list"         => "#FID#/",
                                                         "read"         => "#FID#/#TID#/",
                                                         "message"      => "messages/#FID#/#TID#/#MID#/",
                                                         "help"         => "help/",
                                                         "rules"        => "rules/",
                                                         "message_appr" => "messages/approve/#FID#/#TID#/",
                                                         "message_move" => "messages/move/#FID#/#TID#/#MID#/",
                                                         "pm_list"      => "pm/#FID#/",
                                                         "pm_edit"      => "pm/#FID#/#MID#/user#UID#/#mode#/",
                                                         "pm_read"      => "pm/#FID#/#MID#/",
                                                         "pm_search"    => "pm/search/",
                                                         "pm_folder"    => "pm/folders/",
                                                         "rss"          => "rss/#TYPE#/#MODE#/#IID#/",
                                                         "search"       => "search/",
                                                         "subscr_list"  => "subscribe/",
                                                         "active"       => "topic/new/",
                                                         "topic_move"   => "topic/move/#FID#/#TID#/",
                                                         "topic_new"    => "topic/add/#FID#/",
                                                         "topic_search" => "topic/search/",
                                                         "user_list"    => "users/",
                                                         "profile"      => "user/#UID#/edit/",
                                                         "profile_view" => "user/#UID#/",
                                                         "user_post"    => "user/#UID#/post/#mode#/",
                                                         "message_send" => "user/#UID#/send/#TYPE#/",
                                                     )
                                                 ),
                                   false
);?> <? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>