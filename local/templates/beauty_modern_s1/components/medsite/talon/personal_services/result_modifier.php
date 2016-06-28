<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['MY_LAST_SERV'] = Array();
$arResult['MY_LAST_SERV_URL'] = Array();

function cmp($a, $b)
{
   // if ($a['SERVICE']['ID'] == $b['SERVICE']['ID']) {
	//	{
		if ($a['DATE'] == $b['DATE'])
				{
				if ($a['TIME_START'] == $b['TIME_START']) return 0;
				return ($a['TIME_START'] < $b['TIME_START']) ? -1 : 1;
				}
		return ($a['DATE'] > $b['DATE']) ? -1 : 1;
		//}   
   // }
   // return ($a['SERVICE']['ID'] < $b['SERVICE']['ID']) ? -1 : 1;
}

usort($arResult['talons'], "cmp");
foreach ($arResult['talons'] as $talon)
		{
		$arResult['MY_LAST_SERV'][$talon['SERVICE']['ID']] = $talon['ID'];
		}

?>