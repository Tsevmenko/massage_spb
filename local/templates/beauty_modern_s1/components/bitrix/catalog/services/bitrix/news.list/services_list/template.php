<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="search-in-page col-margin-top">
    <form>
        <div class="search-in-page-wrapper" id="bx_service_search_wrapper">
            <input class="input input-block input-search search-in-page-input" type="text" placeholder="<?=GetMessage('MC_REC_SERVICE_SEARCH')?>">
            <button id="bx-btn-reset" type="reset" class="search-in-page-reset"><i class="icon icon-search-reset"></i></button>
            <button class="search-in-page-btn"><i class="icon icon-search"></i></button>
            <div id="serviceSearchR" style="display:none;" class="we-serch"><?=GetMessage('MC_REC_SEARCH_WAIT')?></div>
        </div>
    </form>
</div>
<div class="services-list col-margin-top">
		<?$oldLevel = 0;?>
		<?$arSearch=array();?>
		<?if (!empty($arResult["SectionTree"])):?>
			<?foreach($arResult["SectionTree"] as $sKey=>$arSection):?>
				<?if ($sKey==0) continue;?>
                <?$arSearch[$arSection['ID']]=array('found'=>0,'items'=>array());?>
                <? if ($arSection['DEPTH_LEVEL']<=$oldLevel):?>
                    <?=str_repeat('</ul></li>',($oldLevel-$arSection['DEPTH_LEVEL']))?>
                <? endif; ?>
                <?
                if ($arSection['DEPTH_LEVEL'] == 1):
                    if ($arSection['DEPTH_LEVEL']<=$oldLevel): ?>
                        <?= '</div></div>' ?>
                    <? endif; ?>
                <div id="sec_<?=$arSection['ID']?>" class="services-list-item">
                    <div class="services-list-header"><?=$arSection['NAME']?></div>
                        <div class="services-list-content white-content-box">
                            <ul>
                                <?if (!empty($arSection['ITEMS'])): ?>
                                    <?foreach($arSection['ITEMS'] as $arService):?>
                                        <?$arSearch[$arSection['ID']]['items'][$arService['ID']]=array(
                                            'id'=>$arService['ID'],
                                            'sid' => $arSection['ID'],
                                            'name' => strtoupper($arService['NAME']),
                                        );?>
                                        <li id="sec_<?=$arSection['ID']?>_service_<?=$arService['ID']?>">
                                            <a href="<?=$arService['DETAIL_URL']?>">
                                                <?=$arService['NAME']?>
                                                <?if (!empty($arService['PRICES'])):?>
                                                    <?$price = current($arService['PRICES'])?>
                                                    <?if ($price['VALUE']!=0):?>
                                                        <span class="fl-r">
                                                            <?if (!empty($price['CURRENCY']) && $price['CURRENCY']!="RUB"):?>
                                                                <?=$price['PRINT_VALUE']?>
                                                            <?else:?>
                                                                <?=$price['VALUE']?> p
                                                            <?endif?>
                                                        </span>
                                                    <?endif;?>
                                                <?endif?>
                                            </a>
                                        </li>
                                    <?endforeach;?>
                                <? endif; ?>
                <? else : ?>
                        <li><?=$arSection['NAME']?>
                        <?if (!empty($arSection['ITEMS'])):?>
                            <ul>
                                <?foreach($arSection['ITEMS'] as $arService):?>
                                    <?$arSearch[$arSection['ID']]['items'][$arService['ID']]=array(
                                        'id'=>$arService['ID'],
                                        'sid' => $arSection['ID'],
                                        'name' => strtoupper($arService['NAME']),
                                    );?>
                                    <li id="sec_<?=$arSection['ID']?>_service_<?=$arService['ID']?>">
                                        <a href="<?=$arService['DETAIL_URL']?>">
                                            <?=$arService['NAME']?>
                                            <?if (!empty($arService['PRICES'])):?>
                                                <?$price = current($arService['PRICES'])?>
                                                <?if ($price['VALUE']!=0):?>
                                                    <span class="fl-r">
                                                        <?if (!empty($price['CURRENCY']) && $price['CURRENCY']!="RUB"):?>
                                                            <?=$price['PRINT_VALUE']?>
                                                        <?else:?>
                                                            <?=$price['VALUE']?> p
                                                        <?endif?>
                                                    </span>
                                                <?endif;?>
                                            <?endif?>
                                        </a>
                                    </li>
                                <?endforeach;?>
                            <? if (!isset($arSection['IS_PARENT']) || !$arSection['IS_PARENT']): ?>
                                </ul>
                            <? endif; ?>
                        <?endif;?>
                        <? if (!isset($arSection['IS_PARENT']) || !$arSection['IS_PARENT']): ?>
                            </li>
                        <? endif; ?>
                <? endif; ?>
                    
                <?$oldLevel = $arSection['DEPTH_LEVEL']?>
            <?endforeach;?>
            <? if ($oldLevel): ?>
                <?if ($oldLevel > 1)://close last item tags?>
                    <?=str_repeat("</ul></li>", ($oldLevel-1) );?>
                <?endif?>
                </div></div>
            <?endif?>
        <?endif?>

		<?if (!empty($arResult["SectionTree"][0]['ITEMS'])):?>
            <div id="sec_<?=$arSection['ID']?>" class="services-list-item">
                <div class="services-list-header"><?= GetMessage('MC_OTHER_SERVICES'); ?></div>
                    <div class="services-list-content white-content-box">
                        <ul>
                            <?foreach($arResult["SectionTree"][0]['ITEMS'] as $arService):?>
                                <?$arSearch[$arSection['ID']]['items'][$arService['ID']]=array(
                                        'id'=>$arService['ID'],
                                        'sid' => $arSection['ID'],
                                        'name' => strtoupper($arService['NAME']),
                                    );?>
                                    <li id="sec_<?=$arSection['ID']?>_service_<?=$arService['ID']?>">
                                        <a href="<?=$arService['DETAIL_URL']?>">
                                            <?=$arService['NAME']?>
                                            <?if (!empty($arService['PRICES'])):?>
                                                <?$price = current($arService['PRICES'])?>
                                                <?if ($price['VALUE']!=0):?>
                                                    <span class="fl-r">
                                                        <?if (!empty($price['CURRENCY']) && $price['CURRENCY']!="RUB"):?>
                                                            <?=$price['PRINT_VALUE']?>
                                                        <?else:?>
                                                            <?=$price['VALUE']?> p
                                                        <?endif?>
                                                    </span>
                                                <?endif;?>
                                            <?endif?>
                                        </a>
                                    </li>
                            <?endforeach;?>
                        </ul>
                    </div>
            </div>
		<?endif;?>

