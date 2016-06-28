<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Для сотрудников");
?>
    <p><a href="/for_staff/stamps.php">Управление талонами</a></p>
    <p>Позволяет просматривать список имеющихся талонов, а так же утверждать/отклонять их.</p>
    <p><a href="/for_staff/analysis_results.php">Результаты анализов</a></p>
    <p><a href="/for_staff/schedules.php">Расписание</a></p>
    <p>Позволяет просматривать полное расписание врача, в котором показывается сколько талонов уже занято.</p>
    <p><a href="/for_staff/ereg_tuning/schedules.php">Расписание услуг</a></p>
    <p>Позволяет натсраивать расписание работы выбранного сотрудника.</p>
    <p><a href="/for_staff/ereg_tuning/user_fields.php">Поля карточки пациента</a></p>
    <p>Позволяет создавать и настраивать дополнительные поля, которые пациент может ввести при записи на прием.</p>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>