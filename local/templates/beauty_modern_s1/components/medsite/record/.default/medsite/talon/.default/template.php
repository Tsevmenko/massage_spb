<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$talon = array_shift($arResult['talons']);
?>
<div class="content">
	<div class="col col-12 print-block">
		<div class="h3">Ваш талон №<?=$talon['ID']?></div>
	</div>
	<div class="col col-7 print-block">
		<div class="white-box record-tikket">

			<div class="content">
				<div class="col col-6">
					<div class="record-tikket-header">
						Дата приёма
					</div>
					<?
					$ts = strtotime($talon['DATE']);
					$day = date('j',$ts);
					$month = date('n',$ts);
					$year = date('Y',$ts);
					$dayString = date('N',$ts);
					$dt = strtotime($talon['TIME_START']);
					$dte = strtotime($talon['TIME_END']);
					?>
					<div class="record-tikket-text fz20">
						<?=$day?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$month)?> <?=$year?>
					</div>
				</div>

				<div class="col col-6">
					<div class="record-tikket-header">
						Время приёма
					</div>
					<div class="record-tikket-text fz20">
						<?=date('H:i', $dt).' - '.date('H:i', $dte)?>
					</div>
				</div>
			</div>
			<hr>

			<div class="record-tikket-header">
				Услуга
			</div>
			<div class="record-tikket-text">
				<?=$talon['SERVICE']['NAME']?>
			</div>

			<hr>

			<div class="record-tikket-header">
				Врач
			</div>
			<div class="record-tikket-text">
				<?
				$NAME = $talon['EMPLOYEE']['LAST_NAME'];
				if (!empty($talon['EMPLOYEE']['NAME']))
					$NAME .= ' '.$talon['EMPLOYEE']['NAME'];
				if (!empty($talon['EMPLOYEE']['SECOND_NAME']))
					$NAME .= ' '.$talon['EMPLOYEE']['SECOND_NAME'];
				?>
				<?=$NAME?>
			</div>

			<hr>

			<?if (in_array('PLACE', $arParams['SHOW_PROPERTIES'])):?>
				<? if (!empty($talon['PLACE']['PLACEMENT']['NAME'])): ?>
					<div class="record-tikket-header">
						<?=GetMessage("TALON_CABINET") ?>
					</div>
					<div class="record-tikket-text">
						<? echo trim($talon['PLACE']['PLACEMENT']['NAME']) ?>
					</div>
					<hr>
				<? endif; ?>
			<?endif;?>

			<div class="record-tikket-header">
				Больница
			</div>
			<div class="record-tikket-text">
				<?=$talon['ORGANIZATION']['NAME'] ?>
			</div>

			<hr>

			<div class="record-tikket-header">
				Адрес
			</div>
			<div class="record-tikket-text">
				<?if (array_key_exists('NAME',$talon['PLACE']['SECTOR'])):?>
					<?=$talon['PLACE']['SECTOR']['NAME']?>
				<? elseif (!empty($talon['DEPARTAMENT']['UF_ADDRESS'])): ?>
					<?=$talon['DEPARTAMENT']['UF_ADDRESS'] ?>
				<? else: ?>
					<?=$talon['ORGANIZATION']['PROPERTY_ADRESS_VALUE'] ?>
					<? if (!empty($talon['ORGANIZATION']['PROPERTY_PHONE_VALUE']))
						echo GetMessage('ORGANIZATION_PHONE'), $talon['ORGANIZATION']['PROPERTY_PHONE_VALUE'] ?>
				<?endif; ?>
			</div>

			<hr>

			<?if (!empty($talon['PRICE'])):?>
				<div class="record-tikket-text clearfix">
					<div class="fl-l text-light">
						<?=GetMessage("TALON_PRICE") ?>
					</div>
					<div class="fl-r">
						<b>
							<? if ($talon['PRICE']>0): ?>
								<?if (!empty($talon['CURRENCY']) && $talon['CURRENCY']!="RUB"):?>
									<?=$talon['PRINT_INT_PRICE']?>
								<?else:?>
									<span class="service-price"><?=intval($talon['PRICE'])?>
										<span class="rub"><?=GetMessage('PRICE_RUB')?></span>
										</span>
								<?endif;?>

							<? else: ?>
								<?=GetMessage('PRICE_EMPTY')?>
							<? endif; ?>
						</b>
					</div>
				</div> <!-- .record-tikket-price mb0 clearfix -->

				<hr>
			<?endif?>

			<div class="ta-center print-hide mt20">
				<? if (!empty($talon['PRICE']) && $talon['PRICE']>0): ?>
					<? if ($talon['PAYED']!=='Y'): ?>
						<? if ($talon['STATE']==mc_talon_state_accepted): ?>
							<a target="_blank" href="<?=SITE_DIR.'personal/cart/'?>" class="btn btn-big"><?=GetMessage('RECORD_PAY_FOR_TALON')?></a>
						<?else:?>
							<?=GetMessage('RECORD_FINAL_COMMENT_NO_ACCEPTED')?>
						<?endif?>
					<?else:?>
						<a target="_blank" href="<?=SITE_DIR.'personal/stamps.php?talon='.$talon['ID'].'&print=Y'?>" class="btn btn-big"><?=GetMessage('PRINT_TALON')?></a>
						<?=GetMessage('RECORD_FINAL_COMMENT')?>
					<? endif; ?>
				<? else: ?>
					<a target="_blank" href="<?=SITE_DIR.'personal/stamps.php?talon='.$talon['ID'].'&print=Y'?>" class="btn btn-big"><?=GetMessage('PRINT_TALON')?></a>
					<?=GetMessage('RECORD_FINAL_COMMENT')?>
				<?endif;?>
			</div>

		</div> <!-- .white-box record-tikket -->
	</div> <!-- .col col-7 -->
	<div class="col col-5 print-hide">
		<div class="white-box p30">
			<p class="mt0"><?=GetMessage('TALON_PROFILE')?> <b><?=$talon['PROFILE_NAME']?></b></p>
			<p><?=GetMessage('TALON_NUMBER')?> <b><?=$talon['ID']?></b></p>
			<p><?=GetMessage('TALON_STATUS')?> <span class="btn btn-square btn-small btn-outline disabled <?=$talon['STATE_CLASS']?>"><?=$talon['STATE_TEXT']?></span></p>
			<? if ($talon['STATE'] !== mc_talon_state_created && $talon['STATE'] !== mc_talon_state_accepted && !empty($talon['COMMENT'])): ?>
				<p><span style="font-size:10pt;"><? echo $talon['COMMENT'] ?></span></p>
			<? endif; ?>
			<?foreach ($talon['USER_INFO']['PROPERTIES'] as $pkey => $pvalue):
				if (in_array($pkey, $arParams['SHOW_PROPERTIES']) && !empty($pvalue['VALUE'])):?>
					<p><? echo $pvalue['NAME'] ?>&nbsp;<b><? echo $pvalue['VALUE'] ?></b></p>
				<?endif;
			endforeach;?>
			<p class="mb0"><?=GetMessage('RECORD_FINAL_SEE_TALON_AT')?> </p>
			<p class="mb0"><a class="border-link" href="<?=$APPLICATION->GetCurPage()?>"><?=GetMessage('RECORD_FINAL_NEW_RECORD')?></a></p>
		</div>

	</div> <!-- .col col-5 -->
</div>