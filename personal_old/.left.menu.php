<?
$aMenuLinks = Array(
    Array(
        "Данные пациента",
        "/personal/profile/",
        Array(),
        Array(),
        "CUSER::IsAuthorized()"
    ),
    Array(
        "Мои талоны",
        "/personal/stamps.php",
        Array(),
        Array(),
        ""
    ),
	Array(
		"Мои обращения",
		"/personal/requests/",
		Array(),
		Array(),
		"CUSER::IsAuthorized()"
	),
	Array(
		"Корзина услуг",
		"/personal/cart/",
		Array(),
		Array(),
		"IsModuleInstalled('sale')"
	),

);
?>