# Блог о путешествиях
*Проект находится в процессе разработки*
____
## Цели проекта
Проект демонстрирует навыки backend-разработки на языке PHP. При разработке используется локальный веб-сервер XAMPP. Макет сайта создан при помощи bootstrap.
____
## Используемые технологии
PHP, SQL, HTML.
____
## Описание
### Структура базы данных
База данных на сервере включает в себя 2 таблицы: категории (регионы) и публикации. Таблицы сосстоят из следующих полей.
#### Категории (регионы)
+ ID категории
+ название категории
#### Публикации
+ ID публикации
+ категория публикации (ID категории)
+ название публикации
+ автор публикации
+ дата публикации
+ изображение
+ текст публикации
+ тэги
+ количество комментариев
+ статус публикации
### Структура главной страницы сайта
На главной странице сайта можно выделить 4 основных блока: верхнее меню, область публикаций, боковое меню, концевик.
#### Верхнее меню
Верхнее меню состоит из ссылок навигации по категориям сайта, а также ссылок на главную страницу и на раздел администратора (Управление). Во избежание переполнения области верхнего меню количество отображаемых категорий органичено. Нажатие на ссылку категории перенаправляет пользователя на страницу данной категории.
#### Область публикаций
В этой области последовательно отображены все публикации. Каждая публикация сопровождается следующей информацией: название, автор, дата публикации, изображение, текст. Для более компактного отображения информации количество видимых символов текста публикации ограничено. При нажатии на заголовок публикации, изображение или кнопку "Читать дальше" пользователь перенаправляется на страницу публикации.
#### Боковое меню
Боковое меню состоит из области поиска, ссылок навигации по категориям сайта и рекламного виджета. Назначение ссылок навигации по категориям аналогично подобным ссылкам в верхнем меню за исключением того, что количество отображаемых категорий не ограничено. Поиск осуществляется по полю "тэги" публикаций и перенаправляет пользователя на страницу результатов поиска.
#### Концевик
В этом разделе расположен копирайт.
### Другие страницы сайта
#### Страница категории
На этой странице отображаются все публикации, соответствующие выбранной категории.
#### Страница поиска
На этой странице отображаются все публикации, удовлетворяющие поисковому запросу. 
#### Страница публикации
На этой странице отображается полная информация о выбранной публикации, в том числе полный текст публикации. Под публикацией расположена область комментариев.
____
### Раздел администратора
Чтобы перейти в раздел администратора, нажмите ссылку "Управление" на верхнем меню. Главная страница раздела администратора состоит из верхнего и бокового меню и блока вывода информации.
В верхнем меню находятся следующие ссылки:
+ **"Управление сайтом"** - переход на главную страницу раздела администратора;
+ **"Перейти на сайт"** - переход на главную страницу сайта;
+ **"Профиль"**.

Боковое меню включает в себя следующие разделы:
+ **"Информация"**
+ **"Публикации"**
+ **"Комментарии"**
+ **"Регионы"** (категории сайта)
+ **"Пользователи"**
+ **"Профиль"**
#### Раздел "Публикации"
В этом разделе есть два подраздела: "Показать все публикации" и "Добавить публикацию". Первый подраздел перенаправляет пользователя на страницу с таблицей, содержащей информацию обо всех публикациях из базы данных. В конце каждой строки таблицы расположены иконки, позволяющие редактировать или удалить публикацию. При нажатии на иконку редактирования пользователь перенаправляется на страницу с формой, в которой можно отредактировать публикацию, а также загрузить новое изображение. Введенные данные проверяются на корректность, и в случае некорректных данных выводится сообщение об ошибке.

Раздел "Добавить публикацию" перенаправляет пользователя на страницу с формой для создания новой публикации. Форма включает в себя следующие поля:
+ название категории (региона) - необходимо выбрать из выпадающего списка
+ название публикации
+ автор
+ изображение - необходимо выбрать файл
+ текст публикации
+ тэги - поле можно оставить пустым
+ статус - поле можно оставить пустым, значение по умолчанию "черновик"

Введенные данные проходят проверку на корректность.
#### Раздел "Регионы"
В левой части раздела расположена форма для создания новой категории (региона). В правой части расположена таблица с информацией о всех категориях из базы данных.В конце каждой строки таблицы находятся иконки для редактирования и удаления категории. При нажатии на иконку редактирования категории в левой части раздела появляется форма редактирования категории. Все введенные данные проходят проверку на корректность.
____
## Используемые материалы
При разработке проекта использовались материалы из видеокурса PHP for Beginners - Become a PHP Master - CMS Project.
Ссылка на курс: [здесь](https://www.udemy.com/course/php-for-complete-beginners-includes-msql-object-oriented)
