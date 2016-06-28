<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
	$arCompanies = $arParams['AVIABLE_COMPANIES'];
	$companyID = empty($arParams['COMPANY'])?intval(current($arCompanies)):intval($arParams['COMPANY']['ID']);
	if (!empty($arParams['SCHEDULE_LINK'])){
		$baseUrl = substr($arParams['SCHEDULE_LINK'],0,strpos($arParams['SCHEDULE_LINK'],'?')).'?STEP=registration&COMPANY='.$companyID.'&SERVICE='.intval($arParams['SERVICE']);
	} else {
		$baseUrl = $APPLICATION->GetCurPageParam('STEP=registration&COMPANY='.$companyID.'&SERVICE='.intval($arParams['SERVICE']),array('DATE','START','TYPE','STEP','END','EMPLOYEE','SERVICE'));
	}
	$arSearch = array();
?>
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

			<div class="ta-center">
				<a href="{% this.link %}" class="btn btn-big"><?=GetMessage('RECORD_TO')?></a>
			</div>
		</div>
	</div>

</template>
<div class="white-box col-margin-top">
	<div class="schedule">
		<div class="schedule-header">
			<?if ($arResult['WEEK_START_YMD']!=MCDateTimeTools::AddDaysToYMDs($arResult['NOW_WEEK_YMD'],(7*$arParams['WEEK_LEAF']),false,'Y-m-d')):?>
				<div class="fl-r">
					<a id="NextWeek" href="<?=$APPLICATION->GetCurPageParam('WEEK_START='.MCDateTimeTools::AddDays($arResult['WEEK_START'],7),array('WEEK_START'))?>"><?=GetMessage('MC_RECORD_SHOW_NEXT_WEEK')?></a>
				</div>
			<?endif;?>
			<?if ($arResult['WEEK_START_YMD']!=$arResult['NOW_WEEK_YMD']):?>
				<div class="fl-r text-primary mr20">
					<a  id="NOW_WEEK_LINK" href="<?=$APPLICATION->GetCurPageParam('WEEK_START='.MCDateTimeTools::GetWeekStartByDate('d.m.Y', date('d.m.Y')),array('WEEK_START'))?>"><i class="icon icon-dot mr10"></i><?=GetMessage('MC_RECORD_NOW_WEEK')?></a>
				</div>
			<?endif;?>
			<?if (!$arResult['isNowWeek']):?>
				<div class="fl-l">
					<a id="PrevWeek" href="<?=$APPLICATION->GetCurPageParam('WEEK_START='.MCDateTimeTools::AddDays($arResult['WEEK_START'],-7),array('WEEK_START'))?>"><?=GetMessage('MC_RECORD_SHOW_PREV_WEEK')?></a>
				</div>
			<?endif;?>
			<div class="ta-center">
				<?if ($arResult['WEEK_ARRAY']['WEEK_START_YEAR']==$arResult['WEEK_ARRAY']['WEEK_END_YEAR']):?>
					<?=$arResult['WEEK_ARRAY']['WEEK_START_DAY']?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$arResult['WEEK_ARRAY']['WEEK_START_MONTH'])?>
					—
					<?=$arResult['WEEK_ARRAY']['WEEK_END_DAY']?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$arResult['WEEK_ARRAY']['WEEK_END_MONTH'])?>
					<?=$arResult['WEEK_ARRAY']['WEEK_START_YEAR']?>
				<?else:?>
					<?=$arResult['WEEK_ARRAY']['WEEK_START_DAY']?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$arResult['WEEK_ARRAY']['WEEK_START_MONTH'])?>
					<?=$arResult['WEEK_ARRAY']['WEEK_START_YEAR']?>
					—
					<?=$arResult['WEEK_ARRAY']['WEEK_END_DAY']?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$arResult['WEEK_ARRAY']['WEEK_END_MONTH'])?>
					<?=$arResult['WEEK_ARRAY']['WEEK_END_YEAR']?>
				<?endif?>
			</div>
		</div> <!-- .schedule-header -->
		<div class="schedule-week-header">
			<?for($day = 1; $day<=7; $day++):?>
				<div class="schedule-day-header <?=$arResult['NOW_WEEK_YMD']==$arResult['WEEK_START_YMD']&&$day==$arResult['nowDayNumber']?'today':''?>">
					<?
					$DATE = MCDateTimeTools::AddDaysToYMDs($arResult['WEEK_START_YMD'],($day-1));
					$ts = strtotime($DATE);
					$dayNumber = date('j',$ts);
					$month = date('n',$ts);
					?>
					<?=GetMessage('MC_RECORD_DAY_'.$day)?>,	<?=$dayNumber?>
				</div>
			<?endfor;?>
		</div>
		<div class="change-service clearfix">
			<span class="steps-breadcrumb-name"><?=GetMessage('MC_RECORD_COMPANY')?></span>
			<?global $arOrgFilter;
			$arOrgFilter = array(
				'ID' => $arCompanies,
				'PROPERTY_EL_VALUE' => 'Y',
			);
			?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"change_company_list",
				Array(
					"IBLOCK_TYPE"                     => $arParams['COMPANY_IB_TYPE'],
					"IBLOCK_ID"                       => $arParams['COMPANY_IB_ID'],
					"NEWS_COUNT"                      => 2000,
					"SORT_BY1"                        => "NAME",
					"SORT_ORDER1"                     => "ASC",
					"SORT_BY2"                        => "ID",
					"SORT_ORDER2"                     => "DESC",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"FILTER_NAME"                     => "arOrgFilter",
					"SECTION_FIELDS"                  => array(0 => "NAME"),
					'CURRENT_COMPANY' => $companyID,
					'COUNT_ELEMENTS' => 'N',
					'PAGER_SHOW_ALL' => 'N',
					"DISPLAY_TOP_PAGER"	=>	'N',
					"DISPLAY_BOTTOM_PAGER"	=>	'N',
					"PAGER_SHOW_ALWAYS"	=>	'N',
					"FIELD_CODE"	=>	array('NAME'),
					'RECORD_WITHOUT_SERVICE' => GetMessage('RECORD_WITHOUT_SERVICE'),
					'DETAIL_URL' => $APPLICATION->GetCurPageParam('COMPANY=#ELEMENT_ID#',array('COMPANY')),
				),
				$component
			);?>
		</div>
		<div class="change-service clearfix">
			<span class="steps-breadcrumb-name"><?=GetMessage('MC_RECORD_SERVICE')?></span>
			<?global $arServicesFilter;
				$arServicesFilter = array(
					'ID' => $arParams['SERVICE_LIST'],
					'PROPERTY_not_for_record' => false,
				);
				?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"change_services_list_s",
					Array(
						"IBLOCK_TYPE"                     => 'medservices',
						"IBLOCK_ID"                       => $arParams['IBLOCK_ID'],
						"NEWS_COUNT"                      => 2000,
						"SORT_BY1"                        => "NAME",
						"SORT_ORDER1"                     => "ASC",
						"SORT_BY2"                        => "ID",
						"SORT_ORDER2"                     => "DESC",
						"SET_TITLE" => "N",
						"SET_STATUS_404" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"FILTER_NAME"                     => "arServicesFilter",
						"SECTION_FIELDS"                  => array(0 => "NAME"),
						'CURRENT_SERVICE' => $arParams['SERVICE'],
						'COUNT_ELEMENTS' => 'N',
						'PAGER_SHOW_ALL' => 'N',
						"DISPLAY_TOP_PAGER"	=>	'N',
						"DISPLAY_BOTTOM_PAGER"	=>	'N',
						"PAGER_SHOW_ALWAYS"	=>	'N',
						"FIELD_CODE"	=>	array('NAME'),
						'RECORD_WITHOUT_SERVICE' => GetMessage('RECORD_WITHOUT_SERVICE'),
						'DETAIL_URL' => $APPLICATION->GetCurPageParam('SERVICE=#ELEMENT_ID#',array('SERVICE')),
					),
					$component
				);?>
		</div>
