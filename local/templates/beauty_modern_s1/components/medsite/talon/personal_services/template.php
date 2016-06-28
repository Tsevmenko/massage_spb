<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>


<? $month = array
    (
        '1'  => GetMessage('MONTH_1'),
        '2'  => GetMessage('MONTH_2'),
        '3'  => GetMessage('MONTH_3'),
        '4'  => GetMessage('MONTH_4'),
        '5'  => GetMessage('MONTH_5'),
        '6'  => GetMessage('MONTH_6'),
        '7'  => GetMessage('MONTH_7'),
        '8'  => GetMessage('MONTH_8'),
        '9'  => GetMessage('MONTH_9'),
        '10' => GetMessage('MONTH_10'),
        '11' => GetMessage('MONTH_11'),
        '12' => GetMessage('MONTH_12')
    );
	?>
<?$arDates = array();?>
<?if (empty($arResult['talons'])):?>
<div class="news-item">
	<?=GetMessage('NOT_FOUND')?>
</div>
<?endif;?>

<?foreach ($arResult['talons'] as $key => $talon):?>
	<?if (in_array($talon['ID'], $arResult['MY_LAST_SERV'])):?>
		<?$talon['TIME_START'] = substr($talon['TIME_START'],0,5)?>
		<?$arDate=explode("-",$talon['DATE']);?>
		
		<div class="news-item arr arr-right" data-target-self="<?=str_replace('#SECTION_ID#',intval($talon['SERVICE']['IBLOCK_SECTION_ID']),str_replace('#ELEMENT_ID#',$talon['SERVICE']['ID'],$arParams['DETAIL_URL_SERVICES']))?>">
			<?if (!in_array($talon['DATE'],$arDates)):
				$arDates[] = $talon['DATE'];
			?>
				<h3><?=$arDate[2]?> <?=$month[intval($arDate[1])]?></h3>
			<?endif?>
				<span class="time"><?=$talon['TIME_START']?></span> - <a href="<?=SITE_DIR?>employees/personal_info.php?employee=<?=$talon['EMPLOYEE']['ID']?>"><?=$talon['EMPLOYEE']['LAST_NAME']?> <?=substr($talon['EMPLOYEE']['NAME'],0,1)?>.<?=substr($talon['EMPLOYEE']['SECOND_NAME'],0,1)?></a>, <?=$talon['SERVICE']['NAME']?>
		</div>
	<?endif;?>
		
<?endforeach;?>
   
   
   
	
