<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arSearch = array();?>
<div class="col col-12">
	<div class="white-content-box">
		<ul class="links-block clearfix">
		<?foreach($arResult['ITEMS'] as $arSection):
			$arSearch['items'][]=array('id'=>$arSection['ID'],'found'=>0,'video'=>in_array($arSection['ID'],$arParams['WITH_VIDEO']));
			?>
			<li id="spec_<?=$arSection['ID']?>">
				<a class="ppvr" data-html="true" data-container="body" data-placement="bottom" data-trigger="hover" href="<?=$arParams['USER_INFO_BASE_LINK'].'&SPECIALITY='.$arSection['ID']?>"><?=$arSection["NAME"]?></a>
			</li>
		<?endforeach;?>
		</ul>
	</div> <!-- .white-content-box -->
</div> <!-- .col col-12 -->
<script>
	var specialitySearch = <?=CUtil::PhpToJsObject($arSearch)?>;

	function SearchVideoSpeciality () {
		var searchVideo = $('#SHOW_VIDEO')[0].checked;
		$.each(specialitySearch, function( index, value) {
			if (!searchVideo || value.video) {
				$('#spec_'+value.id).show();
			} else {
				$('#spec_'+value.id).hide();
			}
		});
		$('#employeeSearchR').hide();
	}
	SearchVideoSpeciality($('#SHOW_VIDEO')[0].checked);
</script>