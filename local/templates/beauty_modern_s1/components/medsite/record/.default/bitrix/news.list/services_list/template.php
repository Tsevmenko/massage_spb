<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="search-in-page col-margin-top">
	<form>
		<div class="search-in-page-wrapper service-search">
			<input class="input input-block input-search search-in-page-input" type="text" name="search-in-page" placeholder="<?=GetMessage('MC_REC_SERVICE_SEARCH')?>">
			<button id="bx-btn-reset" class="search-in-page-reset" type="reset">
				<i class="icon icon-search-reset"></i>
			</button>
			<button class="search-in-page-btn"><i class="icon icon-search"></i></button>
		</div>
	</form>
</div>

		<?$oldLevel = 0;?>
		<?$arSearch=array();?>
		<?foreach($arResult["SectionTree"] as $sKey=>$arSection):?>
			<?if ($sKey==0) continue;?>
		<?$arLetters = array()?>
	<?$arSearch[$arSection['ID']]=array('found'=>0,'items'=>array(),'parent'=>intval($arSection['IBLOCK_SECTION_ID']));?>
		<?if ($arSection['DEPTH_LEVEL']<=$oldLevel):?>
			<?=str_repeat('</div></div>',($oldLevel-$arSection['DEPTH_LEVEL']+1))?>
		<?endif;?>
		<?if ($arSection['DEPTH_LEVEL']==1):?>
			<div id="sec_<?=$arSection['ID']?>" class="step-service-item">
				<div class="h3 col-margin-top mb20"><?=$arSection['NAME']?></div>
				<div class="white-content-box">
		<?else:?>
			<div id="sec_<?=$arSection['ID']?>" class="step-services-theme col-margin-top">
				<div class="step-services-theme-header"><?=$arSection['NAME']?></div>
				<div class="step-services-theme-content">
		<?endif;?>
			<?if (!empty($arSection['ITEMS'])):?>
				<ul class="in-page-nav in-page-nav-price">
					<?foreach($arSection['ITEMS'] as $arService):?>
						<?$letter = strtoupper(substr($arService['NAME'],0,1));?>
						<?$video = in_array($arService['ID'],$arParams['WITH_VIDEO']);?>
						<?$arSearch[$arSection['ID']]['items'][$arService['ID']]=array(
							'id'=>$arService['ID'],
							'sid' => $arSection['ID'],
							'name' => strtoupper($arService['NAME']),
							'video' => $video,
							'foundOnChild' => 0,
						);?>
						<li id="sec_<?=$arSection['ID']?>_service_<?=$arService['ID']?>">
							<a href="<?=str_replace('#SERVICE_ID#',$arService['ID'],$arParams['DETAIL_URL'])?>">
								<?=$arService['NAME']?>
								<?if (!empty($arService['PRICES'])):?>
									<?$price = current($arService['PRICES'])?>
									<?if ($price['VALUE']!=0):?>
										<span class="fl-r">
												<?if (!empty($price['CURRENCY']) && $price['CURRENCY']!="RUB"):?>
													<?=$price['PRINT_VALUE']?>
												<?else:?>
													<?=$price['VALUE']?> <span class="rub"><?=GetMessage('PRICE_RUB')?></span>
												<?endif?>
										</span>
									<?endif?>
								<?endif?>
							</a>
						</li>
					<?endforeach;?>
				</ul>
			<?endif;?>
			<?$oldLevel = $arSection['DEPTH_LEVEL']?>
			<?endforeach;?>
			<?if (!empty($arResult["SectionTree"])):?>
		<?=str_repeat('</div>',($arSection['DEPTH_LEVEL']))?>
	<?else:?>
	<?endif?>
		<?if (!empty($arResult["SectionTree"][0]['ITEMS'])):?>
			<ul class="unstyled non-accordeon-body">
				<?foreach($arResult["SectionTree"][0]['ITEMS'] as $arService):?>
					<?$letter = strtoupper(substr($arService['NAME'],0,1));?>
					<?$arSearch[$arSection['ID']]['items'][$arService['ID']]=array(
						'id'=>$arService['ID'],
						'sid' => $arSection['ID'],
						'name' => strtoupper($arService['NAME']),
					);?>
					<li id="sec_<?=$arSection['ID']?>_service_<?=$arService['ID']?>">
						<?if (!in_array($letter,$arLetters)):
							$arLetters[] = $letter;
							?>
							<!--								<div class="service-letter">--><?//=$letter?><!--</div>-->
						<?endif?>
						<a href="<?=$arService['DETAIL_URL']?>">
							<?=$arService['NAME']?>
							<?if (!empty($arService['PRICES'])):?>
								<?$price = current($arService['PRICES'])?>
								<?if ($price['VALUE']!=0):?>
									<span class="service-price">
												<?if (!empty($price['CURRENCY']) && $price['CURRENCY']!="RUB"):?>
													<?=$price['PRINT_VALUE']?>
												<?else:?>
													<?=$price['VALUE']?> <span class="rub">p</span>
												<?endif?>
											</span>
								<?endif;?>
							<?endif?>
						</a>
					</li>
				<?endforeach;?>
			</ul>
		<?endif;?>
	</div>
