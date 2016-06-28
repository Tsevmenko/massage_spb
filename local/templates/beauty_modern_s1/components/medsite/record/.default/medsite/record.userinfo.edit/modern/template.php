<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
CModule::IncludeModule('form');

$DIR = dirname(__FILE__);
$DIR = str_replace('\\','/',$DIR);

$inComponent = strrpos($DIR,'/bitrix/components/');
if ($inComponent===false)
$templatePath = substr($DIR,strrpos($DIR,'/bitrix/templates/'));
else
$templatePath = substr($DIR,$inComponent);

?>
<script src="<?=$templatePath?>/js/jquery.maskedinput.min.js"></script>


<script>
	jQuery(document).ready(function($) {
		$('#divProfile').show();
		$('#USER_PROFILE').hide();
		$(document)
			.on('click', '.current-profile', function() {
				$('.profile-selector-inner').fadeIn(600);
			})
			.on('click', '.profile-selector-inner li', function() {
				var thisEl = $(this).find('.profile-name');
				$('.current-profile').text(thisEl.text());
				$(this).addClass('current').siblings().removeClass('current');
				var id = thisEl.data('id');
				$('#USER_PROFILE').val(id);
				ChangeProfile();
			})
			.on('click', function (e) {
				if ($(e.target).closest('.current-profile').length) return;
				$('.profile-selector-inner').fadeOut('150');
			})
			.on('click', '.show-pass', function () {
			var thisEl = $(this);
			var textHide = thisEl.data('hide');
			var textShow = thisEl.data('show');
			if (thisEl.hasClass('expanded')) {
				thisEl.removeClass('expanded').text(textShow);
				$('#typePass').prop('type', 'password');
			}
			else {
				thisEl.addClass('expanded').text(textHide);
				$('#typePass').prop('type', 'text');
			};
		})
	});
	var upd = <?=$arResult['profilesData']?>;
	function ChangeProfile() {
		var el = document.getElementById('USER_PROFILE');
		var val = upd[el.value];
		for (v in val) {
			var prop = document.getElementById('PROPERTY_' + v);
			if (prop != undefined && prop != null) {
				prop.value = val[v];
				prop.innerHTML = val[v];
			}
		}
	}
