<?
global $APPLICATION;
$aMenuLinks = Array(
    Array(
        "Кабинет",
        "/m/personal/",
        Array(),
        Array("class"=>"personl-kabinet"),
        ""
    ),
    Array(
		"Личные данные",
		"/m/personal/profile/",
		Array(), 
		Array("class"=>"personl-aboutme"),
		"" 
	),
	Array(
		"Талоны",
		"/m/personal/stamps/",
		Array(), 
		Array("class"=>"personl-talons"),
		"" 
	),
	 Array(
        "Мои услуги",
        "/m/personal/services/",
        Array(),
        Array("class"=>"personl-services"),
        ""
    ),
	Array(
        "Мои обращения",
        "/m/personal/requests/",
        Array(),
        Array("class"=>"personl-requests"),
        ""
    ),
	Array(
		"Выход",
		$APPLICATION->GetCurPageParam('logout=yes',array('logout')),
		Array(),
		Array("class"=>"personl-requests"),
		""
	)
);
?>

						