<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$this->setFrameMode(true);
?>
<div class="doctors-search" style="float:left;">
	<input id="searchbycity" class="input input-search input-round-search" type="text" placeholder="Город">
	<button class="btn btn-round-search">Поиск</button>
</div>
<br/><br/>
<?
if (!is_array($arResult['USERS']) || !($USERS_CNT = count($arResult['USERS']))):
    if ($arResult['EMPTY_UNFILTERED_LIST'] == 'Y'):
        ShowNote(GetMessage('INTR_ISL_TPL_NOTE_UNFILTERED'));
    elseif ($arParams['SHOW_ERROR_ON_NULL'] == 'Y'):
        ShowError(GetMessage('INTR_ISL_TPL_NOTE_NULL'));
    endif;
else:
    if (!is_array($arParams['USER_PROPERTY']) || count($arParams['USER_PROPERTY']) <= 0)
        $arParams['USER_PROPERTY'] = array('UF_DEPARTMENT', 'PERSONAL_PHONE', 'PERSONAL_MOBILE', 'WORK_PHONE');
    if ($arParams['SHOW_NAV_TOP'] == 'Y'):
        ?>
        <div class="bx-users-nav"><? echo $arResult['USERS_NAV']; ?></div>
    <? else: ?>
        <a name="nav_start"></a>
    <?
    endif;
    ?>
    <div class="content mt10 doctors-list">
        <?
		$arUserIDs = array_keys($arResult['USERS']);
		$arTime = MCSchedule::GetScheduleInfo($arUserIDs);
		$arUserServices = MCSchedule::GetEmployeeServices($arUserIDs, false);
        foreach ($arResult['USERS'] as $key => $arUser):

            $APPLICATION->IncludeComponent(
                'medsite:medsite.system.person',
                '',
                array(
                    'USER'                       => $arUser,
                    'USER_PROPERTY'              => $arParams['USER_PROPERTY'],
					"SCHEDULE_LINK"	=> $arParams["SCHEDULE_LINK"],
                    'USER_INFO_LINK'             => $arParams['USER_INFO_LINK'],
                    'PM_URL'                     => $arParams['PM_URL'],
                    'STRUCTURE_PAGE'             => $arParams['STRUCTURE_PAGE'],
                    'STRUCTURE_FILTER'           => $arParams['STRUCTURE_FILTER'],
                    'USER_PROP'                  => $arResult['USER_PROP'],
                    'NAME_TEMPLATE'              => $arParams['NAME_TEMPLATE'],
                    'SHOW_LOGIN'                 => $arParams['SHOW_LOGIN'],
                    'LIST_OBJECT'                => $arParams['LIST_OBJECT'],
                    'SHOW_FIELDS_TOOLTIP'        => $arParams['SHOW_FIELDS_TOOLTIP'],
                    'USER_PROPERTY_TOOLTIP'      => $arParams['USER_PROPERTY_TOOLTIP'],
                    "DATE_FORMAT"                => $arParams["DATE_FORMAT"],
                    "DATE_FORMAT_NO_YEAR"        => $arParams["DATE_FORMAT_NO_YEAR"],
                    "DATE_TIME_FORMAT"           => $arParams["DATE_TIME_FORMAT"],
                    "SHOW_YEAR"                  => $arParams["SHOW_YEAR"],
                    "CACHE_TYPE"                 => $arParams["CACHE_TYPE"],
                    "CACHE_TIME"                 => $arParams["CACHE_TIME"],
                    "PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
                    "PATH_TO_VIDEO_CALL"         => $arParams["~PATH_TO_VIDEO_CALL"],
                    "SRVICES_BLOCK"              => $arParams["SRVICES_BLOCK"],
                    "SHOW_SERVICES"              => 'N',
                    "DEFAULT_SERVICE"            => $arParams["DEFAULT_SERVICE"],
                    "REVIEW_FORM_ID"            => $arParams["REVIEW_FORM_ID"],
                    "REVIEW_URL"            => $arParams["REVIEW_URL"],
                ),
                null,
                array('HIDE_ICONS' => 'Y')
            );
        endforeach;
        ?>
    </div>
    <?
    if ($arParams['SHOW_NAV_BOTTOM'] == 'Y'):
        ?>
        <div class="bx-users-nav"><? echo $arResult['USERS_NAV']; ?></div>
    <?
    endif;
endif;
?>
<script>
	$("#searchbycity").on("keyup", function(){
		var val = $(this).val();
		var citystrlen = val.length;

		$(".doctor-item.white-box").each(function(){
			if($(this).parent().data("city").substr(0,citystrlen) != val)
				$(this).hide();
			else
				$(this).show();
		});

	});
</script>