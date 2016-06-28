<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/sample.css');
$APPLICATION->SetPageProperty('hideWrapper', true);
?>
<?if ($_GET['print'] != "Y") {
	?>
	<?
		$filterClassName = ($_COOKIE['filter-expanded']) ? '' : 'filter-collapsed';
	?>
	<div class="mt20 filter-block <?=$filterClassName?>">
		<div class="filter-header">
			<div class="btn-link filter-header-left filter-trigger"><?=GetMessage('TALON_FILTER_FILTER')?></div>
			<div class="btn-link filter-header-right filter-trigger"><?=GetMessage('TALON_FILTER_CLOSE')?></div>
		</div>
		<form name="talon_search" class="col white-box mb20">
			<input type="hidden" name="FilterUsed" value="Y">
			<table class="form-table search_form simple">
				<tr>
					<td>
						<? echo GetMessage("FIND_TALON").': ' ?>
					</td>
					<td>
						<input class="input" type="text" style="width:350px" name="talon" value="<?= $arParams['TALON_ID'] ?>">
					</td>
				</tr>
				<tr>
					<td>
						<? echo GetMessage("TALON_PEOFILE").' ' ?>
					</td>
					<td>
						<input class="input" type="text" style="width:350px" name="user_profile" value="<?= $arParams['USER_PROFILE'] ?>">
					</td>
				</tr>
				<?if ($arParams['CAN_SEE_FOREIGN_STAMPS'] === true) {
					?>
					<tr>
						<td>
							<? echo GetMessage("TALON_EMPLOYEE").' ' ?>
						</td>
						<td>
							<input class="input" type="text" style="width:350px" name="employee" value="<?= $arParams['EMPLOYEE'] ?>">
						</td>
					</tr>
				<? } ?>
				<tr>
					<td>
						<? echo GetMessage("TALON_DATE").' ' ?>
					</td>
					<td>
						<input class="input" type="text" style="width:150px" name="dat_start" value="<?= $arParams['DAT_START'] ?>">
						<?
						$APPLICATION->IncludeComponent(
							'bitrix:main.calendar',
							'',
							array(
								'FORM_NAME' => 'talon_search',
								'INPUT_NAME' => "dat_start",
								'INPUT_VALUE' => $arParams['DAT_START'],
							),
							null,
							array('HIDE_ICONS' => 'Y')
						);
						?>
						...
						<input class="input" type="text" style="width:150px" name="dat_end" value="<?= $arParams['DAT_END'] ?>">
						<?
						$APPLICATION->IncludeComponent(
							'bitrix:main.calendar',
							'',
							array(
								'FORM_NAME' => 'talon_search',
								'INPUT_NAME' => "dat_end",
								'INPUT_VALUE' => $arParams['DAT_END'],
							),
							null,
							array('HIDE_ICONS' => 'Y')
						);
						?>
					</td>
				</tr>
				<tr>
					<td>
						<? echo GetMessage("TALON_TIME").' ' ?>
					</td>
					<td>
						<?$APPLICATION->IncludeComponent("medsite:clock", "", Array(
																				"INPUT_ID"   => "t_start",
																				"INPUT_NAME" => "t_start",
																				"INPUT_CLASS" => "input",
																				"BUTTON_CLASS" => "btn btn-small",
																				"INIT_TIME"  => $arParams['T_START'],
																				"zIndex"     => '2000',
																				"STEP"       => "0",
																			)
						);
						?>
						...
						<?$APPLICATION->IncludeComponent("medsite:clock", "", Array(
																				"INPUT_ID"   => "t_end",
																				"INPUT_NAME" => "t_end",
																				"INIT_TIME"  => $arParams['T_END'],
																				"INPUT_CLASS" => "input",
																				"BUTTON_CLASS" => "btn btn-small",
																				"zIndex"     => '2000',
																				"STEP"       => "0",
																			)
						);
						?>
					</td>
				</tr>
				<tr>
					<td>
						<? echo GetMessage("TALON_STATUS").' ' ?>
					</td>
					<td>
						<select class="styler" id="talon_state" name="talon_state" style="width:100%">
							<option value="<?= -1 ?>"><?=GetMessage('ALL_TALON_STATES')?></option>
							<option <? if ($arParams['TALON_STATE'] == mc_talon_state_created)
								echo 'selected' ?>
								value="<?= mc_talon_state_created ?>"><?= MCTalon::GetStateName(mc_talon_state_created) ?></option>
							<option <? if ($arParams['TALON_STATE'] == mc_talon_state_accepted)
								echo 'selected' ?>
								value="<?= mc_talon_state_accepted ?>"><?= MCTalon::GetStateName(mc_talon_state_accepted) ?></option>
							<option <? if ($arParams['TALON_STATE'] == mc_talon_state_cenceled)
								echo 'selected' ?>
								value="<?= mc_talon_state_cenceled ?>"><?= MCTalon::GetStateName(mc_talon_state_cenceled) ?></option>
							<option <? if ($arParams['TALON_STATE'] == mc_talon_state_denied)
								echo 'selected' ?>
								value="<?= mc_talon_state_denied ?>"><?= MCTalon::GetStateName(mc_talon_state_denied) ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td>
						<? echo GetMessage("TALON_SERV").': ' ?>
					</td>
					<td>
						<select class="styler" id="talon_serv" name="talon_serv" style="width:100%">
							<option value="<?= -1 ?>"><?=GetMessage('ALL_TALON_STATES')?></option>
							<?
							foreach ($arResult['FILTER_SERVICES'] as $key => $value) {
								?>
								<option <? if ($value['ID'] == $arParams['TALON_SERV'])
									echo 'selected' ?>
									value="<?= $value['ID'] ?>"><?= $value['NAME'] ?></option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>&nbsp;</td>
					<td class="buttons">
						<input class="btn" type="submit" value="<?= GetMessage('FIND_TALON_SUBMIT') ?>">
						<input class="btn" type="submit" value="<?= GetMessage('TALON_FILTER_CLEAR') ?>" name="del_filter">
					</td>
				</tr>
				</tfoot>
			</table>
		</form>
	</div> <!-- .filter-block <?=$filterClassName?> -->
<? } ?>
<? if ($arParams['DISPLAY_TOP_PAGER'])
	echo $arResult['NavString']; ?>
<table class="simple talon w100p">
	<?
	foreach ($arResult['talons'] as $key => $talon) {
		?>
		<? if (!$i & 1): ?>    <tr><? endif ?>
		<td>

			<div id="<?= $talon['ID'] ?>" class="talon_main white-box">
				<table class="talon_number simple">
					<tr class="talon_row">
						<td>
							<? echo GetMessage("TALON_NUMBER") ?>&nbsp;<b><? echo $talon['ID'] ?></b>
						</td>
						<td>
							<span class="talon_status <?= $talon['STATE_CLASS'] ?>"><? echo $talon['STATE_TEXT'] ?></span>
							<?if (!empty($talon['PRICE']) && $talon['PRICE']>0): ?>

								<? if ($talon['PAYED']!=='Y'): ?>
									<span class="talon_status talon_state_red"><?=GetMessage("TALON_NOT_PAYED")?></span>
								<?else:?>
									<span class="talon_status talon_state_green"><?=GetMessage("TALON_ALREADY_PAYED")?></span>
								<? endif; ?>
							<?endif; ?>
						</td>
					</tr>
				</table>

				<?
				if (in_array('ORGANIZATION', $arParams['SHOW_PROPERTIES']) && !empty($arTalon['ORGANIZATION'])) {
					?>
					<table class="talon_param simple">
						<tr class="talon_row">
							<td style="">
								<b><? echo $talon['ORGANIZATION']['NAME'] ?><b>
							</td>
						</tr>
						<tr class="talon_row">
							<td style="">
								<?if (array_key_exists('NAME',$talon['PLACE']['SECTOR'])):?>
									<?=$talon['PLACE']['SECTOR']['NAME']?>
								<? elseif (!empty($talon['DEPARTAMENT']['UF_ADDRESS'])): ?>
									<?=$talon['DEPARTAMENT']['UF_ADDRESS'] ?>
								<? else: ?>
									<?=$talon['ORGANIZATION']['PROPERTY_ADRESS_VALUE'] ?>
									<? if (!empty($talon['ORGANIZATION']['PROPERTY_PHONE_VALUE']))
										echo $talon['ORGANIZATION']['PROPERTY_PHONE_VALUE'] ?>
								<?endif; ?>
							</td>
						</tr>
					</table>
				<?
				}
				?>
				<table class="talon_param simple">
					<tr class="talon_row">
						<td style="">
							<b><? echo GetMessage("TALON_PACIENT") ?></b>
						</td>
						<td class="param_value">
							&nbsp;<? echo $talon['PROFILE_NAME'] ?>
						</td>
					</tr>
				</table>
				<table class="talon_param simple">
					<tr class="talon_row">
						<td style="">
							<b><? echo GetMessage("TALON_DATE") ?></b>
						</td>
						<td class="param_value">
							&nbsp;
							<?
							$dt = strtotime($talon['DATE']);
							echo date('d.m.Y', $dt);
							?>
						</td>
					</tr>
				</table>
				<table class="talon_param simple">
					<tr class="talon_row">
						<td style="">
							<b><? echo GetMessage("TALON_TIME") ?></b>
						</td>
						<td class="param_value">
							&nbsp;
							<?
							$dt = strtotime($talon['TIME_START']);
							$dte = strtotime($talon['TIME_END']);
							echo date('H:i', $dt).' - '.date('H:i', $dte);
							?>
						</td>
					</tr>
				</table>

				<?
				if (in_array('SERVICE', $arParams['SHOW_PROPERTIES'])) {
					?>
					<table class="talon_param simple">
						<tr class="talon_row">
							<td style="">
								<b><?=GetMessage("TALON_SERVICE") ?></b>
							</td>
							<td class="param_value">
								&nbsp;<?=empty($talon['SERVICE']['NAME'])?GetMessage("TALON_NO_SERVICE"):$talon['SERVICE']['NAME'] ?>
							</td>
						</tr>
					</table>
				<?
				}
				?>

				<?
				if (in_array('EMPLOYEE', $arParams['SHOW_PROPERTIES'])) {
					?>
					<table class="talon_param simple">
						<tr class="talon_row">
							<td style="">
								<b><? echo GetMessage("TALON_EMPLOYEE") ?></b>
								&nbsp;<? echo $talon['EMPLOYEE']['LAST_NAME'].' '.$talon['EMPLOYEE']['NAME'].' '.$talon['EMPLOYEE']['SECOND_NAME'].' - '.$talon['EMPLOYEE']['WORK_POSITION'] ?>
							</td>
						</tr>
					</table>
				<?
				}
				?>

				<?
				if (in_array('PLACE', $arParams['SHOW_PROPERTIES'])) {
					?>

					<table class="talon_param simple" style="">
						<tr class="talon_row">
							<td style="">
								<b><? echo GetMessage("TALON_CABINET") ?></b>
								&nbsp;<? echo trim($talon['PLACE']['PLACEMENT']['NAME']) ?>
							</td>
						</tr>
					</table>

				<?
				}
				?>

				<?
				foreach ($talon['USER_INFO']['PROPERTIES'] as $pkey => $pvalue) {
					if (in_array($pkey, $arParams['SHOW_PROPERTIES']) && !empty($pvalue['VALUE'])) {
						?>
						<table class="talon_param simple" style="">
							<tr class="talon_row">
								<td style="">
									<b><? echo $pvalue['NAME'] ?></b>
									&nbsp;<? echo $pvalue['VALUE'] ?>
								</td>
							</tr>
						</table>
					<?
					}
				}
				?>
				<table class="talon_param simple talon-bottom">
					<tr>
						<td>
							<?if (!empty($talon['PRICE'])):?>
								<b class="talon-price">
									<?if ($talon['PRICE']>0):?>
										<?if (!empty($talon['CURRENCY']) && $talon['CURRENCY']!="RUB"):?>
											<?=$talon['PRINT_INT_PRICE']?>
										<?else:?>
											<span class="service-price"><?=intval($talon['PRICE'])?>
												<span class="rub"><?=GetMessage('PRICE_RUB')?></span>
											</span>
										<?endif;?>
									<?else:?>
										<?=GetMessage('PRICE_EMPTY')?>
									<?endif;?>
								</b>
							<?endif?>
						</td>
						<td>
							<div class="talon_buttons">
								<?if (defined('MC_TALON_REWRITE') && !empty($arParams['RECORD_WIZARD_URL'])):?>
									<a class="btn btn-gray btn-noborder" title="<? echo GetMessage("TALON_REWRITE") ?>"
									   href="<?=$arParams['RECORD_WIZARD_URL']?>?service=<?=$talon['SERVICE']['ID']?>&employee=<?=$talon['EMPLOYEE']['ID']?>&re=<?=$talon['ID']?>"><? echo GetMessage("TALON_REWRITE") ?></a>
								<?endif;?>
								<? if (($talon['STATE'] == mc_talon_state_created) or ($talon['STATE'] == mc_talon_state_accepted)) { ?>
									<?
									$urlCancel = urldecode($APPLICATION->GetCurPageParam('canceled='.$talon['ID'], array('canceled')));
									$urlConfirm = urldecode($APPLICATION->GetCurPageParam('confirmed='.$talon['ID'], array('confirmed')));
									unset($urlReturn);
									?>
									<a class="btn btn-gray btn-noborder"  title="<? echo GetMessage("TALON_CANCLE") ?>"
									   href="javascript:goAjax('<?= $talon["ID"] ?>','<?= $urlCancel ?>')"><? echo GetMessage("TALON_CANCLE") ?></a>
								<?
								}
								elseif ($talon['STATE'] == mc_talon_state_denied) {
									$urlReturn = urldecode($APPLICATION->GetCurPageParam('return='.$talon['ID'], array('return')));
									unset($urlCancel);
									unset($urlConfirm);
								}
								?>
								
								
								<?if (isset($urlReturn)):?>
									<a href="<?=$urlReturn?>" class="btn"><?=GetMessage('TALON_RETURN')?></a>
								<?elseif (isset($urlConfirm) && $talon['STATE'] != mc_talon_state_accepted):?>
									<a href="<?=$urlConfirm?>" class="btn"><?=GetMessage('TALON_CONFIRM')?></a>
								<?endif?>
							</div>
						</td>
					</tr>
				</table>

			</div>
		</td>
		<? if ($i & 1): ?></tr><? endif ?>
		<? $i++ ?>
	<?
	}
	?>
</table>



<? if ($arParams['DISPLAY_BOTTOM_PAGER'])
	echo $arResult['NavString']; ?>
<div id="return_error" class="<? echo $arParams['ERROR_CLASS'] ?>">
	<? echo GetMessage("TALON_RETURN_ERROR") ?><br><br>

	<div style="text-align:center;">
		<input type="button" value="<? echo GetMessage("BUTTON_CAPTION_OK") ?>" onClick="javascript:close_error()">
	</div>
</div>
<div id="t_check_div">
	<input id="talon_check" type="hidden" value="0">
</div>