<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>

<div class="doctors-search">
            <input class="input input-search input-round-search" type="text" name="users_LAST_NAME" value="" placeholder="Фамилия, имя или отчество">
            <button class="btn btn-round-search">Поиск</button>
    </div>

<link rel="stylesheet" type="text/css" href="style.css">
<script src="jquery.mask.js"></script>
<script src="main.js"></script>

<div class="fx-select">
	<span class="fx-title t1 active">Зарегистрироваться как массажист</span>
	<span class="fx-title t2">Зарегистрироваться как клиент</span>
	<span class="fx-slider"><i class="left"></i></span>
	<input type="hidden" name="valuename">
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>