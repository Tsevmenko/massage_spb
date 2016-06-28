<?	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Мастер записи");
$APPLICATION->SetPageProperty("description", "Мастер записи");
$APPLICATION->SetTitle($currentSite['NAME']);
?>
	<div id="menu" data-id="menu">
		<ul class="datetime" data-container="datetime">
			<li class="time" data-item="time"></li>
			<li class="date" data-item="date"></li>
		</ul>
		<a href="<?=$arParams['MAIN_PAGE_LINK']?>" class="link"><?=GetMessage('MCInfomatExitRecord')?></a>
		<div class="clinic-container" data-container="clinic" data-action="clinic">
			<div class="clinic-content">
				<div class="clinic-title" data-title="clinic"></div>
				<div class="clinic-button"><?=GetMessage('MCInfomatChange')?></div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="main-title commonLogo"><?=$currentSite['NAME']?></div>
		<ul class="main-menu">
			<li class="main-menu-item item1 disabled">
				<span class="main-menu-text">Расписание работы врачей</span>
			</li>
			<li class="main-menu-item item2">
				<a href="/infomat/record/" class="main-menu-link">
					<span class="main-menu-text">Запись на приём</span>
				</a>
			</li>
			<li class="main-menu-item item3 disabled">
				<span class="main-menu-text">Личный кабинет</span>
			</li>
		</ul>
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include", "",
			Array(
				"AREA_FILE_SHOW" => "file",
				"PATH"           => SITE_DIR."includes/infomat-addr.php"
			));?>

	</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>