</script>
<div class="content">
	<div class="col col-4">
		<div class="h3"><?=GetMessage('RECORD_USER_EDIT_ON')?></div>
		<div class="white-box record-tikket">
			<?
			$ts = strtotime($arResult['TALON']['DATE']);
			$day = date('j',$ts);
			$month = date('n',$ts);
			$dayString = date('N',$ts);
			?>
			<div class="date"></div>

			<?if ($arResult['TALON']['TYPE']!==rec_type_wish):?>
				<div class="record-tikket-date clearfix">
					<div class="record-tikket-time">
						<?=$arResult['TALON']['TIME_START']?>
					</div>
					<?=$day?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$month)?>, <?=GetMessage('RECORD_USER_EDIT_DAY_'.$dayString)?>
				</div> <!-- .record-tikket-date clearfix -->
			<?else:?>
				<div class="record-tikket-date clearfix">
					<?=$day?> <?=GetMessage('RECORD_USER_EDIT_MONTH_'.$month)?>, <?=GetMessage('RECORD_USER_EDIT_DAY_'.$dayString)?>
					<hr>
					<?=$arResult['TALON']['type_name']?> <?=$arResult['TALON']['TIME_START']?>-<?=$arResult['TALON']['TIME_END']?>
				</div> <!-- .record-tikket-date clearfix -->
			<?endif;?>
			<hr>
			<?if (isset($arResult['TALON']['SERVICE_INFO']) && !empty($arResult['TALON']['SERVICE_INFO'])):?>
				<div class="record-tikket-header">
					<?=GetMessage('RECORD_USER_SERVICE')?>
				</div>
				<div class="record-tikket-text">
					<?=$arResult['TALON']['SERVICE_INFO']['NAME']?>
				</div>
				<p class="service"></p>
			<?endif;?>
			<?if (isset($arResult['TALON']['EMPLOYEE_INFO']) && !empty($arResult['TALON']['EMPLOYEE_INFO'])):
				$NAME = $arResult['TALON']['EMPLOYEE_INFO']['LAST_NAME'];
				if (!empty($arResult['TALON']['EMPLOYEE_INFO']['NAME']))
					$NAME .= ' '.$arResult['TALON']['EMPLOYEE_INFO']['NAME'];
				if (!empty($arResult['TALON']['EMPLOYEE_INFO']['SECOND_NAME']))
					$NAME .= ' '.$arResult['TALON']['EMPLOYEE_INFO']['SECOND_NAME'];
				?>
				<div class="record-tikket-header">
					<?=GetMessage('RECORD_USER_EMPLOYEE')?>
				</div>
				<div class="record-tikket-text">
					<?=$NAME?>
				</div>
			<?endif;?>
			<div class="record-tikket-header">
				<?=GetMessage('RECORD_USER_COMPANY')?>
			</div>
			<div class="record-tikket-text">
				<?=$arResult['TALON']['EMPLOYEE_INFO']['ORGANIZATION']['NAME']?>
			</div>
			<?if (!empty($arResult['TALON']['PRICE'])):?>
				<div class="record-tikket-price clearfix">
					<div class="fl-l text-light">
						<?=GetMessage('TALON_PRICE')?>
					</div>
					<div class="fl-r">
						<? if ($arResult['TALON']['PRICE']>0): ?>
							<?if ($arResult['TALON']['CURRENCY']!="RUB" && !empty($arResult['TALON']['CURRENCY'])):?>
								<?=$arResult['TALON']['PRINT_INT_PRICE']?>
							<?else:?>
								<span class="service-price"><?=intval($arResult['TALON']['PRICE'])?>
									<span class="rub"><?=GetMessage('PRICE_RUB')?></span>
							</span>
							<?endif?>
						<? else: ?>
							<?=GetMessage('PRICE_EMPTY')?>
						<? endif; ?>
					</div>
				</div> <!-- .record-tikket-price clearfix -->
			<?endif?>

			<div class="ta-center">
				<a href="<?=$APPLICATION->GetCurPageParam('STEP=service',array('STEP'))?>" class="btn btn-outline btn-outline-bold"><?=GetMessage('RECORD_USER_EDIT_BACK')?></a>
			</div>


		</div> <!-- .white-box record-tikket -->
	</div> <!-- .col col-4 -->
	<div class="col col-8">
		<div class="h3"><?=GetMessage('RECORD_USER_CONTACTS_DATA')?></div>
		<div class="white-content-box">
			<form method="POST" action="<?=$APPLICATION->GetCurPageParam()?>">
				<?foreach($arResult['ERRORS'] as $error):?>
					<p>
					<div class="textError"><?=$error?></div>
					</p>
				<?endforeach?>
				<? if (count($arResult['profiles']) > 1): ?>
					<div class="col-margin-bottom">
						<div class="mb10">
							<?=GetMessage('RECORD_USER_EDIT_PROFILE')?>
						</div>

						<select class="styler col-6 mb20" id="USER_PROFILE" onChange="ChangeProfile()"
								name="USER_PROFILE">
							<?
							$num = 0;
							$add_num = '';
							foreach ($arResult['profiles'] as $id => $profile):?>
								<option <?= $id == $arParams['userInfo']? 'selected' : '' ?> class="profile-name"
																							 value="<?= $id ?>"><?= $add_num.' '.$profile ?></option>
								<?
								$num = $num + 1;
								$add_num = $num.'.'
								?>
							<? endforeach; ?>
						</select>
					</div>
				<? endif; ?>

				<?if (isset($arResult['propertyList']['EMAIL'])):?>
					<?$arResult['propertyList']['EMAIL']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-isemail="Y" data-verification="Y" placeholder="name@example.com" size="33"','class="input col-6 mb20'),$arResult['propertyList']['EMAIL']['DISPLAY_VALUE']);?>
					<?$arResult['propertyList']['EMAIL']['DISPLAY_VALUE'] = str_replace('<br />','',$arResult['propertyList']['EMAIL']['DISPLAY_VALUE']);?>
					<?if ($arResult['propertyList']['EMAIL']['SITES'][SITE_ID]['REQUIRED'])
						$arResult['propertyList']['EMAIL']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-require-error="'.GetMessage('VERIFY_ERROR_REQUIRED_EMAIL').'" data-require="Y"','class="input required col-6 mb20'),$arResult['propertyList']['EMAIL']['DISPLAY_VALUE']);
					?>
					<div class="col-margin-bottom">
						<div class="mb10"><?=GetMessage('RECORD_USER_EDIT_EMAIL')?> <?if ($arResult['propertyList']['EMAIL']['SITES'][SITE_ID]['REQUIRED']):?><span class="req">*</span><?endif;?></div>
						<div class="input-status"><?=$arResult['propertyList']['EMAIL']['DISPLAY_VALUE']?></div>
					</div> <!-- .form-group -->
				<?unset ($arResult['propertyList']['EMAIL']);endif;?>

				<?if (isset($arResult['propertyList']['PHONE'])):?>
					<?$arResult['propertyList']['PHONE']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-verification="Y" placeholder="8 (000) 000-00-00" size="22"','class="input  col-4'),$arResult['propertyList']['PHONE']['DISPLAY_VALUE']);?>
					<?$arResult['propertyList']['PHONE']['DISPLAY_VALUE'] = str_replace('<br />','',$arResult['propertyList']['PHONE']['DISPLAY_VALUE']);?>
					<?if ($arResult['propertyList']['PHONE']['SITES'][SITE_ID]['REQUIRED'])
						$arResult['propertyList']['PHONE']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-require-error="'.GetMessage('VERIFY_ERROR_REQUIRED_PHONE').'" data-require="Y"','class="input required col-4'),$arResult['propertyList']['PHONE']['DISPLAY_VALUE']);
					?>
					<div class="col-margin-bottom">
						<div class="mb10"><?=GetMessage('RECORD_USER_EDIT_PHONE')?> <?if ($arResult['propertyList']['PHONE']['SITES'][SITE_ID]['REQUIRED']):?><span class="req">*</span><?endif;?></div>
						<?=$arResult['propertyList']['PHONE']['DISPLAY_VALUE']?>
					</div> <!-- .form-group -->

				<?unset ($arResult['propertyList']['PHONE']);endif;?>

				<div class="col-margin-bottom">
					<div class="mb10"><?=GetMessage('RECORD_USER_EDIT_YOUR_NAME')?> <span class="req">*</span></div>
					<?if (isset($arResult['propertyList']['LAST_NAME'])):?>
						<?$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-verification="Y" placeholder="'.$arResult['propertyList']['LAST_NAME']['NAME'].'" size="33"','class="input col-6 mb20'),$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE']);?>
						<?$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE'] = str_replace('<br />','',$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE']);?>
						<?if ($arResult['propertyList']['LAST_NAME']['SITES'][SITE_ID]['REQUIRED'])
							$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-require-error="'.GetMessage('VERIFY_ERROR_REQUIRED_LAST_NAME').'" data-require="Y"','class="input required col-6 mb20'),$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE']);
						?>
						<div class="input-status"><?=$arResult['propertyList']['LAST_NAME']['DISPLAY_VALUE']?></div>
						<?unset ($arResult['propertyList']['LAST_NAME']);endif;?>
					<?if (isset($arResult['propertyList']['NAME'])):?>
						<?$arResult['propertyList']['NAME']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-verification="Y" placeholder="'.$arResult['propertyList']['NAME']['NAME'].'" size="33"','class="input col-6 mb20'),$arResult['propertyList']['NAME']['DISPLAY_VALUE']);?>
						<?$arResult['propertyList']['NAME']['DISPLAY_VALUE'] = str_replace('<br />','',$arResult['propertyList']['NAME']['DISPLAY_VALUE']);?>
						<?if ($arResult['propertyList']['NAME']['SITES'][SITE_ID]['REQUIRED'])
							$arResult['propertyList']['NAME']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-require-error="'.GetMessage('VERIFY_ERROR_REQUIRED_NAME').'" data-require="Y"','class="input required col-6 mb20'),$arResult['propertyList']['NAME']['DISPLAY_VALUE']);
						?>
						<?=$arResult['propertyList']['NAME']['DISPLAY_VALUE']?>
						<?unset ($arResult['propertyList']['NAME']);endif;?>
					<?if ($arResult['propertyList']['SECOND_NAME']['SITES'][SITE_ID]['ACTIVE']=='Y'):?>
						<?$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-verification="Y" placeholder="'.$arResult['propertyList']['SECOND_NAME']['NAME'].'" size="33"','class="input  col-6 mb20'),$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE']);?>
						<?$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE'] = str_replace('<br />','',$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE']);?>
						<?if ($arResult['propertyList']['SECOND_NAME']['SITES'][SITE_ID]['REQUIRED'])
							$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-require-error="'.GetMessage('VERIFY_ERROR_REQUIRED_SECOND_NAME').'" data-require="Y"','class="input required col-6 mb20'),$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE']);
						?>
						<div class="input-status"><?=$arResult['propertyList']['SECOND_NAME']['DISPLAY_VALUE']?></div>
						<?unset ($arResult['propertyList']['SECOND_NAME']);endif;?>
				</div> <!-- .form-group -->

				<?if (!empty($arResult['propertyList'])):?>
					<?foreach ($arResult['propertyList'] as $property):?>
						<?if ($property['SITES'][SITE_ID]['ACTIVE']!='Y') continue;?>
						<?$property['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-verification="Y" size="33"','class="input  col-6 mb20'),$property['DISPLAY_VALUE']);?>
						<?$property['DISPLAY_VALUE'] = str_replace('<br />','',$property['DISPLAY_VALUE']);?>
						<?if ($property['SITES'][SITE_ID]['REQUIRED'])
							$property['DISPLAY_VALUE'] = str_replace(array('<input','class="input2 form-control'),array('<input data-require="Y"','class="input required col-6 mb20'),$property['DISPLAY_VALUE']);
						?>
						<div class="col-margin-bottom">
							<div class="mb10"><?=$property['NAME']?><?if ($property['SITES'][SITE_ID]['REQUIRED']):?><span class="req">*</span><?endif;?></div>
							<?=$property['DISPLAY_VALUE']?>
						</div> <!-- .form-group -->
					<?endforeach;?>
				<?endif;?>
				<?if (!$USER->IsAuthorized() && COption::GetOptionString("main", "new_user_registration", "N")=='Y'):?>
					<div class="col-margin-bottom">
						<input class="checkbox" name="autoReg" type="checkbox" id="autoReg"><label for="autoReg"><span></span> <?=GetMessage('RECORD_USER_EDIT_FAST_REGISTER')?></label>
					</div>
				<?endif;?>
				<p><?=GetMessage('RECORD_USER_EDIT_ATTENTION')?></p>

				<?if (!$USER->IsAuthorized() && COption::GetOptionString("main", "new_user_registration", "N")=='Y'):?>
					<div style="display:none;" class="col-margin-bottom pass-block clearfix">
						<div class="mb10"><?=GetMessage('RECORD_USER_EDIT_PASSWORD')?></div>
						<input name="password" type="password" id="typePass" class="input col-6 mb20" placeholder="<?=GetMessage('RECORD_USER_EDIT_PASSWORD_ENTER')?>"> <span class="show-pass dashed-link" data-show="<?=GetMessage('RECORD_USER_EDIT_PASSWORD_SHOW')?>" data-hide="<?=GetMessage('RECORD_USER_EDIT_PASSWORD_HIDE')?>"><?=GetMessage('RECORD_USER_EDIT_PASSWORD_SHOW')?></span>
					</div> <!-- .form-group -->
				<?endif;?>

				<? if (!$USER->IsAuthorized() && !$_SESSION['tested']): ?>
					<? $FORM = new CFormOutput() ?>
					<? $code = htmlspecialcharsbx($APPLICATION->CaptchaGetCode()) ?>
					<div class="col-margin-bottom">
						<div class="mb10"><?= GetMessage('CALENDAR_CAPCHA_WORD') ?><?= $FORM->ShowRequired() ?>:</div>
						<input type="hidden" name="captcha_sid" value="<?= $code ?>"/>
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $code ?>" width="180" height="40"
							 alt="CAPTCHA"/><br>
						<input class="input col-6 mb20 mt10" type="text" name="captcha_word" maxlength="50" value=""/>
					</div>
				<? endif ?>
				<div class="ta-center">
					<button type="submit" class="btn btn-big"><?=GetMessage('RECORD_USER_EDIT_RECORD')?></button>
				</div>



			</form>
		</div> <!-- .white-content-box -->
	</div> <!-- .col col-8 -->
