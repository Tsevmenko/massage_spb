<?
header("Content-Type: application/x-javascript");
$hash = "bitrix.sitemedicine";
$config = array("appmap" =>
	array("main" => "/mobile_app/",
		"left" => "/mobile_app/menu.php",
		"right" => "/mobile_app/right.php",
		"settings" => "/mobile_app/settings.php",
		"hash" => substr($hash, rand(1, strlen($hash)))
	)
);
echo json_encode($config);