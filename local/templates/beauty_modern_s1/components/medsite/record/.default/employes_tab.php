<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="search-in-page col-margin-top">
	<form>
		<div class="search-in-page-wrapper employee-search">
			<input class="input input-block input-search search-in-page-input" type="text" name="search-in-page" placeholder="<?=GetMessage('MCRWizard_SEARCH_EMPLOYEE')?>">
			<button id="bx-btn-reset" class="search-in-page-reset" type="reset">
				<i class="icon icon-search-reset"></i>
			</button>
			<button class="search-in-page-btn"><i class="icon icon-search"></i></button>
		</div>
	</form>
</div>
<?
	$arParams['USER_INFO_BASE_LINK'] = $APPLICATION->GetCurPageParam('STEP=service&SHOW=employee',array('STEP','SHOW','SPECIALITY','SERVICE','EMPLOYEE'));
	if (array_key_exists('VIDEO',$_REQUEST)) {
		$employeeIDs = $arResult['VideoEmployes'];
	} else {
		$employeeIDs = array_keys($arResult['ScheduleEmployesData']);
	}

?>
<div class="step-doctors">

	<?$weekStart = MCDateTimeTools::GetWeekStartByDate('Y-m-d')?>
	<?$nextWeekStart = MCDateTimeTools::AddDaysToYMDs($weekStart,7,false,'Y-m-d')?>
	<?$arTimeThisWeek = MCSchedule::GetScheduleInfo($employeeIDs, $arResult['VARIABLES']["COMPANY"])?>
	<?$arTimeNextWeek = MCSchedule::GetScheduleInfo($employeeIDs,$arResult['VARIABLES']["COMPANY"], $nextWeekStart)?>
	<?foreach ($employeeIDs as $userID):?>
		<?$arSearch[$userID] = array(
			'id'=>$userID,
			'video'=>in_array($userID,$arResult['VideoEmployes']),
			'name' => strtoupper($arResult['ScheduleEmployesData'][$userID]['LAST_NAME'].' '.$arResult['ScheduleEmployesData'][$userID]['NAME'].' '.$arResult['ScheduleEmployesData']['SECOND_NAME']),
		)?>
			<?
			$arParams['USER_INFO_LINK'] = $arParams['USER_INFO_BASE_LINK'].'&EMPLOYEE='.$userID;
			if ($arResult['ScheduleEmployesData'][$userID]['PERSONAL_PHOTO']) {
				$arFile = CFile::ResizeImageGet(
					$arResult['ScheduleEmployesData'][$userID]['PERSONAL_PHOTO'],
					array("width" => 80, "height" => 80),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					false
				);
				if (!empty($arFile)) {
					$img = $arFile['src'];
				}
				else {
					$img = '/bitrix/components/medsite/medsite.system.person/templates/.default/images/nopic_user_100_noborder.gif';
				}
			}
			else {
				$img = '/bitrix/components/medsite/medsite.system.person/templates/.default/images/nopic_user_100_noborder.gif';
			}
			?>

			<div id="emp_<?=$userID?>" class="step-doctor col-margin-top">
				<div class="step-doctor-header">
					<div class="step-doctor-col">
						<div class="doctor-photo" style="background-image: url('<?=$img?>');"></div>
					</div>
					<div class="step-doctor-col col-4 pl0">
						<div class="step-doctor-name"><?=$arResult['ScheduleEmployesData'][$userID]['LAST_NAME'].' '.$arResult['ScheduleEmployesData'][$userID]['NAME'].' '.$arResult['ScheduleEmployesData']['SECOND_NAME']?></div>
					</div>
					<div class="step-doctor-col col-5">
						<div class="step-doctor-info">
							<?= $arResult['ScheduleEmployesData'][$userID]['WORK_POSITION'] ?>
						</div>
						<?if (!empty($arParams['EMPLOYEE_PERSONAL_PAGE'])):?>
							<a href="<?=str_replace('#ID#',$userID,$arParams['EMPLOYEE_PERSONAL_PAGE'])?>" class="border-link mt20"><?=GetMessage('EMP_TAB_PERSONAL')?></a>
						<?endif?>
					</div>
					<div class="step-doctor-col col-3 ta-right">
						<a href="<?=$arParams['USER_INFO_LINK']?>" class="btn"><?=GetMessage('EMP_TAB_RECORD')?></a>
					</div>
				</div> <!-- .step-doctor-header -->
				<div class="step-doctor-week clearfix">
					<?for($i=$arResult['nowDayNumber'];$i<$arResult['nowDayNumber']+7;$i++):?>
						<?if ($i>7) {
							$showDay = $i-7;
							$weekData = $arTimeNextWeek;
							$day = date('d',MCDateTimeTools::AddDaysToYMDs($nextWeekStart,$i-8,true));
						} else {
							$showDay = $i;
							$weekData = $arTimeThisWeek;
							$day = date('d',MCDateTimeTools::AddDaysToYMDs($weekStart,$i-1,true));
						}?>

						<div class="step-doctor-day">
							<?if (is_array($weekData[$userID]) &&  array_key_exists($showDay,$weekData[$userID]['DAYS'])):?>
								<?if ($showDay==$arResult['nowDayNumber']):?>
									<b><?=GetMessage('EMP_TAB_DAY_'.$showDay)?>, <?=$day?></b>
								<?else:?>
									<?=GetMessage('EMP_TAB_DAY_'.$showDay)?>, <?=$day?>
								<?endif;?>
								<div class="fz14"><?=substr($weekData[$userID]['DAYS'][$showDay]['START_TIME'],0,5)?>-<?=substr($weekData[$userID]['DAYS'][$showDay]['END_TIME'],0,5)?></div>
							<?endif?>
							<?if (is_array($weekData[$userID]) &&  array_key_exists($showDay,$weekData[$userID]['DO_NOT_WORK'])):?>
								<?if ($showDay==$arResult['nowDayNumber']):?>
									<b><?=GetMessage('EMP_TAB_DAY_'.$showDay)?>, <?=$day?></b>
								<?else:?>
									<?=GetMessage('EMP_TAB_DAY_'.$showDay)?>, <?=$day?>
								<?endif;?>
								<div class="fz14"><?=GetMessage('EMP_TAB_REC_TYPE_'.$weekData[$userID]['DO_NOT_WORK'][$showDay]['TYPE_CODE'])?></div>
							<?endif?>
						</div>
					<?endfor;?>
				</div> <!-- .step-doctor-week clearfix -->
			</div> <!-- .step-doctor col-margin-top -->
	<?endforeach;?>
