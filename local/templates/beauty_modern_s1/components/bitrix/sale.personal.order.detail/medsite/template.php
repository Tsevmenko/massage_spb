<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx_my_order_switch">
	<a class="bx_mo_link" href="<?=$arResult["URL_TO_LIST"]?>"><?=GetMessage('SPOD_CUR_ORDERS')?></a>
</div>

<div class="bx_order_list">

	<?if(strlen($arResult["ERROR_MESSAGE"])):?>

		<?=ShowError($arResult["ERROR_MESSAGE"]);?>

	<?else:?>
	
		<table class="bx_order_list_table">
			<thead>
				<tr>
					<td colspan="2"><?=GetMessage('SPOD_ORDER')?> <?=GetMessage('SPOD_NUM_SIGN')?><?=$arResult["ACCOUNT_NUMBER"]?> <?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_INSERT_FORMATED"]?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<?=GetMessage('SPOD_ORDER_STATUS')?>:
					</td>
					<td>
						<?=$arResult["STATUS"]["NAME"]?> (<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)
					</td>
				</tr>
				<tr>
					<td>
						<?=GetMessage('SPOD_ORDER_PRICE')?>:
					</td>
					<td>
						<?=$arResult["PRICE_FORMATED"]?>
						<?if(floatval($arResult["SUM_PAID"])):?>
							(<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
						<?endif?>
					</td>
				</tr>

				<?if($arResult["CANCELED"] == "Y" || $arResult["CAN_CANCEL"] == "Y"):?>
					<tr>
						<td><?=GetMessage('SPOD_ORDER_CANCELED')?>:</td>
						<td>
							<?if($arResult["CANCELED"] == "Y"):?>
								<?=GetMessage('SPOD_YES')?> (<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_CANCELED_FORMATED"]?>)
							<?elseif($arResult["CAN_CANCEL"] == "Y"):?>
								<?=GetMessage('SPOD_NO')?>&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["URL_TO_CANCEL"]?>"><?=GetMessage("SPOD_ORDER_CANCEL")?></a>]
							<?endif?>
						</td>
					</tr>
				<?endif?>

				<tr><td><br></td><td></td></tr>

				<?if(intval($arResult["USER_ID"])):?>

					<tr>
						<td colspan="2"><?=GetMessage('SPOD_ACCOUNT_DATA')?></td>
					</tr>
					<?if(strlen($arResult["USER_NAME"])):?>
						<tr>
							<td><?=GetMessage('SPOD_ACCOUNT')?>:</td>
							<td><?=$arResult["USER_NAME"]?></td>
						</tr>
					<?endif?>
					<tr>
						<td><?=GetMessage('SPOD_LOGIN')?>:</td>
						<td><?=$arResult["USER"]["LOGIN"]?></td>
					</tr>
					<tr>
						<td><?=GetMessage('SPOD_EMAIL')?>:</td>
						<td><a href="mailto:<?=$arResult["USER"]["EMAIL"]?>"><?=$arResult["USER"]["EMAIL"]?></a></td>
					</tr>

					<tr><td><br></td><td></td></tr>

				<?endif?>

				<tr>
					<td colspan="2"><?=GetMessage('SPOD_ORDER_PROPERTIES')?></td>
				</tr>
				<tr>
					<td><?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:</td>
					<td><?=$arResult["PERSON_TYPE"]["NAME"]?></td>
				</tr>
				<?/*				
				<tr>
					<td><?=GetMessage('SPOD_ORDER_COMPLETE_SET')?>:</td>
					<td></td>
				</tr>
				*/?>

				<?foreach($arResult["ORDER_PROPS"] as $prop):?>

					<?if($prop["SHOW_GROUP_NAME"] == "Y"):?>

						<tr><td><br></td><td></td></tr>
						<tr>
							<td colspan="2"><?=$prop["GROUP_NAME"]?></td>
						</tr>

					<?endif?>

					<tr>
						<td><?=$prop['NAME']?>:</td>
						<td>

							<?if($prop["TYPE"] == "CHECKBOX"):?>
								<?=GetMessage('SPOD_'.($prop["VALUE"] == "Y" ? 'YES' : 'NO'))?>
							<?else:?>
								<?=$prop["VALUE"]?>
							<?endif?>

						</td>
					</tr>

				<?endforeach?>

				<?if(!empty($arResult["USER_DESCRIPTION"])):?>

					<tr>
						<td><?=GetMessage('SPOD_ORDER_USER_COMMENT')?>:</td>
						<td><?=$arResult["USER_DESCRIPTION"]?></td>
					</tr>

				<?endif?>

				<tr><td><br></td><td></td></tr>

				<tr>
					<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT")?></td>
				</tr>
				<tr>
					<td><?=GetMessage('SPOD_PAY_SYSTEM')?>:</td>
					<td>
						<?if(intval($arResult["PAY_SYSTEM_ID"])):?>
							<?=$arResult["PAY_SYSTEM"]["NAME"]?>
						<?else:?>
							<?=GetMessage("SPOD_NONE")?>
						<?endif?>
					</td>
				</tr>
				<tr>
					<td><?=GetMessage('SPOD_ORDER_PAYED')?>:</td>
					<td>
						<?if($arResult["PAYED"] == "Y"):?>
							<?=GetMessage('SPOD_YES')?>
							(<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_PAYED_FORMATED"]?>)
						<?else:?>
							<?=GetMessage('SPOD_NO')?>
							<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
								&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SPOD_REPEAT_PAY")?></a>]
							<?endif?>
						<?endif?>
					</td>
				</tr>

				<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] != "Y"):?>
					<tr>
						<td colspan="2">
							<?
								$ORDER_ID = $ID;
								include($arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]);
							?>
						</td>
					</tr>
				<?endif?>

			</tbody>
		</table>

		<h3><?=GetMessage('SPOD_ORDER_BASKET')?></h3>
		<table class="bx_order_list_table_order">
			<thead>
				<tr>			
					<td ><?=GetMessage('SPOD_NAME')?></td>
					<td class="custom price"><?=GetMessage('SPOD_PRICE')?></td>

					<?if($arResult['HAS_PROPS']):?>
						<td class="custom amount"><?=GetMessage('SPOD_PROPS')?></td>
					<?endif?>
					
					<?if($arResult['HAS_DISCOUNT']):?>
						<td class="custom price"><?=GetMessage('SPOD_DISCOUNT')?></td>
					<?endif?>

				</tr>
			</thead>
			<tbody>
				<?foreach($arResult["BASKET"] as $prod):?>
					<tr>
						<?$hasLink = !empty($prod["DETAIL_PAGE_URL"]);?>
						<td class="custom name">
							<?if($hasLink):?>
								<a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank">
							<?endif?>
							<?=htmlspecialcharsEx($prod["NAME"])?>
							<?if($hasLink):?>
								</a>
							<?endif?>
						</td>

						<td class="custom price"> <span class="fm"><?=GetMessage('SPOD_PRICE')?>:</span> <?=$prod["PRICE_FORMATED"]?></td>

						<?if($arResult['HAS_PROPS']):?>
							<td class="custom"> <span class="fm"><?=GetMessage('SPOD_PROPS')?>:</span> 

								<?if(is_array($prod["PROPS"]) && !empty($prod["PROPS"])):?>
									<table cellspacing="0" class="bx_ol_sku_prop">
										<?foreach($prod["PROPS"] as $prop):?>

											<?if(!empty($prop['SKU_VALUE']) && $prop['SKU_TYPE'] == 'image'):?>

												<tr>
													<td colspan="2">
														<nobr><?=$prop["NAME"]?>:</nobr><br />
														<img src="<?=$prop['SKU_VALUE']['PICT']['SRC']?>" width="<?=$prop['SKU_VALUE']['PICT']['WIDTH']?>" height="<?=$prop['SKU_VALUE']['PICT']['HEIGHT']?>" title="<?=$prop['SKU_VALUE']['NAME']?>" alt="<?=$prop['SKU_VALUE']['NAME']?>" />
													</td>
												</tr>

											<?else:?>

												<tr>
													<td><nobr><?=$prop["NAME"]?>:</nobr></td>
													<td style="padding-left: 10px !important"><b><?=$prop["VALUE"]?></b></td>
												</tr>

											<?endif?>

										<?endforeach?>
									</table>
								<?endif;?>

							</td>
						<?endif?>

						<?if($arResult['HAS_DISCOUNT']):?>
							<td class="custom price"> <span class="fm"><?=GetMessage('SPOD_DISCOUNT')?>:</span> <?=$prod["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
						<?endif?>

					</tr>
				<?endforeach?>

			</tbody>
		</table>
		<br>

		<table class="bx_ordercart_order_sum">
			<tbody>

				<? ///// WEIGHT ?>
				<?if(floatval($arResult["ORDER_WEIGHT"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
						<td class="custom_t2"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
					</tr>
				<?endif?>

				<? ///// PRICE SUM ?>
				<tr>
					<td class="custom_t1"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
					<td class="custom_t2"><?=$arResult['PRODUCT_SUM_FORMATTED']?></td>
				</tr>

				<? ///// DELIVERY PRICE: print even equals 2 zero ?>
				<?if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_DELIVERY')?>:</td>
						<td class="custom_t2"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
					</tr>
				<?endif?>

				<? ///// TAXES DETAIL ?>
				<?foreach($arResult["TAX_LIST"] as $tax):?>
					<tr>
						<td class="custom_t1"><?=$tax["TAX_NAME"]?>:</td>
						<td class="custom_t2"><?=$tax["VALUE_MONEY_FORMATED"]?></td>
					</tr>	
				<?endforeach?>

				<? ///// TAX SUM ?>
				<?if(floatval($arResult["TAX_VALUE"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_TAX')?>:</td>
						<td class="custom_t2"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
					</tr>
				<?endif?>

				<? ///// DISCOUNT ?>
				<?if(floatval($arResult["DISCOUNT_VALUE"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
						<td class="custom_t2"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
					</tr>
				<?endif?>

				<tr>
					<td class="custom_t1 fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
					<td class="custom_t2 fwb"><?=$arResult["PRICE_FORMATED"]?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table class="bx_control_table" style="width: 100%;">
			<tr>
				<td>  <a href="<?=$arResult["URL_TO_LIST"]?>" class="bx_big btn bx_cart"><?=GetMessage('SPOD_GO_BACK')?></a></td>
			</tr>
		</table>

	<?endif?>

</div>