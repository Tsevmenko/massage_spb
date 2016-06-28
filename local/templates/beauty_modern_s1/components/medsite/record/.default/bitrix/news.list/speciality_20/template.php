<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arSearch = array();?>
<ul class="tree-column">
	<?foreach($arResult["LETTERS"] as $letter=>$arDepartment):?>
		<?$arSearch[$letter]=array('found'=>0,'items'=>array());?>
		<li id="spec_<?=$letter?>" class="column-item col-margin-top">
			<div class="column-item-letter"><?=$letter?></div>
			<ul class="in-page-nav">
		<?foreach($arDepartment as $arSection){
			$arSearch[$letter]['items'][]=array('id'=>$arSection['ID'],'found'=>0,'letter'=>$letter,'video'=>in_array($arSection['ID'],$arParams['WITH_VIDEO']));
			?>
			<li id="spec_<?=$letter?>_<?=$arSection['ID']?>">
				<a class="ppvr" data-html="true" href="<?=$arParams['USER_INFO_BASE_LINK'].'&SPECIALITY='.$arSection['ID']?>"><?=$arSection["NAME"]?></a>
			</li>
		<?}?>
			</ul>
		</li>
	<?endforeach;?>
</ul> <!-- .all-specialty -->
<script>
	var AllIsHidden=true;
	var specialitySearch = <?=CUtil::PhpToJsObject($arSearch)?>;
	jQuery(document).ready(function($) {
		$(document).on('click', '#ALL_SPECIALITY', function() {
			if (AllIsHidden==true) {
				$('.all-specialty').show()
				$(this).addClass('expanded');
				AllIsHidden = false;
			} else {
				$('.all-specialty').hide()
				$(this).removeClass('expanded');
				AllIsHidden = true;
			}
			return false;
		});
	});
	function SearchVideoSpeciality () {
		var searchVideo = $('#SHOW_VIDEO')[0].checked;
		$.each(specialitySearch, function( letter,  letterData) {
			specialitySearch[letter].found=0;
				$.each(letterData.items, function( index, value) {
					if (!searchVideo || value.video) {
						$('#spec_'+value.letter+'_'+value.id).show();
						specialitySearch[value.letter].found++;
					} else {
						$('#spec_'+value.letter+'_'+value.id).hide();
					}
				});
			if (specialitySearch[letter].found>0) {
				$('#spec_'+letter).show();
			} else {
				$('#spec_'+letter).hide();
			}
		});
		$('#employeeSearchR').hide();
	}
	SearchVideoSpeciality($('#SHOW_VIDEO')[0].checked);
</script>