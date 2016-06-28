<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О клинике");
?>

<div class="negative-margin gray">
<section>
				 				<div>
				 					<img class="news-item-img" src="/images/tmp/12.jpg" alt="">
				 					<div class="news-item white">
				 						В Клиническом центре более 5000 сотрудников, в том числе, более 1200 работников с высшим медицинским образованием и около 1600 сотрудников со средним медицинским образованием.В штате Клинического центра 6 академиков РАМН, 17 заслуженных врачей РФ.
				 					</div>
				 					<hr>
				 				</div>
				 			</section>	 
				 			<section>
								<?$APPLICATION->IncludeComponent("bitrix:news.list", "licences_list", array(
	"IBLOCK_TYPE" => "additional_info",
	"IBLOCK_ID" => "#LICENCE_IBLOCK_ID#",
	"NEWS_COUNT" => "20",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "DETAIL_PICTURE",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
	"ADD_SECTIONS_CHAIN" => "Y",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "Y",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>

				 				
				 			</section>	
</div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>