<?if (!isset($arResult['EMPLOYES']) || empty($arResult['EMPLOYES'])):?>
	<div style="padding: 15px 0 10px 0;">
		<?ShowError(GetMessage('NO_SCHEDULE_FOUND'));?>
	</div>
<?else:?>
	<? foreach ($arResult['EMPLOYES'] as $employee): ?>
		<?$arSearch[$employee['ID']] = array(
			'id'=>$employee['ID'],
			'name' => strtoupper($employee['LAST_NAME'].' '.$employee['NAME'].' '.$employee['SECOND_NAME']),
		)?>
		<?if (empty($employee['WEEKS'][$arResult['WEEK_START_YMD']]['SCHEDULE'])) continue;?>
		<?$collapsed = false;?>
		<?$baseEmpUrl = $baseUrl.'&EMPLOYEE='.$employee['ID'];?>

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
													$hasReserved = true;
													$description = GetMessage('RECORD_NOT_FREE');
												}
												else{
													$url = ''.$baseEmpDateUrl.'&START='.$startTime.'&END='.$timeData['duration'].'&TTYPE='.$timeData['idType'];
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
													<a  <?=empty($timeData['placement'])?'':'data-cabinet="'.$timeData['placement']['NAME'].'"'?>
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
													<a  <?=empty($timeData['placement'])?'':'data-cabinet="'.$timeData['placement']['NAME'].'"'?>
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
	<? endforeach ?>
<?endif;?>
	</div>
</div>