</div>

<script>
	var employeeSearch = <?=CUtil::PhpToJsObject($arSearch)?>;
	var employeeSearchText='';
	jQuery(document).ready(function($) {
		$('.big-search-inner').on('submit', 'form', function() {
			return false;
		});

		// Form buttons.
		$('.typeahead-wrapper, .employee-search')
			.on('keyup change input', 'input[type="text"]', function () {
				var thisPP = $(this).parent().parent();
				var newSearch = $(this).val().toUpperCase();
				if (newSearch!=employeeSearchText){
					employeeSearchText = newSearch;
					if (employeeSearchText.length >1) {
						SearchForEmployee(employeeSearchText);
					}
					else {
						ClearSearchForEmployee();
					};
				}
			})
			.on('click', '#bx-btn-reset', function() {
				$(this).parent().parent().find('input').val('');
				ClearSearchForEmployee();
			});
		function SearchForEmployee (text) {
			$('#employeeSearchR').show();
			var searchVideo = $('#SHOW_VIDEO')[0].checked;
			$.each(employeeSearch, function( index, value ) {
				var found = value.name.toUpperCase().indexOf(employeeSearchText);
				if (searchVideo && value.video==false) found = -1;
				var NAME = $('#emp_'+value.id).find('div.step-doctor-name');
				var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'');

				if (found==-1) {
					$('#emp_'+value.id).hide();
				} else {
					var replaceFrom = text.substr(found,employeeSearchText.length);
					var replaceTo = '<span class="found">'+replaceFrom+'</span>';
					text = text.replace(replaceFrom,replaceTo);
					$('#emp_'+value.id).show();
				}
				NAME.html(text);
			});
			$('#employeeSearchR').hide();
		}
		function ClearSearchForEmployee () {
			var searchVideo = $('#SHOW_VIDEO')[0].checked;
			$.each(employeeSearch, function( index, value) {
				if (!searchVideo || value.video) {
					$('#emp_'+value.id).show();
				} else {
					$('#emp_'+value.id).hide();
				}
				var NAME = $('#emp_'+value.id).find('div.step-doctor-name');
				var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'');
				NAME.html(text);
			});
			$('#employeeSearchR').hide();
		}
		if ($('#SHOW_VIDEO')[0].checked)
			ClearSearchForEmployee ();
	});

</script>
