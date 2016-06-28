<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("BodyClass", "menu-page"); ?>
<script type="text/javascript">
    BXMobileApp.UI.Page.Scroll.setEnabled(false);

    BX.addCustomEvent("onUserAuthChangeMenu", function() {
        BXMobileApp.UI.Page.reload();
    });
</script>

<? if ($GLOBALS["USER"]->IsAuthorized()) { ?>
    <?
    $APPLICATION->IncludeComponent(
        'bitrix:mobileapp.menu',
        'mobile',
        array("MENU_FILE_PATH"=>SITE_DIR."mobile_app/.mobile_menu_right.php", "MENU_ID" => "right-menu"),
        false,
        Array('HIDE_ICONS' => 'Y'));
    ?>
<? } else { ?>
    <div class="need-auth">
        <p>Авторизованным пользователям доступно больше функций.</p>
        <div onclick="BXMobileApp.PageManager.loadPageModal({url: '<?=SITE_DIR?>mobile_app/auth/'});" class="btn btn-login">Войти</div>
        <p><a href="#" onclick="BXMobileApp.PageManager.loadPageModal({url: '<?=SITE_DIR?>mobile_app/auth/register.php'}); return false;">Регистрация</a></p>
    </div>
<? } ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>