<script>
	var serviceSearch = <?=CUtil::PhpToJsObject($arSearch)?>;
	var serviceSearchText='',
		cleared = true;
	jQuery(document).ready(function($) {
		// Form buttons.
		$('.typeahead-wrapper, .service-search')
			.on('keyup change input', 'input[type="text"]', function () {
				var thisPP = $(this).parent().parent();
				var newSearch = $(this).val().toUpperCase();
				if (newSearch!=serviceSearchText){
					serviceSearchText = newSearch;
					if (serviceSearchText.length >1) {
						cleared = false;
						SearchService(serviceSearchText);
					}
					else {
						if (!cleared){
							ClearSearchService(true);
							cleared = true;
						}
					};
				}
			})
			.on('click', '#bx-btn-reset', function() {
				$(this).parent().parent().find('input').val('');
				ClearSearchService();
			});
		function SearchService (text) {
			var searchVideo = $('#SHOW_VIDEO')[0].checked;
			$.each(serviceSearch, function( section,  sectionData) {
				if (serviceSearch[section].foundOnChild>0) 
					serviceSearch[section].found=serviceSearch[section].foundOnChild;
				else
				serviceSearch[section].found=0;
				$.each(sectionData.items, function( index, value ) {
					var found = value.name.toUpperCase().indexOf(serviceSearchText);
					if (searchVideo && value.video==false) found = -1;
					var NAME = $('#sec_'+value.sid+'_service_'+value.id).find('a');
					var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'').trim();
					if (found==-1) {
						$('#sec_'+value.sid+'_service_'+value.id).hide();
					} else {
						var replaceFrom = text.substr(found,serviceSearchText.length);
						var replaceTo = '<span class=\"found\">'+replaceFrom+'</span>';
						text = text.replace(replaceFrom,replaceTo);
						$('#sec_'+value.sid+'_service_'+value.id).show();
						serviceSearch[value.sid].found++;
					}
					NAME.html(text);
				});
				if (serviceSearch[section].found>0) {
					$('#sec_'+section).show().find('h4 .servicesFound').html(' ('+serviceSearch[section].found+') ');
					serviceSearch[section].parent = Number(serviceSearch[section].parent);
					if (serviceSearch[section].parent>0) {
						$('#sec_'+serviceSearch[section].parent).show().find('h4 .servicesFound').html('').parent().parent().addClass('expanded');
						serviceSearch[serviceSearch[section].parent].foundOnChild = serviceSearch[section].found;
					}
				} else {
					$('#sec_'+section).hide().find('h4 .servicesFound').html('');
				}
			});
		}
		function ClearSearchService (slide) {
			var searchVideo = $('#SHOW_VIDEO')[0].checked;
			$.each(serviceSearch, function( section,  sectionData) {
				if (serviceSearch[section].foundOnChild>0) 
					serviceSearch[section].found=serviceSearch[section].foundOnChild;
				else
				serviceSearch[section].found=0;
				$.each(sectionData.items, function( index, value) {
					var NAME = $('#sec_'+value.sid+'_service_'+value.id).find('a');
					var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'');
					NAME.html(text);
					if (!searchVideo || value.video) {
						$('#sec_'+value.sid+'_service_'+value.id).show();
						serviceSearch[value.sid].found++;
					}
					else {
						$('#sec_'+value.sid+'_service_'+value.id).hide();
					}
				});
				if (serviceSearch[section].found>0) {
					$('#sec_'+section).show().find('h4 .servicesFound').html('');
					serviceSearch[section].parent = Number(serviceSearch[section].parent);
					if (serviceSearch[section].parent>0) {
						$('#sec_'+serviceSearch[section].parent).show().find('h4 .servicesFound').html('').parent().parent().addClass('expanded');
					}
				} else {
					$('#sec_'+section).hide();
				}
			});
		}
		$('.big-search-inner').on('submit', 'form', function() {
			return false;
		});
		if ($('#SHOW_VIDEO')[0].checked)
			ClearSearchService(true);
	});
</script>