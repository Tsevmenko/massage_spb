<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Главная страница");
?>
    <br/>
    <!--Див под Рекомендации-->

    <div class="rightCol">
        <!--Див под Для клиента-->
        <div class="clearfix">
            <?
            $w_link = "/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardSiteID=".SITE_ID."&wizardName=bitrix:medsite_clear&".bitrix_sessid_get();
            if ($USER->IsAdmin() && COption::GetOptionString("main", "wizard_clear_exec", "N", SITE_ID) != "Y" && $APPLICATION->GetCurPage(true) == SITE_DIR."index.php") {
                ?>
                <div class="forDopMenu clearfix">
                    <div class="sidebar-block-inner">
                        <h4 class="title">Удаление демо-данных</h4>

                        <div class="sidebar-help-content">
                            <p><b>Внимание!</b> На сайте размещено демонстрационное информационное наполнение, не
                                предназначенное для публикации в сети Интернет. </p>

                            <p>Материалы предназначены исключительно для демонстрации возможностей сайта и являются
                                справочной информацией для подготовки уникальных текстов и иллюстраций.</p>
                            Для удаления демонстрационных данных с портала используйте
								<?='<a href="'.$w_link.'">Мастер очистки</a>.';?>
                        </div>
                    </div>
                </div>
            <?
            }
            ?>
            <div class="tizer">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                                 array(
                                                     "AREA_FILE_SHOW" => "file",
                                                     "PATH"           => "/includes/include_right.php"),
                                                 false);?>
            </div>
            <div class="tizer">
                <h3 class="title">Сегодня работают</h3>

                <div class="wdcontent">
                    <?$APPLICATION->IncludeComponent("medsite:working_today", ".default", array
                                                                            (
                                                                                "IBLOCK_TYPE"                     => "registry",
                                                                                "IBLOCK_ID"                       => "#SHEDULES_BLOCK_ID#",
                                                                                "PER_ID"                          => "#PERIODS_BLOCK_ID#",
                                                                                "SPEC_IB_ID"                      => "5",
                                                                                "PERSON_COUNT"                    => "5",
							'SCHEDULE_LINK' => '/schedule/record_wizard.php?STEP=service&SHOW=employee&EMPLOYEE=#ID#',
                                                                                "PERSON_LINK"                     => "/employees/personal_info.php",
                                                                                "STRUCTURE_LINK"                  => "/employees/index.php",
                                                                                "STRUCTURE_FILTER"                => "users",
                                                                                "SHOW_IN_RANDOM_ORDER"            => "Y",
                                                                                "DISPLAY_TOP_PAGER"               => "N",
                                                                                "DISPLAY_BOTTOM_PAGER"            => "N",
                                                                                "PAGER_SHOW_ALWAYS"               => "N",
                                                                                "PAGER_TEMPLATE"                  => "",
                                                                                "PAGER_DESC_NUMBERING"            => "N",
                                                                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                                                "PAGER_SHOW_ALL"                  => "N",
                                                                                "DISPLAY_DATE"                    => "Y",
                                                                                "DISPLAY_NAME"                    => "Y",
                                                                                "DISPLAY_PICTURE"                 => "Y",
                                                                                "DISPLAY_PREVIEW_TEXT"            => "Y"
                                                                            ),
                                                     false
                    );?>
                    <span class="btn btn-gray btn-small" style="background-position: left 0px;">
			<a href="/employees/index.php" style="background-position: right -110px;">Все специалисты</a>
		</span>
		<span class="btn2">
			<a href="/about/structure.php">Структура клиники</a>
		</span>
                </div>
            </div>

            <!--Див под информацию-->

            <div class="tizer">
                <h3 class="title">Информация</h3>

                <div class="wdcontent">
                    <div class="gdadv" style="text-align: justify; padding:0px 10px 10px 10px;">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                                         array(
                                                             "AREA_FILE_SHOW" => "file",
                                                             "PATH"           => "/includes/information.php"),
                                                         false);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="leftCol">

        <div class="forDopMenu clearfix">
            <h4 class="title">Наши направления</h4>
            <? require('dopmenu.php') ?>
        </div>
        <!--.forDopMenu-->
        <div class="tizer">
            <h3 class="title">Наши услуги</h3>

            <p>В нашей клинике вы можете получить профессиональное обслуживание на высоком уровне. Основными направления
                являются: </p>
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "main_service",
                Array(
                    "IBLOCK_TYPE"                     => 'medservices',
                    "IBLOCK_ID"                       => "8",
                    "NEWS_COUNT"                      => 5,
                    "SORT_BY1"                        => "ACTIVE_FROM",
                    "SORT_ORDER1"                     => "DESC",
                    "SORT_BY2"                        => "ID",
                    "SORT_ORDER2"                     => "DESC",
                    "FILTER_NAME"                     => "",
                    "FIELD_CODE"                      => array(0 => "", 1 => "", 2 => "",),
                    "PROPERTY_CODE"                   => array(0 => "", 1 => "", 2 => "",),
                    "DETAIL_URL"                      => '/schedule/record_wizard.php?STEP=service&SERVICE=#ELEMENT_ID#',
                    "AJAX_MODE"                       => "N",
                    "AJAX_OPTION_SHADOW"              => "N",
                    "AJAX_OPTION_JUMP"                => "N",
                    "AJAX_OPTION_STYLE"               => "N",
                    "AJAX_OPTION_HISTORY"             => "N",
                    "CACHE_TYPE"                      => $arGadgetParams["CACHE_TYPE"],
                    "CACHE_TIME"                      => $arGadgetParams["CACHE_TIME"],
                    "CACHE_FILTER"                    => "N",
                    "PREVIEW_TRUNCATE_LEN"            => "",
                    "ACTIVE_DATE_FORMAT"              => "d.m.Y",
                    "DISPLAY_PANEL"                   => "N",
                    "SET_TITLE"                       => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
                    "ADD_SECTIONS_CHAIN"              => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
                    "PARENT_SECTION"                  => "",
                    "DISPLAY_TOP_PAGER"               => "N",
                    "DISPLAY_BOTTOM_PAGER"            => "N",
                    "PAGER_TITLE"                     => "",
                    "PAGER_SHOW_ALWAYS"               => "N",
                    "PAGER_TEMPLATE"                  => "",
                    "PAGER_DESC_NUMBERING"            => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "DISPLAY_DATE"                    => "N",
                    "DISPLAY_NAME"                    => "Y",
                    "DISPLAY_PICTURE"                 => "N",
                    "DISPLAY_PREVIEW_TEXT"            => "N",
                    "INTRANET_TOOLBAR"                => "N",
                    "bxpiwidth"                       => "693"
                ),
                false
            );?>
            <div class="btn btn-gray btn-small leftStep" style="background-position: left 0px;">
                <a href="/services/list/" style="background-position: right -110px;">Все наши
                    услуги</a>
            </div>
        </div>

        <div class="recomend clearfix">
            <h4 class="title med">Рекомендации врачей</h4>

            <div class="wheader-underline"></div>
            <div class="wdcontent">
                <?$APPLICATION->IncludeComponent("bitrix:news.list", "table", Array(
                                                                       "IBLOCK_TYPE"                     => "news", // Тип информационного блока (используется только для проверки)
                                                                       "IBLOCK_ID"                       => "3", // Код информационного блока
                                                                       "NEWS_COUNT"                      => "5", // Количество новостей на странице
                                                                       "SORT_BY1"                        => "ACTIVE_FROM", // Поле для первой сортировки новостей
                                                                       "SORT_ORDER1"                     => "DESC", // Направление для первой сортировки новостей
                                                                       "SORT_BY2"                        => "SORT", // Поле для второй сортировки новостей
                                                                       "SORT_ORDER2"                     => "ASC", // Направление для второй сортировки новостей
                                                                       "FILTER_NAME"                     => "", // Фильтр
                                                                       "FIELD_CODE"                      => array( // Поля
                                                                           0 => "",
                                                                           1 => "",
                                                                       ),
                                                                       "PROPERTY_CODE"                   => array( // Свойства
                                                                           0 => "",
                                                                           1 => "",
                                                                       ),
                                                                       "CHECK_DATES"                     => "Y", // Показывать только активные на данный момент элементы
                                                                       "DETAIL_URL"                      => "/services/recomendations/#ELEMENT_ID#/", // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
                                                                       "AJAX_MODE"                       => "N", // Включить режим AJAX
                                                                       "AJAX_OPTION_SHADOW"              => "Y", // Включить затенение
                                                                       "AJAX_OPTION_JUMP"                => "N", // Включить прокрутку к началу компонента
                                                                       "AJAX_OPTION_STYLE"               => "Y", // Включить подгрузку стилей
                                                                       "AJAX_OPTION_HISTORY"             => "N", // Включить эмуляцию навигации браузера
                                                                       "CACHE_TYPE"                      => "A", // Тип кеширования
                                                                       "CACHE_TIME"                      => "3600", // Время кеширования (сек.)
                                                                       "CACHE_FILTER"                    => "N", // Кешировать при установленном фильтре
                                                                       "CACHE_GROUPS"                    => "Y", // Учитывать права доступа
                                                                       "PREVIEW_TRUNCATE_LEN"            => "", // Максимальная длина анонса для вывода (только для типа текст)
                                                                       "ACTIVE_DATE_FORMAT"              => "d.m.Y", // Формат показа даты
                                                                       "SET_TITLE"                       => "N", // Устанавливать заголовок страницы
                                                                       "SET_STATUS_404"                  => "N", // Устанавливать статус 404, если не найдены элемент или раздел
                                                                       "INCLUDE_IBLOCK_INTO_CHAIN"       => "N", // Включать инфоблок в цепочку навигации
                                                                       "ADD_SECTIONS_CHAIN"              => "N", // Включать раздел в цепочку навигации
                                                                       "HIDE_LINK_WHEN_NO_DETAIL"        => "N", // Скрывать ссылку, если нет детального описания
                                                                       "PARENT_SECTION"                  => "", // ID раздела
                                                                       "PARENT_SECTION_CODE"             => "", // Код раздела
                                                                       "DISPLAY_TOP_PAGER"               => "N", // Выводить над списком
                                                                       "DISPLAY_BOTTOM_PAGER"            => "N", // Выводить под списком
                                                                       "PAGER_TITLE"                     => "Новости", // Название категорий
                                                                       "PAGER_SHOW_ALWAYS"               => "N", // Выводить всегда
                                                                       "PAGER_TEMPLATE"                  => "", // Название шаблона
                                                                       "PAGER_DESC_NUMBERING"            => "N", // Использовать обратную навигацию
                                                                       "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000", // Время кеширования страниц для обратной навигации
                                                                       "PAGER_SHOW_ALL"                  => "Y", // Показывать ссылку "Все"
                                                                       "DISPLAY_DATE"                    => "Y", // Выводить дату элемента
                                                                       "DISPLAY_NAME"                    => "Y", // Выводить название элемента
                                                                       "DISPLAY_PICTURE"                 => "Y", // Выводить изображение для анонса
                                                                       "DISPLAY_PREVIEW_TEXT"            => "Y", // Выводить текст анонса
                                                                       "AJAX_OPTION_ADDITIONAL"          => "", // Дополнительный идентификатор
                                                                   ),
                                                 false
                );?>
                <div class="btn btn-gray btn-small leftStep" style="background-position: left 0px;">
                    <a href="/services/recomendations/" style="background-position: right -110px;">Все
                        рекомендации</a>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="razdelitel"></div>-->
    <div class="centerCol">
        <div class="middle_block_content">

            <?$APPLICATION->IncludeComponent("bitrix:news.list", "main_slider", array(
                                                                   "IBLOCK_TYPE"                     => "additional_info",
                                                                   "IBLOCK_ID"                       => "17",
                                                                   "NEWS_COUNT"                      => "10",
                                                                   "SORT_BY1"                        => "SORT",
                                                                   "SORT_ORDER1"                     => "ASC",
                                                                   "SORT_BY2"                        => "ID",
                                                                   "SORT_ORDER2"                     => "ASC",
                                                                   "FILTER_NAME"                     => "",
                                                                   "FIELD_CODE"                      => array(
                                                                       0 => "",
                                                                       1 => "",
                                                                   ),
                                                                   "PROPERTY_CODE"                   => array(
                                                                       0 => "",
                                                                       1 => "REAL_PICTURE",
                                                                   ),
                                                                   "CHECK_DATES"                     => "Y",
                                                                   "DETAIL_URL"                      => "",
                                                                   "AJAX_MODE"                       => "N",
                                                                   "AJAX_OPTION_JUMP"                => "N",
                                                                   "AJAX_OPTION_STYLE"               => "N",
                                                                   "AJAX_OPTION_HISTORY"             => "N",
                                                                   "CACHE_TYPE"                      => "A",
                                                                   "CACHE_TIME"                      => "36000000",
                                                                   "CACHE_FILTER"                    => "N",
                                                                   "CACHE_GROUPS"                    => "Y",
                                                                   "PREVIEW_TRUNCATE_LEN"            => "",
                                                                   "ACTIVE_DATE_FORMAT"              => "d.m.Y",
                                                                   "SET_TITLE"                       => "N",
                                                                   "SET_STATUS_404"                  => "N",
                                                                   "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
                                                                   "ADD_SECTIONS_CHAIN"              => "N",
                                                                   "HIDE_LINK_WHEN_NO_DETAIL"        => "Y",
                                                                   "PARENT_SECTION"                  => "",
                                                                   "PARENT_SECTION_CODE"             => "main_slider",
                                                                   "DISPLAY_TOP_PAGER"               => "N",
                                                                   "DISPLAY_BOTTOM_PAGER"            => "N",
                                                                   "PAGER_TITLE"                     => "Новости",
                                                                   "PAGER_SHOW_ALWAYS"               => "N",
                                                                   "PAGER_TEMPLATE"                  => "",
                                                                   "PAGER_DESC_NUMBERING"            => "N",
                                                                   "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                                   "PAGER_SHOW_ALL"                  => "N",
                                                                   "DISPLAY_DATE"                    => "N",
                                                                   "DISPLAY_NAME"                    => "Y",
                                                                   "DISPLAY_PICTURE"                 => "Y",
                                                                   "DISPLAY_PREVIEW_TEXT"            => "Y",
                                                                   "AJAX_OPTION_ADDITIONAL"          => ""
                                                               ),
                                             false
            );?>


            <!--Див под приветствие-->

            <div class="tizer">
                <h3>Добро пожаловать в нашу клинику</h3>

                <div class="wdcontent">
                    <div class="gdadv" style="text-align: justify; padding:0px 10px 10px 10px;">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                                         array(
                                                             "AREA_FILE_SHOW" => "file",
                                                             "PATH"           => SITE_DIR."includes/salutation.php"),
                                                         false);?>
                    </div>
                </div>
            </div>

            <div class="advert">
                <h3>Объявления<a href="/about/advert/rss/" class="rss">Rss-лента</a></h3>

                <div class="wdcontent">
                    <?$APPLICATION->IncludeComponent("bitrix:news.list", "main_advt", Array(
                                                                           "IBLOCK_TYPE"                     => "news",
                                                                           "IBLOCK_ID"                       => "2",
                                                                           "NEWS_COUNT"                      => 5,
                                                                           "SORT_BY1"                        => "ACTIVE_FROM",
                                                                           "SORT_ORDER1"                     => "DESC",
                                                                           "SORT_BY2"                        => "ID",
                                                                           "SORT_ORDER2"                     => "DESC",
                                                                           "FILTER_NAME"                     => "",
                                                                           "FIELD_CODE"                      => array(
                                                                               0 => "",
                                                                           ),
                                                                           "PROPERTY_CODE"                   => array(
                                                                               1 => "",
                                                                           ),
                                                                           "DETAIL_URL"                      => "/about/advert/#ELEMENT_ID#/",
                                                                           "AJAX_MODE"                       => "N",
                                                                           "AJAX_OPTION_SHADOW"              => "Y",
                                                                           "AJAX_OPTION_JUMP"                => "N",
                                                                           "AJAX_OPTION_STYLE"               => "Y",
                                                                           "AJAX_OPTION_HISTORY"             => "N",
                                                                           "CACHE_TYPE"                      => "A",
                                                                           "CACHE_TIME"                      => "3600",
                                                                           "CACHE_FILTER"                    => "N",
                                                                           "PREVIEW_TRUNCATE_LEN"            => "",
                                                                           "ACTIVE_DATE_FORMAT"              => "d.m.Y",
                                                                           "DISPLAY_PANEL"                   => "N",
                                                                           "SET_TITLE"                       => "N",
                                                                           "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
                                                                           "ADD_SECTIONS_CHAIN"              => "N",
                                                                           "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
                                                                           "PARENT_SECTION"                  => "",
                                                                           "DISPLAY_TOP_PAGER"               => "N",
                                                                           "DISPLAY_BOTTOM_PAGER"            => "N",
                                                                           "PAGER_TITLE"                     => "",
                                                                           "PAGER_SHOW_ALWAYS"               => "N",
                                                                           "PAGER_TEMPLATE"                  => "",
                                                                           "PAGER_DESC_NUMBERING"            => "N",
                                                                           "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                                           "DISPLAY_DATE"                    => "Y",
                                                                           "DISPLAY_NAME"                    => "Y",
                                                                           "DISPLAY_PICTURE"                 => "Y",
                                                                           "DISPLAY_PREVIEW_TEXT"            => "Y",
                                                                           "INTRANET_TOOLBAR"                => "N",
                                                                       ),
                                                     false
                    );?>
                    <div class="btn btn-gray btn-small leftStep" style="background-position: left 0px;">
                        <a href="/about/advert/" style="background-position: right -110px;">Все
                            объявления</a>
                    </div>
                </div>
            </div>

            <!--Див под Новости медицины-->

            <div class="tizer newsAnno">
                <h3 class="title">Новости медицины<a href="/about/news/rss/" class="rss">Rss-лента</a></h3>

                <div class="wdcontent">

                    <?$APPLICATION->IncludeComponent("bitrix:news.list", "main_news", Array(
                                                                           "IBLOCK_TYPE"                     => "news", // Тип информационного блока (используется только для проверки)
                                                                           "IBLOCK_ID"                       => "1", // Код информационного блока
                                                                           "NEWS_COUNT"                      => "5", // Количество новостей на странице
                                                                           "SORT_BY1"                        => "ACTIVE_FROM", // Поле для первой сортировки новостей
                                                                           "SORT_ORDER1"                     => "DESC", // Направление для первой сортировки новостей
                                                                           "SORT_BY2"                        => "SORT", // Поле для второй сортировки новостей
                                                                           "SORT_ORDER2"                     => "ASC", // Направление для второй сортировки новостей
                                                                           "FILTER_NAME"                     => "", // Фильтр
                                                                           "FIELD_CODE"                      => array( // Поля
                                                                               0 => "",
                                                                               1 => "",
                                                                           ),
                                                                           "PROPERTY_CODE"                   => array( // Свойства
                                                                               0 => "",
                                                                               1 => "",
                                                                           ),
                                                                           "CHECK_DATES"                     => "Y", // Показывать только активные на данный момент элементы
                                                                           "DETAIL_URL"                      => "/about/news/#ELEMENT_ID#/", // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
                                                                           "AJAX_MODE"                       => "N", // Включить режим AJAX
                                                                           "AJAX_OPTION_SHADOW"              => "Y", // Включить затенение
                                                                           "AJAX_OPTION_JUMP"                => "N", // Включить прокрутку к началу компонента
                                                                           "AJAX_OPTION_STYLE"               => "Y", // Включить подгрузку стилей
                                                                           "AJAX_OPTION_HISTORY"             => "N", // Включить эмуляцию навигации браузера
                                                                           "CACHE_TYPE"                      => "A", // Тип кеширования
                                                                           "CACHE_TIME"                      => "3600", // Время кеширования (сек.)
                                                                           "CACHE_FILTER"                    => "N", // Кешировать при установленном фильтре
                                                                           "CACHE_GROUPS"                    => "Y", // Учитывать права доступа
                                                                           "PREVIEW_TRUNCATE_LEN"            => "", // Максимальная длина анонса для вывода (только для типа текст)
                                                                           "ACTIVE_DATE_FORMAT"              => "d.m.Y", // Формат показа даты
                                                                           "SET_TITLE"                       => "N", // Устанавливать заголовок страницы
                                                                           "SET_STATUS_404"                  => "N", // Устанавливать статус 404, если не найдены элемент или раздел
                                                                           "INCLUDE_IBLOCK_INTO_CHAIN"       => "N", // Включать инфоблок в цепочку навигации
                                                                           "ADD_SECTIONS_CHAIN"              => "Y", // Включать раздел в цепочку навигации
                                                                           "HIDE_LINK_WHEN_NO_DETAIL"        => "N", // Скрывать ссылку, если нет детального описания
                                                                           "PARENT_SECTION"                  => "", // ID раздела
                                                                           "PARENT_SECTION_CODE"             => "", // Код раздела
                                                                           "DISPLAY_TOP_PAGER"               => "N", // Выводить над списком
                                                                           "DISPLAY_BOTTOM_PAGER"            => "N", // Выводить под списком
                                                                           "PAGER_TITLE"                     => "Новости", // Название категорий
                                                                           "PAGER_SHOW_ALWAYS"               => "N", // Выводить всегда
                                                                           "PAGER_TEMPLATE"                  => "", // Название шаблона
                                                                           "PAGER_DESC_NUMBERING"            => "N", // Использовать обратную навигацию
                                                                           "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000", // Время кеширования страниц для обратной навигации
                                                                           "PAGER_SHOW_ALL"                  => "Y", // Показывать ссылку "Все"
                                                                           "DISPLAY_DATE"                    => "Y", // Выводить дату элемента
                                                                           "DISPLAY_NAME"                    => "Y", // Выводить название элемента
                                                                           "DISPLAY_PICTURE"                 => "Y", // Выводить изображение для анонса
                                                                           "DISPLAY_PREVIEW_TEXT"            => "Y", // Выводить текст анонса
                                                                           "AJAX_OPTION_ADDITIONAL"          => "", // Дополнительный идентификатор
                                                                       ),
                                                     false
                    );?>
                    <div class="btn btn-gray btn-small leftStep" style="background-position: left 0px;">
                        <a href="/about/news/" style="background-position: right -110px;">Все новости</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="razdelitel"></div>-->
    <div class="clear"></div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>