<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$frame = $this->createFrame()->begin();
?>
<?if($arResult["FORM_TYPE"] == "login" || $arResult["FORM_TYPE"] == "otp"):?>
	<a class="top-line-link" href="/login/"><i class="icon icon-lk"></i> <?=GetMessage('AUTH_LOGIN_BUTTON')?></a>
<? else: ?>
	<a class="top-line-link" href="/personal/<?//=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><i class="icon icon-lk"></i> <?=GetMessage("AUTH_PROFILE")?></a>
    <a class="top-line-link" href="?logout=yes">[<?=GetMessage("AUTH_LOGOUT_BUTTON")?>]</a>
<?endif?>
<? $frame->beginStub(); ?>
<a class="top-line-link" href="<?= $arParams['REGISTER_URL'] ?>"><i class="icon icon-lk"></i> <?=GetMessage('AUTH_LOGIN_BUTTON')?></a>
<? $frame->end(); ?>