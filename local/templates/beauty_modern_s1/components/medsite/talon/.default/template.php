<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/sample.css');
$APPLICATION->SetPageProperty('hideWrapper', true);
?>
<?if ($_GET['print'] != "Y" && $arParams['SHOW_SEARCH'] == 'Y') {
	?>
	<?
		$filterClassName = ($_COOKIE['filter-expanded']) ? '' : 'filter-collapsed';
	?>
	<div class="mt20 white-box p20 filter-block <?=$filterClassName?>">
		<div class="filter-header clearfix">
			<div class="btn btn-big filter-trigger"><?=GetMessage('TALON_FILTER_FILTER')?></div>
			<div class="btn btn-outline filter-header-right filter-trigger"><?=GetMessage('TALON_FILTER_CLOSE')?></div>
		</div>
		<form name="talon_search" class="mt20">
			<table class="table">
				<tr>
					<td class="va-middle">
						<? echo GetMessage("FIND_TALON").': ' ?>
					</td>
					<td>
						<input class="input mb0" type="text" style="width:350px" name="talon" value="<?= $arParams['TALON_ID'] ?>">
					</td>
				</tr>
				<? if ($USER->IsAuthorized()) { ?>
					<?if (!empty($arResult['profiles'])):?>
					<tr>
						<td class="va-middle">
							<? echo GetMessage("TALON_PEOFILE").': ' ?>
						</td>
						<td>
							<select class="styler" id="user_profile" name="user_profile" style="width:100%">
								<? foreach ($arResult['profiles'] as $id => $profile): ?>
									<option <? if ($id == $arParams['USER_PROFILE'])
										echo 'selected' ?>
										value="<?= $id ?>"><?= $profile ?></option>
								<? endforeach; ?>
							</select>
						</td>
					</tr>
					<?endif?>
					<tr>
						<td class="va-middle">
							<? echo GetMessage("TALON_EMPLOYEE").' ' ?>
						</td>
						<td>
							<input class="input mb0" type="text" style="width:350px" name="employee" value="<?= $arParams['EMPLOYEE'] ?>">
						</td>
					</tr>
					<tr>
						<td class="va-middle">
							<? echo GetMessage("TALON_DATE").' ' ?>
						</td>
						<td>
							<input class="input mb0" type="text" style="width:120px" name="dat_start" value="<?= $arParams['DAT_START'] ?>">
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
							<input class="input mb0" type="text" style="width:120px" name="dat_end" value="<?= $arParams['DAT_END'] ?>">
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
						<td class="va-middle">
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
					<?if (!empty($arResult['FILTER_SERVICES'])):?>
					<tr>
						<td class="va-middle">
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
					<?endif?>
				<? } ?>
				<? if (!$USER->IsAuthorized()) { ?>
					<tr>
						<td colspan="2">
							<?include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
							$cpt = new CCaptcha();
							$captchaPass = COption::GetOptionString("main", "captcha_password", "");
							if (strlen($captchaPass) <= 0) {
								$captchaPass = randString(10);
								COption::SetOptionString("main", "captcha_password", $captchaPass);
							}
							$cpt->SetCodeCrypt($captchaPass);
							?>
							<input name="captcha_code" value="<?= htmlspecialcharsbx($cpt->GetCodeCrypt()); ?>" type="hidden">
							<img style="float:left; margin-right:45px;" src="/bitrix/tools/captcha.php?captcha_code=<?= htmlspecialcharsbx($cpt->GetCodeCrypt()); ?>">
							<input class="input mb0" id="captcha_word" name="captcha_word" type="text">
							<? if ($arResult['captha_error']) { ?>
								<div class="talon_state_red"><?= GetMessage('CAPTHA_ERROR') ?></div>
							<? } ?>
						</td>
					</tr>
				<? } ?>
				<tfoot>
				<tr>
					<td> &nbsp; </td>
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
<?if (empty($arResult['talons'])):?>
	<?if ($USER->IsAuthorized()):?>
		<?=str_replace('#RECORD_URL#',$arParams['RECORD_URL'],GetMessage('TALONS_NOT_FOUND'));?>
	<?endif?>
<?else:?>
	<table class="simple talon w100p">
	<?
	foreach ($arResult['talons'] as $key => $talon) {
		?>
		<? if (!$i & 1): ?>    <tr><? endif ?>
		<td>
		<div class="white-box talon_main <?=($arParams['SHOW_PRINTED'] == 'Y' || $_GET['print'] == "Y")?'print':''?>">
		<table class="talon_number simple">
			<tr class="talon_row">
				<td>
					<? echo GetMessage("TALON_NUMBER") ?>&nbsp;<b><? echo $talon['ID'] ?></b>
				</td>
				<td>
					<?if (IsModuleInstalled('sale') && !empty($talon['PRICE']) && $talon['PRICE']>0 && $talon['PAYED']==='Y'): ?>
						<span class="talon_status <?= $talon['STATE_CLASS'] ?>"><?=GetMessage("TALON_ALREADY_PAYED")?></span>
					<?else:?>
						<span class="talon_status <?= $talon['STATE_CLASS'] ?>"><?=$talon['STATE_TEXT']?></span>
					<?endif; ?>
				</td>
			</tr>
		</table>
		<? if ($talon['STATE'] !== mc_talon_state_created && $talon['STATE'] !== mc_talon_state_accepted && !empty($talon['COMMENT'])): ?>
			<div class="talon_status_comment">
				<span><? echo $talon['COMMENT'] ?></span>
			</div>
		<? endif; ?>

		<?
		if (in_array('ORGANIZATION', $arParams['SHOW_PROPERTIES']) && !empty($arTalon['ORGANIZATION'])) {
			?>
			<table class="talon_param simple">
				<tr class="talon_row">
					<td>
						<b><? echo $talon['ORGANIZATION']['NAME'] ?><b>
					</td>
				</tr>
				<tr class="talon_row">
					<td style="font-size:10pt;">
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
				<? if (!empty($talon['DEPARTAMENT']['UF_PHONE'])): ?>
					<tr class="talon_row">
						<td style="font-size:10pt;">
							<?= GetMessage('DEPARTMENT_PHONE').': '.$talon['DEPARTAMENT']['UF_PHONE'] ?>
						</td>
					</tr>
				<? endif ?>
			</table>
		<?
		}


		?>
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
						<b><? echo GetMessage("TALON_SERVICE") ?></b>
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
			<? if (!empty($talon['PLACE']['PLACEMENT']['NAME'])): ?>
				<table class="talon_param simple" style="">
					<tr class="talon_row">
						<td style="">
							<b><? echo GetMessage("TALON_CABINET") ?></b>
							&nbsp;<? echo trim($talon['PLACE']['PLACEMENT']['NAME']) ?>
						</td>
					</tr>
				</table>
			<? endif; ?>

		<?
		}
		?>

		<?
		foreach ($talon['USER_INFO']['PROPERTIES'] as $pkey => $pvalue) {
			if (in_array($pkey, $arParams['SHOW_PROPERTIES']) && !empty($pvalue['VALUE'])) {
				?>
				<table class="talon_param simple">
					<tr class="talon_row">
						<td>
							<b><? echo $pvalue['NAME'] ?></b>
							&nbsp;<? echo $pvalue['VALUE'] ?>
						</td>
					</tr>
				</table>
			<?
			}
		}
		?>
		<?if (IsModuleInstalled('sale') && !empty($talon['PRICE']) && $talon['PRICE']>0 && !empty($talon['BASKET']['ORDER_LINK'])):?>
		<table class="talon_param simple">
			<tr class="talon_row">
				<td style="">
					<a href="<?=$talon['BASKET']['ORDER_LINK']?>" class=""><?=GetMessage('RECORD_GO_TO_ORDER')?></a>
				</td>
			</tr>
		</table>
		<?endif;?>

		<table class="talon_param simple talon-bottom">
			<tr>
				<td>
					<?if (!empty($talon['PRICE'])):?>
						<b class="talon-price">
							<? if ($talon['PRICE']>0): ?>
								<?if (!empty($talon['CURRENCY']) && $talon['CURRENCY']!="RUB"):?>
									<?=$talon['PRINT_INT_PRICE']?>
								<?else:?>
									<span class="service-price"><?=intval($talon['PRICE'])?>
										<span class="rub"><?=GetMessage('PRICE_RUB')?></span>
									</span>
								<?endif;?>
								<? if (IsModuleInstalled('sale') && !empty($talon['PRICE']) && $talon['PRICE']>0): ?>
									<? if ($talon['STATE']==mc_talon_state_accepted): ?>
										<?if (!$talon['BASKET']):?>
											<a href="<?=urldecode($APPLICATION->GetCurPageParam('pay='.$talon['ID'], array('pay')))?>" class="btn btn-blue btn-small"><?=GetMessage('RECORD_PAY_FOR_TALON')?></a>
										<?elseif(empty($talon['BASKET']['ORDER_LINK'])):?>
											<a href="<?=$arParams['BASKET_URL']?>" class="btn btn-blue btn-small"><?=GetMessage('RECORD_PAY_FOR_TALON')?></a>
										<?endif;?>
									<?endif?>
								<? endif; ?>
							<? else: ?>
								<?=GetMessage('PRICE_EMPTY')?>
							<? endif; ?>
						</b>
					<?endif?>
				</td>
				<td>
					<? if ($_GET['print'] != "Y" && $arParams['SHOW_ICONS'] == 'Y'): ?>
						<div class="talon_buttons">
							<? if (($talon['STATE'] == mc_talon_state_created) or ($talon['STATE'] == mc_talon_state_accepted)) { ?>
								<a class="btn btn-gray btn-noborder" title="<? echo GetMessage("PRINT_TALON") ?>" target="blank"
								   href="<?= $_SERVER['PHP_SELF'].'?talon='.$talon['ID'].'&print=Y' ?>"><? echo GetMessage("PRINT_TALON") ?></a>
								<?
								$url = urldecode($APPLICATION->GetCurPageParam('canceled='.$talon['ID'], array('canceled')));
								if ($USER->IsAuthorized()) {
									?>
									<a class="btn btn-gray btn-noborder" title="<? echo GetMessage("TALON_CANCLE") ?>"
									   href="javascript:if(confirm('<? echo GetMessage("TALON_CANCLE_ALERT") ?>')) jsUtils.Redirect([], '<?= $url ?>');"><? echo GetMessage("TALON_CANCLE") ?></a>
								<?
								}
							}
							elseif ($talon['STATE'] == mc_talon_state_cenceled) {
								$url = urldecode($APPLICATION->GetCurPageParam('return='.$talon['ID'], array('return')));
								if ($USER->IsAuthorized()) {
									?>
									<a class="btn btn-gray btn-noborder" title="<? echo GetMessage("TALON_RETURN") ?>" href="<?= $url ?>"><? echo GetMessage("TALON_RETURN") ?></a>
								<?
								}
							} ?>
						</div>

					<? endif; ?>
				</tr>
			</td>
		</table>


		<? if ($arParams['SHOW_PRINTED'] == 'Y' || $_GET['print'] == "Y"): ?>
			<div style="font-size:10pt;">
				<? echo GetMessage("TALON_PRINT").' '.date('d.m.Y H:i') ?>
			</div>
		<? endif; ?>

		</div>
		</td>
		<? if ($i & 1): ?></tr><? endif ?>
		<? $i++ ?>
	<?
	}
	?>
	</table>
<?endif?>
<? if ($arParams['SHOW_PRINT_BUTTON'] == 'Y' && count($arResult['talons']) == 1): ?>
	<? $talon = array_shift($arResult['talons']) ?>
	<? if ($talon['STATE'] == mc_talon_state_created || $talon['STATE'] == mc_talon_state_accepted): ?>
		<div class="print_btn4">
			<?
			echo '<a target="blank" href="'.SITE_DIR.'personal/stamps.php?talon='.$talon['ID'].'&print=Y">'.GetMessage('PRINT_TALON').'</a>.';
			?>
		</div>
		<div class="clear"></div>
	<? endif ?>
<? endif ?>
	<div id="return_error" class="<? echo $arParams['ERROR_CLASS'] ?>">
		<? echo GetMessage("TALON_RETURN_ERROR") ?><br><br>

		<div style="text-align:center;">
			<input type="button" value="<? echo GetMessage("BUTTON_CAPTION_OK") ?>" onClick="javascript:close_error()">
		</div>
	</div>
<? if ($_GET['print'] == 'Y'): ?>
	<script type="text/javascript">
		print("");
	</script>
<? endif; ?>
