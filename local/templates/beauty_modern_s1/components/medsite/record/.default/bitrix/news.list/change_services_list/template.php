<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $haveNoServiceRecord = in_array('NONE',$GLOBALS[$arParams['FILTER_NAME']]['ID'])?>

<div class="changeServiceList">
	<form>
		<input class="typeahead-input" type="text" placeholder="<?=GetMessage('MC_REC_SERVICE_SEARCH')?>">
		<span class="search-but"><?=GetMessage('MC_REC_SEARCH')?></span>
		<button class="search-but-true" type="button" title="<?=GetMessage('MC_REC_SEARCH')?>"><?=GetMessage('MC_REC_SEARCH')?></button>
		<button class="reset-but" type="button" title="<?=GetMessage('MC_REC_CLEAR')?>"><?=GetMessage('MC_REC_CLEAR')?></button>
		<div id="serviceSearchR" style="display:none;" class="we-serch"><?=GetMessage('MC_REC_SEARCH_WAIT')?></div>
	</form>
	<div class="services-content">
	<?$oldLevel = 0;?>
	<?$arSearch=array();?>
		<ul class="unstyled">
			<?if ($haveNoServiceRecord):?>
				<li id="service_0">
					<a href="<?=str_replace('#SERVICE_ID#','NONE',$arParams['DETAIL_URL'])?>"><?=$arParams['RECORD_WITHOUT_SERVICE']?></a>
				</li>
			<?endif?>
			<?foreach($arResult['ITEMS'] as $arService):?>
				<?$arSearch[$arService['ID']]=array(
					'id'=>$arService['ID'],
					'name' => strtoupper($arService['NAME']),
				);?>
				<li id="service_<?=$arService['ID']?>">
					<a href="<?=str_replace('#SERVICE_ID#',$arService['ID'],$arParams['DETAIL_URL'])?>"><?=$arService['NAME']?></a>
				</li>
			<?endforeach;?>
		</ul>
	</div>
</div> <!-- .all-services -->
<script>
	var serviceSearch = <?=CUtil::PhpToJsObject($arSearch)?>;
	var serviceSearchText='';
	jQuery(document).ready(function($) {
		$('.big-search-inner').on('submit', 'form', function() {
			return false;
		});
		// Form buttons.
		$('.typeahead-wrapper, .service-search')
			.on('keyup change input', 'input[type="text"]', function () {
				var thisPP = $(this).parent();
				var newSearch = $(this).val().toUpperCase();
				if (newSearch!=serviceSearchText){
					serviceSearchText = newSearch;
					if (serviceSearchText.length >2) {
						thisPP.find('.reset-but').fadeIn(400);
						thisPP.find('.search-but').fadeOut(100);
						SearchService(serviceSearchText);
					}
					else {
						thisPP.find('.reset-but').fadeOut(150);
						thisPP.find('.search-but').fadeIn(300);
						if (serviceSearchText.length == 2)
							ClearSearchService();
					};
				}
			})
			.on('click', '.reset-but', function() {
				$(this).fadeOut(150);
				$(this).parent().parent().find('.search-but').fadeIn(300);
				$(this).parent().parent().find('input').val('');
				ClearSearchService();
			});
		function SearchService (text) {
			$('#serviceSearchR').show();
			$.each(serviceSearch, function( index, value) {
				var found = value.name.toUpperCase().indexOf(serviceSearchText);
				var NAME = $('#service_'+value.id).find('a');
				var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'');
				if (found==-1) {
					$('#service_'+value.id).hide();
				} else {
					var replaceFrom = text.substr(found,serviceSearchText.length);
					var replaceTo = '<span class="found">'+replaceFrom+'</span>';
					text = text.replace(replaceFrom,replaceTo);
					$('#service_'+value.id).show();
				}
				NAME.html(text);
			});
			$('#serviceSearchR').hide();
		}
		function ClearSearchService () {
			$('#serviceSearchR').show();
			$.each(serviceSearch, function( index, value) {
				var NAME = $('#service_'+value.id).find('a');
				var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'');
				NAME.html(text);
				$('#service_'+value.id).show();
			});
			$('#serviceSearchR').hide();
		}
	});
</script>