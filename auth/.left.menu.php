<?
$aMenuLinks = Array(
    Array(
        "Авторизация",
        "/auth/index.php?login=yes",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Регистрация",
		"/registration/",
        Array(),
        Array(),
		""//"COption::GetOptionString(\"main\", \"new_user_registration\") == \"Y\""
    ),
    Array(
        "Забыл пароль",
        "/auth/index.php?forgot_password=yes",
        Array(),
        Array(),
        ""
    )
);
?>