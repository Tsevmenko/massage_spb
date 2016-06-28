<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<select class="styler styler-steps" name="companyselect">
	<?foreach($arResult['ITEMS'] as $arCompany):?>
		<option <?=$arParams['CURRENT_COMPANY']==$arCompany['ID']?'selected':''?> data-url="<?=str_replace('#ELEMENT_ID#',$arCompany['ID'],$arParams['DETAIL_URL'])?>" id="company_<?=$arCompany['ID']?>">
			<?=$arCompany['NAME']?>
		</option>
	<?endforeach;?>
</select>
