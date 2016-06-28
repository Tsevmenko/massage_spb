<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<? if (file_exists($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'services/our_destinations/.left.menu.php')): ?>
    <? require($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'services/our_destinations/.left.menu.php') ?>
    <ul class="DopMenu">
        <? $tmp = $aMenuLinks;
        unset ($aMenuLinks) ?>
        <? foreach ($tmp as $Parent): ?>
            <li>
                <a class="fst" href="<?= $Parent[1] ?>"><?= $Parent[0] ?></a>
                <? $menu = $_SERVER['DOCUMENT_ROOT'].$Parent[1].'.left_sub.menu.php' ?>
                <? if (file_exists($menu)): ?>
                    <? require($menu) ?>
                    <ul>
                        <? $tmp2 = $aMenuLinks;
                        unset ($aMenuLinks) ?>
                        <? foreach ($tmp2 as $child): ?>
                            <li>
                                <a href="<?= $child[1] ?>"><?= $child[0] ?></a>
                            </li>
                        <? endforeach ?>
                    </ul>
                <? endif ?>
            </li>
        <? endforeach ?>
    </ul>
<? endif ?>