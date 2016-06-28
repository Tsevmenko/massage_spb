<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редактирование/добавление результатов анализа");
?><?$APPLICATION->IncludeComponent("medsite:iblock.element.add.form", "analysis_add", Array(
                                                                        "IBLOCK_TYPE"                   => "personal_card", // Тип инфо-блока
                                                                        "IBLOCK_ID"                     => "13", // Инфо-блок
                                                                        "LIST_URL_ALL"                  => "analysis_results.php", // Страница со списком элементов
                                                                        "ID"                            => $_REQUEST["ID"], // ID элемента
                                                                        "SECTION_ID"                    => "",
                                                                        "USER_GROUP"                    => array( // Группа пользователей для списка
                                                                            0 => "8",
                                                                        ),
                                                                        "STATUS_NEW"                    => "N", // Деактивировать элемент
                                                                        "LIST_URL"                      => "", // Страница со списком своих элементов
                                                                        "USE_CAPTCHA"                   => "N", // Использовать CAPTCHA
                                                                        "USER_MESSAGE_EDIT"             => "Данные успешно сохранены", // Сообщение об успешном сохранении
                                                                        "USER_MESSAGE_ADD"              => "", // Сообщение об успешном добавлении
                                                                        "DEFAULT_INPUT_SIZE"            => "30", // Размер полей ввода
                                                                        "RESIZE_IMAGES"                 => "N", // Использовать настройки инфоблока для обработки изображений
                                                                        "PROPERTY_CODES"                => array( // Свойства, выводимые на редактирование
                                                                            0 => "NAME",
                                                                            1 => "DETAIL_TEXT",
                                                                            2 => "28",
                                                                        ),
                                                                        "PROPERTY_CODES_REQUIRED"       => array( // Свойства, обязательные для заполнения
                                                                            0 => "NAME",
                                                                            1 => "DETAIL_TEXT",
                                                                            2 => "28",
                                                                        ),
                                                                        "GROUPS"                        => array( // Группы пользователей, имеющие право на добавление/редактирование
                                                                            0 => "1",
                                                                            1 => "3",
                                                                            2 => "4",
                                                                        ),
                                                                        "STATUS"                        => "ANY", // Редактирование возможно
                                                                        "ELEMENT_ASSOC"                 => "CREATED_BY", // Привязка к пользователю
                                                                        "MAX_USER_ENTRIES"              => "100000", // Ограничить кол-во элементов для одного пользователя
                                                                        "MAX_LEVELS"                    => "100000", // Ограничить кол-во рубрик, в которые можно добавлять элемент
                                                                        "LEVEL_LAST"                    => "Y", // Разрешить добавление только на последний уровень рубрикатора
                                                                        "MAX_FILE_SIZE"                 => "0", // Максимальный размер загружаемых файлов, байт (0 - не ограничивать)
                                                                        "PREVIEW_TEXT_USE_HTML_EDITOR"  => "N", // Использовать визуальный редактор для редактирования текста анонса
                                                                        "DETAIL_TEXT_USE_HTML_EDITOR"   => "Y", // Использовать визуальный редактор для редактирования подробного текста
                                                                        "SEF_MODE"                      => "N", // Включить поддержку ЧПУ
                                                                        "SEF_FOLDER"                    => "/personal/", // Каталог ЧПУ (относительно корня сайта)
                                                                        "CUSTOM_TITLE_NAME"             => "", // * наименование *
                                                                        "CUSTOM_TITLE_ACTIVE"           => "", // активность
                                                                        "CUSTOM_TITLE_SORT"             => "", // сортировка
                                                                        "CUSTOM_TITLE_TAGS"             => "", // * теги *
                                                                        "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "", // * дата начала *
                                                                        "CUSTOM_TITLE_DATE_ACTIVE_TO"   => "", // * дата завершения *
                                                                        "CUSTOM_TITLE_IBLOCK_SECTION"   => "", // * раздел инфоблока *
                                                                        "CUSTOM_TITLE_PREVIEW_TEXT"     => "", // * текст анонса *
                                                                        "CUSTOM_TITLE_PREVIEW_PICTURE"  => "", // * картинка анонса *
                                                                        "CUSTOM_TITLE_DETAIL_TEXT"      => "Результаты", // * подробный текст *
                                                                        "CUSTOM_TITLE_DETAIL_PICTURE"   => "", // * подробная картинка *
                                                                    ),
                                   false
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>