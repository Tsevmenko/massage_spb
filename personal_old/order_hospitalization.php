<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Очередь на госпитализацию");
?>
<?
global $USER;
if (!$USER->IsAuthorized()) {
    echo '<br>Для просмотра <a href="'.SITE_DIR.'personal/profile/?login=yes">авторизируйтесь</a> используя Ваши логин и пароль.';
}
else {
    $APPLICATION->IncludeComponent("medsite:news.line", "making_hospitalization", array(
                                                          "IBLOCK_TYPE"        => "registry",
                                                          "IBLOCKS"            => array(
                                                              0 => "10",
                                                          ),
                                                          "NEWS_COUNT"         => "",
                                                          "FIELD_CODE"         => array(
                                                              0 => "SORT",
                                                              1 => "DATE_ACTIVE_FROM",
                                                              2 => "",
                                                          ),
                                                          "SORT_BY1"           => "ACTIVE_FROM",
                                                          "SORT_ORDER1"        => "DESC",
                                                          "SORT_BY2"           => "SORT",
                                                          "SORT_ORDER2"        => "ASC",
                                                          "DETAIL_URL"         => "",
                                                          "CACHE_TYPE"         => "A",
                                                          "CACHE_TIME"         => "300",
                                                          "CACHE_GROUPS"       => "Y",
                                                          "ACTIVE_DATE_FORMAT" => "d.m.Y"
                                                      ),
                                   false
    );
}
?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>