</div>
	<?/*<script>
	var serviceSearch = <?=CUtil::PhpToJsObject($arSearch)?>;
	var serviceSearchText='',
		cleared = true;
	jQuery(document).ready(function($) {
		// Form buttons.
		$('.typeahead-wrapper, #bx_service_search_wrapper')
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
			.on('click', '.reset-but, #bx-btn-reset', function() {
//				$(this).fadeOut(150);
//				$(this).parent().parent().find('.search-but').fadeIn(300);
				$(this).parent().parent().find('input').val('');
				ClearSearchService();
			});
		function SearchService (text) {
			$.each(serviceSearch, function( section,  sectionData) {
				serviceSearch[section].found=0;
				$.each(sectionData.items, function( index, value ) {
					var found = value.name.toUpperCase().indexOf(serviceSearchText);
//					var NAME = $('#sec_'+value.sid+'_service_'+value.id).find('a');
//					var text = NAME.html().toString().replace(/<\/span>/gi,'').replace(/<span class=\"?found\"?>/gi,'').replace(/<\/SPAN>/gi,'').replace(/<SPAN class=\"?found\"?>/gi,'');
					if (found==-1) {
						$('#sec_'+value.sid+'_service_'+value.id).hide();
					} else {
//						var replaceFrom = text.substr(found,serviceSearchText.length);
//						var replaceTo = '<span class=\"found\">'+replaceFrom+'</span>';
//						text = text.replace(replaceFrom,replaceTo);
						$('#sec_'+value.sid+'_service_'+value.id).show();
						serviceSearch[value.sid].found++;
					}
//					NAME.html(text);
				});
				if (serviceSearch[section].found>0) {
//					$('#sec_'+section).show().find('h4 .servicesFound').html(' ('+serviceSearch[section].found+') ');
					$('#sec_'+section).show().find('.services-list-header').addClass('active');

				} else {
//					$('#sec_'+section).hide().find('h4 .servicesFound').html('');
                    $('#sec_'+section).hide().find('.services-list-header').removeClass('active');
				}
			});
		}
		function ClearSearchService () {
			$.each(serviceSearch, function( section,  sectionData) {
				$.each(sectionData.items, function( index, value) {
					$('#sec_'+value.sid+'_service_'+value.id).show();
				});
				$('#sec_'+section).show().find('.services-list-header').removeClass('active');
			});
		}
		$('.big-search-inner').on('submit', 'form', function() {
			return false;
		});
	});
</script>
*/?>