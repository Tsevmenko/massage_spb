<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($arParams['ACTION_URL'])
	$arResult["FORM_ACTION"]=$arParams['ACTION_URL'];
?>
<script>
	var AllIsHidden=true;
	jQuery(document).ready(function($) {
		$('body').on('click', '#CLINIC_FILTER', function() {
			if (AllIsHidden==true) {
				$('#filterOrgCatalog').show()
				$(this).addClass('expanded');
				AllIsHidden = false;
			} else {
				$('#filterOrgCatalog').hide()
				$(this).removeClass('expanded');
				AllIsHidden = true;
			}
			return false;
		});
	});

</script>
<div class="rec-form clearfix">
	<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
		<?foreach($arResult["ITEMS"] as $arItem):
			if(array_key_exists("HIDDEN", $arItem)):
				echo $arItem["INPUT"];
			endif;
		endforeach;?>
		<div class="col-margin-bottom">
			<span class="btn btn-link" id="CLINIC_FILTER"><?=GetMessage("MEDSITE_FILTER_TITLE")?></span>
		</div>
		<div class="col-12">
			<div class="white-box p20 clearfix" id="filterOrgCatalog" style="display:none;">
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?if(!array_key_exists("HIDDEN", $arItem)):
					$arItem["INPUT"] = str_replace('<input','<input class="input" size="33"',$arItem["INPUT"]);
					$arItem["INPUT"] = str_replace('size="20"','',$arItem["INPUT"]);
					$arItem["INPUT"] = str_replace('<select','<select class="input" size="33"',$arItem["INPUT"]);
					if($arItem["INPUT_NAME"]=="arrFilter_ff[SECTION_ID]" && (int)$_REQUEST['sid']>0 && !$_REQUEST['arrFilter_ff']['SECTION_ID'])
					{
						$arItem["INPUT"]=str_replace('<option value="'.(int)$_REQUEST['sid'].'">','<option value="'.(int)$_REQUEST['sid'].'" selected="selected">',$arItem["INPUT"]);
					}
					$arItem["INPUT"]=str_replace('type="checkbox"','type="checkbox" class="checkbox"',$arItem["INPUT"]);
					?>
					<p class="form-label"><?=$arItem["NAME"]?>:</p>
					<div class="input-status"><?=$arItem["INPUT"]?></div>
				<?endif?>
			<?endforeach;?>
			<input class="btn btn-blue" type="submit" name="set_filter" value="<?=GetMessage("MEDSITE_SET_FILTER")?>" /><input type="hidden" name="set_filter" value="Y" />
			<input type="hidden" name="sid" value="">
			</div>
		</div>
	</form>
</div>