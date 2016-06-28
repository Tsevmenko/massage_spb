<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<div class="news-list">
	<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
		<?= $arParams['nav_pages'] ?><br/>
	<? endif; ?>
	<? foreach ($arParams["users"] as $arItem) { ?>
		<?$APPLICATION->IncludeComponent("medsite:medsite.system.person", "structure_page_list", Array(
				"USER"             => $arItem,
				"SHEDULES_BLOCK"   => $arParams['IBLOCK_ID'],
				"USER_INFO_LINK"   => $arParams["PERSON_LINK"],
				"SCHEDULE_LINK"	=> $arParams["SCHEDULE_LINK"],
				"STRUCTURE_PAGE"   => $arParams["STRUCTURE_LINK"],
				"STRUCTURE_FILTER" => $arParams["STRUCTURE_FILTER"],
				"LIST_VIEW"        => "list",
				"USER_PROPERTY"    => array(
					//		2 => "WORK_POSITION",
					4 => "UF_FOUNDATION",
					6 => "PERSONAL_PHONE",
					7 => "UF_PLACE",
					8 => "work_time",
				),
				"SHOW_SERVICES"    => $arParams["SHOW_SERVICES"],
				"DEFAULT_SERVICE"  => $arParams["DEFAULT_SERVICE"],
			),
			false
		);
		?>
	<? } ?>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<br/><?= $arParams['nav_pages'] ?>
	<? endif; ?>
</div>
