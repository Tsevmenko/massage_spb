<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
	$baseUrl = $APPLICATION->GetCurPageParam($arParams['NEXT_STEP_LINK_TEMPLATE'].'&SERVICE='.$arParams['SERVICE'],array('DATE','START','TYPE','STEP','END','EMPLOYEE','SERVICE'));
	$arSearch = array();
?>
<?if (!isset($arResult['EMPLOYES']) || empty($arResult['EMPLOYES'])):?>
	<div style="padding: 15px 0 10px 0;">
		<?ShowError(GetMessage('NO_SCHEDULE_FOUND'));?>
	</div>
<?else:?>
	<template id="schedule-template">
		<?
		//  Modal window template
		//  Uses cnJsTemplater
		?>
		<div class="cn-modal cn-modal-medium">
			<div class="mfp-close cn-modal-close">&times;</div>
			<div class="cn-modal-header">
				<?=GetMessage('REC_TOOLTIP_CHECK')?>
			</div>
			<div class="cn-modal-content">

				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_CLINIC')?></b>
					</div>
					<div class="col col-8">
						<?=htmlspecialcharsbx($arParams['COMPANY']['NAME'])?>
					</div>
				</div>

				{% if(this.data.address) { %}
				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_ADDRESS')?></b>
					</div>
					<div class="col col-8">
						{% this.data.address %}
					</div>
				</div>
				{% } %}

				<?if (!empty($arParams['SPECIALITY_NAME'])):?>
				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_SPECIALITY')?></b>
					</div>
					<div class="col col-8">
						<?=htmlspecialcharsbx($arParams['SPECIALITY_NAME'])?>
					</div>
				</div>
				<?endif;?>

				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_SERVICE')?></b>
					</div>
					<div class="col col-8">
						<?=htmlspecialcharsbx($arParams['SERVICE_NAME'])?>
					</div>
				</div>

				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_REC_DATE')?></b>
					</div>
					<div class="col col-8">
						{% this.data.date %}, <b>{% this.time %}</b>
					</div>
				</div>

				{% if(this.data.kabinet) { %}
				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_CABINET')?></b>
					</div>
					<div class="col col-8">
						{% this.data.kabinet %}
					</div>
				</div>
				{% } %}

				{% if(this.data.type) { %}
				<div class="content col-margin-bottom">
					<div class="col col-4 ta-right">
						<b><?=GetMessage('REC_TOOLTIP_REC_TYPE')?></b>
					</div>
					<div class="col col-8">
						{% this.data.type %}
					</div>
				</div>
				{% } %}

				{% if(this.link) { %}
				<div class="ta-center">
					<a href="{% this.link %}" class="btn btn-big"><?=GetMessage('RECORD_TO')?></a>
				</div>
				{% } %}
			</div>
		</div>

	</template>

	<?$arTime = MCSchedule::GetScheduleInfo(array_keys($arResult['EMPLOYES']),$arParams['COMPANY']['ID'],$arParams['WEEK_DAY']);?>
	<? foreach ($arResult['EMPLOYES'] as $employee): ?>
		<?if (!$employee['SCHEDULE']) continue;?>
		<?$arSearch[$employee['ID']] = array(
			'id'=>$employee['ID'],
			'name' => strtoupper($employee['LAST_NAME'].' '.$employee['NAME'].' '.$employee['SECOND_NAME']),
		)?>
		<?if (empty($employee['WEEKS'][$arResult['WEEK_START_YMD']]['SCHEDULE'])) continue;?>
		<?$collapsed = false;?>
		<?$baseEmpUrl = $baseUrl.'&EMPLOYEE='.$employee['ID'];?>
		<div class="schedule-item white-box clearfix" id="emp_<?=$employee['ID']?>">
		<div class="schedule-doctor col-4">
			<div class="schedule-doctor-item">
				<?
				$APPLICATION->IncludeComponent("medsite:medsite.system.person", "record", Array(
						"USER"          => $employee,
						"LIST_VIEW"     => "list",
						"USER_PROPERTY" => array(
							0 => "WORK_POSITION",
						),
						"SHOW_SERVICES" => "N",
						'USER_INFO_LINK' => $arParams['EMPLOYEE_PERSONAL_PAGE'],
						'WORK_TIME' => $arTime[$employee['ID']],
					),
					$component->GetParent()
				);
				?>
			</div>
		</div>
		<div class="schedule-week-wrapper col-8">
			<div class="schedule-week collapsed" data-week-id="ww<?=$employee['ID']?>">
				<?foreach ($employee['WEEKS'][$arResult['WEEK_START_YMD']]['SCHEDULE'] as $day=>$dayData):?>
					<?
					$dayTimeStamp = strtotime($dayData['DATE']);
					$dayNumber = date('j',$dayTimeStamp);
					$month = date('n',$dayTimeStamp);
					$baseEmpDateUrl = $baseEmpUrl.'&DATE='.$dayData['DATE'];
					?>
					<div class="schedule-day equal">
						<?if (empty($dayData['ITEMS'])):?>
							<div class="hour hour-height-3 hour-relax">
								<div class="hour-text">
									<?=GetMessage('RECORD_NO_RECORD_ON_THIS_DAY')?>
								</div>
							</div>
						<?endif?>
						<?foreach($dayData['ITEMS'] as $timeData):?>
							<?if (!array_key_exists('timeStart',$timeData) || ($timeData['duration']==0 && $timeData['typeCode']=='talon')):?>
								<?if ($timeData['typeCode']!='talon'):?>
									<div class="hour hour-height-1 hour-busy">
										<div class="hour-text">
											<?=GetMessage('RECORD_NOT_FREE')?>
										</div>
									</div>
								<?elseif($timeData['duration']==0 && $timeData['typeCode']=='talon'):?>
									<div class="hour disabled">
										<?=$timeData['timeStart']?>
									</div>
								<?endif?>
							<?else:?>
								<div class="<?=$timeData['typeCode']?>">
									<?if ($dayData['OLD']): $timeData['description'] = GetMessage('NOT_FOR_RECORD')?>
										<div class="disabled">
									<?endif;?>
										<?if ($timeData['typeCode']=='talon'):?>
											<?
											$i=0;
											$itemCount = floor(($timeData['timeEndMinutes']-$timeData['timeStartMinutes'])/$timeData['duration']);
											$itemCount = $itemCount-2;
											?>
											<?for($start = $timeData['timeStartMinutes'];$start < $timeData['timeEndMinutes'];$start = $start+$timeData['duration']): $i++?>
												<?
												$url='';
												$startTime = MCDateTimeTools::TimeInMinutesToString($start,false,false);
												$disabled='';
												if (!$employee['WEEKS'][$arResult['WEEK_START_YMD']]['IS_NOW_WEEK'] || (!$dayData['OLD'] && ($day!=$arResult['nowDayNumber'] || $arResult['nowTime']<$start))) {
													$disabled='';
													$free = MCSchedule::CheckFreeTime($dayData['DATE'],$dayTimeStamp+($start*60),$dayTimeStamp+(($start+$timeData['duration'])*60),$employee['ID']);
													if (!$free) {
														$description = GetMessage('RECORD_NOT_FREE');
													}
													else{
														$url = $baseEmpDateUrl.'&START='.$startTime.'&END='.$timeData['duration'].'&TTYPE='.$timeData['idType'];
													}
												}else {
													$free = true;
													$disabled='disabled';
													$description = GetMessage('NOT_FOR_RECORD');
												}
												?>
												<?if ($i>=$arParams['COLLAPSE_IF_MORE'] && $i<$itemCount) {
													$reserved ='reserved';
													$collapsed = true;
												} else {
													$reserved = '';
												}
												?>
												<div class="hour <?=$reserved?>">
													<?if (!empty($url)):?>
														<?
														if (!empty($timeData['sector']['NAME'])) {
															$address =  $timeData['sector']['NAME'];
														}
														elseif (!empty($arParams['COMPANY']['PROPERTY_ADRESS_VALUE'])) {
															$address =  $arParams['COMPANY']['PROPERTY_ADRESS_VALUE'];
														}
														?>
														<a <?=empty($timeData['placement'])?'':'data-cabinet="'.$timeData['placement']['NAME'].'"'?>
														   data-type="<?=GetMessage('TYPE_INFO_'.$timeData['typeCode'])?>"
														   data-date="<?=$dayNumber.' '.GetMessage('SCHEDULE_MONTH_'.$month)?>"
														   data-address="<?=$address?>"
															class="btn btn-schedule" href="<?=$url?>"><?=$startTime;?></a>
													<?else:?>
														<?=$startTime;?>
													<?endif?>
												</div>
											<?endfor;?>
										<?else:?>
											<?
											$startTime = MCDateTimeTools::TimeInMinutesToString($timeData['timeStartMinutes'],false,false);
											if ($timeData['typeCode']=='wish') {
												if (!$employee['WEEKS'][$arResult['WEEK_START_YMD']]['IS_NOW_WEEK'] || (!$dayData['OLD'] && ($day!=$arResult['nowDayNumber'] || $arResult['nowTime']<$timeData['timeEndMinutes']))){
													$url = $baseEmpDateUrl.'&START='.$startTime.'&END='.$timeData['duration'].'&TTYPE='.$timeData['idType'];
												}
											} else {
												$url = '';
											}
											if (in_array($timeData['idType'],array(rec_type_hospital, rec_type_vacation, rec_type_business_trip))) {
												$showTime = false;
											} else {
												$showTime = true;
											}
											?>
											<div class="hour hour-busy hour-height-4">
												<?if ($showTime):?>
													<div class="timeTop" data-original-title="" title=""><?=$timeData['timeStart']?></div>
												<?endif;?>
												<div class="hour-text">
													<?if (!empty($url)):?>
														<?
														if (!empty($timeData['sector']['NAME'])) {
															$address =  $timeData['sector']['NAME'];
														}
														elseif (!empty($arParams['COMPANY']['PROPERTY_ADRESS_VALUE'])) {
															$address =  $arParams['COMPANY']['PROPERTY_ADRESS_VALUE'];
														}
														?>
														<a <?=empty($timeData['placement'])?'':'data-cabinet="'.$timeData['placement']['NAME'].'"'?>
															data-type="<?=GetMessage('TYPE_INFO_'.$timeData['typeCode'])?>"
															data-date="<?=$dayNumber.' '.GetMessage('SCHEDULE_MONTH_'.$month)?>"
															data-address="<?=$address?>"
															class="link-schedule" href="<?=$url?>"><?=GetMessage('RECORD_TEXT_'.$timeData['typeCode'])?></a>
													<?else:?>
														<span  <?=empty($timeData['placement'])?'':'data-cabinet="'.$timeData['placement']['NAME'].'"'?>
															   data-type="<?=GetMessage('TYPE_INFO_'.$timeData['typeCode'])?>"
															   data-date="<?=$dayNumber.' '.GetMessage('SCHEDULE_MONTH_'.$month)?>"
															   data-address="<?=$address?>"
															   <?if ($showTime):?>class="link-schedule"<?endif;?> >
																	<?=GetMessage('RECORD_TEXT_'.$timeData['typeCode'])?>
														</span>
													<?endif?>
												</div>
												<?if ($showTime):?>
													<div class="timeBottom" data-original-title="" title=""><?=$timeData['timeEnd']?></div>
												<?endif;?>
											</div>
										<?endif;?>
										<?if ($dayData['OLD']):?>
									</div>
								<?endif;?>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				<?endforeach;?>
			</div>
			<?if ($collapsed):?>
				<div class="schedule-expand-block">
					<span class="btn show-collapsed-days" title="<?=GetMessage('RECORD_SHOW_HIDDEN_TALONS')?>" data-expanded-text="<?=GetMessage('RECORD_HIDE_TALONS')?>" data-collapsed-text="<?=GetMessage('RECORD_SHOW_TALONS')?>" data-expand-id="ww<?=$employee['ID']?>"><?=GetMessage('RECORD_SHOW_TALONS')?></span>
				</div>
			<?endif;?>
		</div>
</div>
		<? endforeach ?>

	<script>
		var employeeSearch = <?=CUtil::PhpToJsObject($arSearch)?>;
		var employeeSearchText='';
		jQuery(document).ready(function($) {
			// auto-initialize plugin
			$('.hour-relax').each(function () {
				var parent = $(this).closest('.schedule-week');
				$(this).css('min-height',parent[0].offsetHeight);
			});
		});
	</script>
<?endif;?>
</div>