</div>

<script>
	jQuery(document).ready(function($) {
		var errors = 0;
		$('form').on('click', 'button', function () {
				errors = 0;
				$('[data-verification="Y"]').each(function() {
					var thisEl = $(this);
					errors = Number(errors) + Number(verifyField(thisEl));
				});
				if (errors>0)
					return false;
		})
		$('body').on('change', '#autoReg', function () {
			if (this.checked)
				$('.pass-block').show();
			else
				$('.pass-block').hide();
		})
		$('form [data-verification="Y"]').focusout(function() {
			verifyField($(this));
		})
		function verifyField (field) {
			var errors=0;
			var required = field.data('require');
			if (required) {
				var data = field.val();
				if (data=='') {
					errors = Number(errors) + 1;
					errorText = field.data('require-error');
					if (errorText==undefined || errorText=='')
						errorText = '<?=GetMessage('VERIFY_ERROR_REQUIRED')?>';

					field.parent().removeClass('valid').addClass('error');
					field.popover({
						content : errorText,
						trigger: 'manual'
					}).popover('show');
				}
			}
			var isEmail = field.data('isemail');
			if (errors==0 && isEmail) {
				var data = field.val();
				if (data!='') {
					var re = /^[=_.0-9a-z+~'!\$&*^`|\#%/?{}-]+@(([-0-9a-z_]+\.)+)([a-z0-9-]{2,10})$/i;
					if (re.test(data)==false) {
						errors = Number(errors) + 1;

						field.parent().removeClass('valid').addClass('error');
						field.data('error','empty');
						field.popover({
							// container : '.form-group',
							content : '<?=GetMessage('VERIFY_ERROR_EMAIL')?>',
							trigger: 'manual'
						}).popover('show');
					}
				}
			}
			if (errors==0) {
				field.parent().removeClass('error').addClass('valid');
				field.popover('destroy');
			}
			return errors;
		}
	});
	var mask ='<?=$arParams['PHONE_MASK']?>';
	var pos = mask.indexOf('9');
	mask = mask.substr(0,pos+1)+"?"+mask.substr(pos+1);
	$('#PROPERTY_PHONE').mask(mask,{placeholder:' '});
</script>