<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Специалисты");
?>

<div class="negative-margin clearfix">
					<section>
								<?$APPLICATION->IncludeComponent("medsite:medsite.search", "mobile_employ", array(
                                                                "SHEDULES_BLOCK"             => "#SHEDULES_BLOCK_ID#",
                                                                "SRVICES_BLOCK"              => "8",
                                                               "STRUCTURE_PAGE"             => "index.php",
                                                               "PM_URL"                     => "/company/personal/messages/chat/#USER_ID#/",
                                                               "PATH_TO_CONPANY_DEPARTMENT" => "/employees/index.php?set_filter_structure=Y&users_UF_DEPARTMENT=#ID#",
                                                               "STRUCTURE_FILTER"           => "structure",
                                                               "FILTER_1C_USERS"            => "N",
                                                               "USERS_PER_PAGE"             => "700",
                                                               'USER_INFO_LINK'             => "/employees/personal_info.php",
                                                               "FILTER_SECTION_CURONLY"     => "N",
                                                               "NAME_TEMPLATE"              => "#NOBR##LAST_NAME# #NAME##/NOBR# #SECOND_NAME#",
                                                               "SHOW_LOGIN"                 => "Y",
                                                               "SHOW_ERROR_ON_NULL"         => "Y",
                                                               "ALPHABET_LANG"              => array(
                                                                   0 => "ru",
                                                                   1 => "",
                                                               ),
                                                               "NAV_TITLE"                  => "Сотрудники",
                                                               "SHOW_NAV_TOP"               => "Y",
                                                               "SHOW_NAV_BOTTOM"            => "Y",
                                                               "SHOW_UNFILTERED_LIST"       => "Y",
                                                               "AJAX_MODE"                  => "N",
                                                               "AJAX_OPTION_SHADOW"         => "Y",
                                                               "AJAX_OPTION_JUMP"           => "N",
                                                               "AJAX_OPTION_STYLE"          => "Y",
                                                               "AJAX_OPTION_HISTORY"        => "N",
                                                               "CACHE_TYPE"                 => "A",
                                                               "CACHE_TIME"                 => "3600",
                                                               "DATE_FORMAT"                => "d.m.Y",
                                                               "DATE_FORMAT_NO_YEAR"        => "d.m",
                                                               "DATE_TIME_FORMAT"           => "d.m.Y H:i:s",
                                                               "SHOW_YEAR"                  => "Y",
                                                               "FILTER_NAME"                => "users",
                                                               "FILTER_DEPARTMENT_SINGLE"   => "Y",
                                                               "FILTER_SESSION"             => "Y",
                                                               "DEFAULT_VIEW"               => "list",
                                                               "LIST_VIEW"                  => "list",
                                                               "USER_PROPERTY_TABLE"        => array(
                                                                   0 => "FULL_NAME",
                                                                   1 => "EMAIL",
                                                                   2 => "UF_DEPARTMENT",
                                                               ),
                                                               "USER_PROPERTY_EXCEL"        => array(
                                                                   0 => "FULL_NAME",
                                                                   1 => "EMAIL",
                                                                   2 => "UF_PLACE",
                                                                   3 => "UF_DEPARTMENT",
                                                               ),
                                                               "GROUPS"                     => array(
                                                                   0 => "5",
                                                                   1 => "6",
                                                               ),
                                                               "SHOW_SERVICES"              => "N",
                                                               "DEFAULT_SERVICE"            => "",
                                                               "USER_SORT"                  => "LAST_NAME",
                                                               "SORT_TYPE"                  => "ASC",
                                                               "USER_PROPERTY_LIST"         => array(
                                                                   0 => "PERSONAL_PHONE",
                                                                   1 => "UF_FOUNDATION",
                                                                   2 => "UF_DEPARTMENT",
                                                               ),
                                                               "AJAX_OPTION_ADDITIONAL"     => ""
                                                           ),
                                   false);?>
	</section>		
</div> <!-- .negative-margin -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>