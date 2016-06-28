<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();


$arUser = $arParams['~USER'];
$name = CUser::FormatName($arParams['NAME_TEMPLATE'], $arUser, $arResult["bUseLogin"]);

$arUserData = array();
foreach ($arParams['USER_PROPERTY'] as $key) {
    if ($arUser[$key]) {
        $arUserData[$key] = $arUser[$key];
    }
}
?>
<?
	if ($arUser['PERSONAL_PHOTO']) {
		$arFile = CFile::ResizeImageGet(
			$arUser['PERSONAL_PHOTO'],
			array("width" => 150, "height" => 150),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			false
		);
		if (!empty($arFile)) {
			$img = $arFile['src'];
		}
		else {
			$wid = '71px';
			$img = '/bitrix/components/medsite/medsite.system.person/templates/.default/images/nopic_user_100_noborder.gif';
			$marg = '120px';
		}
	}
	else {
		$wid = '71px';
		$img = '/bitrix/components/medsite/medsite.system.person/templates/.default/images/nopic_user_100_noborder.gif';
		$marg = '120x';
	}
	?>
<span class="schedule-doctor-image" style="background-image: url('<?= $img ?>');"></span>

<div class="schedule-doctor-content">
	<div class="schedule-doctor-name"><?=$arUser['LAST_NAME']?> <?=$arUser['NAME']?> <?=$arUser['SECOND_NAME']?></div>
	<div class="doctor-item-description"><?= $arUserData['WORK_POSITION'] ?></div>
	<?if (!empty($arParams['USER_INFO_LINK'])):?>
		<a class="border-link mt10" href="<?=$arParams['USER_INFO_LINK']?>"><?=GetMessage('EMPLOYEE_PERSONAL_LINK')?></a>
	<?endif;?>

	<div class="schedule-doctor-schedule">
		<?if (!empty($arParams['WORK_TIME'])):?>
			<?foreach ($arParams['WORK_TIME']['DAYS'] as $day=>$data):?>
			<div class="day-wrapper">
				<span class="day"><?=GetMessage('EMP_TAB_DAY_'.$day)?>, <?=date('d',MCDateTimeTools::AddDays($arParams['WEEK_START'],$day-1,true))?></span>
				<?=substr($arParams['WORK_TIME']['DAYS'][$day]['START_TIME'],0,5)?>-<?=substr($arParams['WORK_TIME']['DAYS'][$day]['END_TIME'],0,5)?>
			</div>
			<?endforeach?>
		<?endif;?>
	</div>
</div> <!-- .schedule-doctor-content -->