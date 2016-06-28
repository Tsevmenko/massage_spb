<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->SetPageProperty('hideWrapper', true);
$DIR = dirname(__FILE__);
$DIR = str_replace('\\','/',$DIR);

$inComponent = strrpos($DIR,'/bitrix/components/');
if ($inComponent===false)
	$templatePath = substr($DIR,strrpos($DIR,'/bitrix/templates/'));
else
	$templatePath = substr($DIR,$inComponent);

$templatePath = "/local/templates/beauty_modern_s1/components/medsite/record/.default";
?>
<script src="<?=$templatePath?>/js/popover.min.js"></script>
<script src="<?=$templatePath?>/js/jquery.magnific.popup.min.js"></script>
<script src="<?=$templatePath?>/js/jquery.typeahead.min.js"></script>
<script src="<?=$templatePath?>/js/jquery.sticky.min.js"></script>
<script src="<?=$templatePath?>/js/jquery.fixer.min.js"></script>
<script src="<?=$templatePath?>/js/script.js"></script>

<?
unset($arResult["WizardSteps"]["where_to_record"]);
unset($arResult["WizardSteps"]["change_service"]);
//unset($arResult["STEP_LINKS"]);
unset($arResult["STEP_LINKS_TEMPLATE"]);
$arResult["STEP_COUNT"]--;
$arResult["WizardSteps"]["service"] = 2;
$arResult["WizardSteps"]["registration"] = 3;
$arResult["WizardSteps"]["final"] = 4;
?>

<div class="content col-margin">
	<div class="col col-12">
	<div class="white-box p20 clearfix">
		<div class="col col-12 mb20">
			<h1><?=GetMessage('MCRWizardStep_'.($arResult["STEP"]+$arResult['STEP_CORRECTION']+$arResult['STEP_TWO_CORECTION']))?></h1>
		</div>
		<div class="col col-12">
			<div class="steps-select clearfix">
				<?$stepShowNumber = 0;?>
				<?if ($arResult["STEP"]==1 && !empty($arResult['FIXED'])) $arResult['STEP_CORRECTION']--;?>
				<?if($_REQUEST["STEP"] == "service") $arResult["STEP"] = 2;?>
				<?for ($stepID=1; $stepID<=3; $stepID++):?>
					<?if ($stepID==1 && !$arParams['ORG_STEP_SHOW']) continue;?>
					<?if ($stepID==2 && !empty($arResult['FIXED'])) continue;?>
					<?$stepShowNumber++;?>
					<?$class=$stepID==$arResult["STEP"]?'current':'';?>
					<div class="step-item" style="width: <?=100/(3+$arResult['STEP_CORRECTION'])?>%;">
						<?if (!empty($arResult['STEP_LINKS'][$stepID])):?>
							<a class="step-item-inner <?=$stepID-$arResult['STEP_CORRECTION']>=$arResult["STEP"]?$class:'checked'?>" href="<?=$arResult['STEP_LINKS'][$stepID]?>">
								<span class="step-item-num"><?=$stepShowNumber?></span>
								<span class="step-item-text"><?=GetMessage('MCRWizardStep_'.$stepID)?></span>
							</a>
						<?else:?>
							<span class="step-item-inner">
								<span class="step-item-num"><?=$stepShowNumber?></span>
								<span class="step-item-text"><?=GetMessage('MCRWizardStep_'.$stepID)?></span>
							</span>
						<?endif;?>
					</div>
				<?endfor;?>
			</div>
		</div>
	</div>
</div>
</div>