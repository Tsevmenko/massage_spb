<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$this->setFrameMode(true);
?>
<?
$arUser = $arParams['~USER'];
$name = CUser::FormatName($arParams['NAME_TEMPLATE'], $arUser, $arResult["bUseLogin"]);

$arUserData = array();
foreach ($arParams['USER_PROPERTY'] as $key) {
    if ($arUser[$key]) {
        $arUserData[$key] = $arUser[$key];
    }
}
?>
<div class="col col-4 col-margin-top" data-city="<?=$arParams['USER']['CITY']?>">
    <div class="doctor-item white-box">
        <?
        if ($arUser['SUBTITLE']):
            ?>
            <div
                class="bx-user-subtitle<? echo $arUser['SUBTITLE_FEATURED'] == 'Y' ? ' bx-user-subtitle-featured' : '' ?>">
					<? echo $arUser['SUBTITLE'] ?>
            </div>
        <?
        endif;
        ?>
        <div class="bx-user-controls">
            <div class="bx-user-control">

                <?
                $arr_serv = array_key_exists('SERVICES',$arUser)?$arUser['SERVICES']:MCSchedule::GetEmployeeServices($arUser['ID'], $arParams['SHOW_SERVICES'] == 'Y');
                $arDays = $arUser['WORKING_TIME'];
				$arDaysNumber = !empty($arDays['DAYS']) ? array_keys($arDays['DAYS']) : array();
                sort($arDaysNumber);
                $strDays = '';
                foreach($arDaysNumber as $day) {
                    $strDays .= '<div class="day">'.GetMessage('DAY_'.$day).'</div>';
                }
                ?>
            </div>
        </div>
        <?
            if ($arUser['PERSONAL_PHOTO']) {
                $rsFile = CFile::GetByID($arUser['PERSONAL_PHOTO']);
                $arFile = $rsFile->Fetch();
                if ($arFile) {
                    $wid = '100px';
                    $marg = '120px';
                    $img = '/upload/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
                }
                else {
                    $wid = '100px';
                    $marg = '120px';
                    if (!empty($arUser['PERSONAL_GENDER'])) {
						$img = $templateFolder.'/images/nopic_user_'.ToLower($arUser['PERSONAL_GENDER']).'.png';
					} else {
						$img = $templateFolder.'/images/nopic_user_100_noborder.gif';
					}
                }
            }
            else {
                if (!empty($arUser['PERSONAL_GENDER'])) {
					$img = $templateFolder.'/images/nopic_user_'.ToLower($arUser['PERSONAL_GENDER']).'.png';
				} else {
					$img = $templateFolder.'/images/nopic_user_100_noborder.gif';
				}
            }
            ?>
            <?// if (!empty($arParams['USER_INFO_LINK'])): ?>
        <?
        $linkDetail = '';
        if (!empty($arParams['USER_INFO_LINK'])) {
            if (stripos($arParams['USER_INFO_LINK'], SITE_DIR.'employees/personal_info.php') === 0
                && !stripos($arParams['USER_INFO_LINK'], '#user_id#')) {
                $linkDetail = $arParams['USER_INFO_LINK'].'?employee='.$arUser['ID'];
            } else {
                $linkDetail =  str_ireplace('#user_id#', $arUser['ID'], $arParams['USER_INFO_LINK']);
            }
        }
        ?>
        <? if (!empty($linkDetail)): ?>
            <a href="<?= $linkDetail ?>" name="system_person_<?= $arUser['ID'] ?>" class="doctor-item-image" style="background-image: url('<?= $img ?>');"></a>
        <? else: ?>
            <div class="doctor-item-image" style="background-image: url('<?= $img ?>');"></div>
        <? endif; ?>
        <div class="doctor-item-content equal have-footer">
            <h3 class="doctor-item-header">
                
               <a href="<?= $linkDetail ?>" name="system_person_<?= $arUser['ID'] ?>">
                   <?=$arParams["USER"]["LAST_NAME"] . " " . $arParams["USER"]["NAME"] . " " . $arParams["USER"]["SECOND_NAME"]?>
               </a>
                
            </h3>

            <div class="doctor-item-description">Рейтинг: <?=$arParams["USER"]["RATE"]?></div>
            
			<?//<a class="btn btn-secondary btn-mini mt10" href="#">Подробнее</a>?>
            
            <div class="doctor-item-footer">
            	<?=$arParams["USER"]["UF_QUALIFICATION"]?>
            </div>

            
        </div>

		<?/*<a href="#" class="doctor-item-comments">5</a>*/?>

    </div>
</div>
