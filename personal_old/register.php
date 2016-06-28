<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>
<? if ($_REQUEST['forgot_password'] == "yes"): ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:system.auth.forgotpasswd", "", false
    );?>
<? else: ?>
    <?$APPLICATION->IncludeComponent("bitrix:main.register", "register", Array(
                                                               "USER_PROPERTY_NAME" => "", // Название блока пользовательских свойств
                                                               "SHOW_FIELDS"        => array( // Поля, которые показывать в форме
                                                                   0 => "NAME",
                                                                   1 => "SECOND_NAME",
                                                                   2 => "LAST_NAME",
                                                                   3 => "PERSONAL_STREET",
                                                                   4 => "PERSONAL_PHONE",
                                                                   5 => "PERSONAL_MOBILE",
                                                               ),
                                                               "REQUIRED_FIELDS"    => "", // Поля, обязательные для заполнения
                                                               "AUTH"               => "Y", // Автоматически авторизовать пользователей
                                                               "USE_BACKURL"        => "Y", // Отправлять пользователя по обратной ссылке, если она есть
                                                               "SUCCESS_PAGE"       => "", // Страница окончания регистрации
                                                               "SET_TITLE"          => "Y", // Устанавливать заголовок страницы
                                                               "USER_PROPERTY"      => "", // Показывать доп. свойства
                                                           ),
                                     false
    );?>
<?endif ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>