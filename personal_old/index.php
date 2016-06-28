<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Для пациента");
$APPLICATION->SetPageProperty("description", "Для пациентаа");
$APPLICATION->SetTitle("Для пациента");
global $USER;
?>
<? if ($USER->IsAuthorized()): ?>
    <p>В карте пациента Вы можете просмотреть или изменить личную информацию, узнать информацию, о ваших записях на
        прием и госпитализацию, а также услугах предоставленных вам. </p>
<? else: ?>
    <p>Войдя в систему Вы сможете просмотреть или изменить личную информацию, узнать информацию, о ваших записях на
        прием и госпитализацию, а также услугах предоставленных вам. </p>
<?endif ?>

<? if ($USER->IsAuthorized()): ?>
    <h3>Информация о талонах и приемах</h3>
    <ul>
        <li><a href="stamps.php">Талоны</a></li>
        <li><a href="services_rendered.php">Оказанные услуги</a></li>
        <li><a href="analysis/">Результаты анализов</a></li>
    </ul>
<? endif ?>


    


    <h3>Обратная связь</h3>
    <ul>
        <li><a href="requests/">Задать вопрос специалисту</a></li>
    </ul>

    <h3>Личная информация</h3>
    <ul>
        <? if ($USER->IsAuthorized()): ?>
            <li><a href="profile/">Изменить регистрационные данные</a></li>
        <? else: ?>
            <li><a href="profile/?login=yes">Авторизация</a></li>
        <?endif ?>
        <li><a href="profile/?change_password=yes">Изменить пароль</a></li>
        <li><a href="profile/?forgot_password=yes">Забыли пароль?</a></li>
    </ul>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>