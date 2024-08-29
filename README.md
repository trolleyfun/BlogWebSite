# Блог о путешествиях
*Проект находится в процессе разработки*
____
## Цели проекта
Проект демонстрирует навыки backend-разработки на языке PHP. Сайт разрабатывается на локальном веб-сервере XAMPP, для работы с базой данных используется phpMyAdmin. Макет сайта создан при помощи bootstrap.
____
## Используемые технологии
PHP, SQL, HTML, Javascript.
____
## Внешние модули
В проекте используются следующие внешние модули: [диаграммы Google Charts](https://developers.google.com/chart), [визуальный текстовый редактор Summernote](https://summernote.org/). Для работы Google Charts необходимо подключение к сети интернет.
____
## Запуск проекта
Для запуска проекта необходимы сервер с поддержкой языка PHP и база данных MySQL.
1. Разместите файлы проекта на своем сервере. По умолчанию параметры сервера и базы данных имеют следующие значения:
    + адрес сервера &ndash; localhost
    + имя пользователя &ndash; admin
    + пароль &ndash; "" (пустая строка)
    + название базы данных &ndash; BlogWebSite
   
   Если вы хотите использовать другие значения, вы можете изменить их в файле includes/db.php;
2. Инициализируйте базу данных при помощи скрипта db/db_script.sql. Скрипт создаст необходимые таблицы в базе данных и наполнит их демонстрационными данными;
3. Для авторизации на сайте можете использовать следующие параметры:
    + логин: admin
    + пароль: adminadmin
____
## Описание
### Структура базы данных
База данных на сервере включает в себя 5 таблиц: категории (регионы), публикации, комментарии, пользователи и сессии.

![Схема базы данных](/db/db_scheme.png)
#### Публикации
+ первичный ключ: **post_id**
+ внешние ключи: **post_category_id**, **post_author_id**
#### Комментарии
+ первичный ключ: **comment_id**
+ внешние ключи: **comment_post_id**, **comment_user_id**
#### Категории (регионы)
+ первичный ключ: **cat_id**
+ столбцы со свойством UNIQUE: **cat_title**
#### Сессии
+ первичный ключ: **record_id**
+ внешние ключи: **session_user_id**
+ столбцы со свойством UNIQUE: **session_id**
#### Пользователи
+ первичный ключ: **user_id**
+ столбцы со свойством UNIQUE: **user_login**, **user_email**
____
### Структура главной страницы сайта
На главной странице сайта можно выделить 4 основных блока: верхнее меню, область публикаций, боковое меню, концевик.
#### Верхнее меню
В левой части верхнего меню расположены ссылки навигации по категориям сайта, а также ссылка на главную страницу. Категории отображаются в порядке убывания по количеству публикаций в этой категории. Во избежание переполнения области верхнего меню количество отображаемых категорий органичено. Нажатие на ссылку категории перенаправляет пользователя на страницу данной категории.

В правой части верхнего меню находятся ссылки на страницу регистрации новых пользователей и на страницу авторизации. После авторизации вместо них появляются ссылка на раздел администратора и меню управления профилем. При нажатии на меню профиля появлется выпадающее меню, состоящее из трех пунктов:
+ **"Добавить публикацию"**
+ **"Профиль"** &ndash; позволяет отредактировать данные авторизовавшегося пользователя;
+ **"Выйти"** &ndash; завершает сеанс текущего пользователя.
#### Область публикаций
В этой области последовательно отображены все публикации. Каждая публикация сопровождается следующей информацией: название, автор, дата публикации, изображение, текст. Для более компактного отображения информации количество видимых символов текста публикации ограничено. При нажатии на заголовок публикации, изображение или кнопку "Читать дальше" пользователь перенаправляется на страницу публикации.
#### Боковое меню
Боковое меню состоит из области поиска, ссылок навигации по категориям сайта (они отсортированы в порядке убывания по количеству публикаций) и рекламного виджета. Назначение ссылок навигации по категориям аналогично подобным ссылкам в верхнем меню за исключением того, что количество отображаемых категорий не ограничено. Поиск осуществляется по полю "тэги" публикаций и перенаправляет пользователя на страницу результатов поиска.
#### Концевик
В этом разделе расположен копирайт.
### Другие страницы сайта
#### Страница регистрации
На этой странице находится форма регистрации новых пользователей. Она состоит из следующих полей:
+ логин
+ пароль
+ имя
+ фамилия
+ e-mail
+ изображение &ndash; необходимо выбрать файл

Требования к введенным данным:
+ поля не могут быть пустыми;
+ пароль должен содержать не менее 8 символов;
+ поле **"e-mail"** должно содержать корректный e-mail;
+ логин и e-mail не должны совпадать с соответствующими данными ранее зарегистрировавшихся пользователей;
+ допускается не загружать изображение.

На сайте существует три типа учетных записей пользователей: "пользователь", "модератор", "администратор". Тип учетной записи нового пользователя по умолчанию "пользователь".
#### Страница авторизации
На этой странице расположена форма авторизации пользователей. Необходимо ввести логин и пароль.
#### Страница категории
На этой странице отображаются все публикации, соответствующие выбранной категории.
#### Страница поиска
На этой странице отображаются все публикации, удовлетворяющие поисковому запросу. 
#### Страница публикации
На этой странице отображается полная информация о выбранной публикации, в том числе полный текст публикации. Под публикацией расположена кнопка, перенаправляющая пользователя на страницу редактирования публикации. Ниже расположена область комментариев.
#### Область комментариев
Этот раздел состоит из формы для создания нового комментария и комментариев к текущей записи.

Форма для создания нового комментария доступна только для авторизованных пользователей (неавторизованные пользователи не могут оставлять комментарии). Комментарии, являющиеся пустой строкой, не допускаются.

У комментария могут быть следующие статусы: "одобрен", "заблокирован". По умолчанию статус нового комментария &ndash; "одобрен".

Ниже располагаются комментарии к публикации. Каждый отображаемый комментарий сопровождается следующей информацией: имя пользователя, оставившего комментарий, изображение пользователя, дата и текст комментария. Сначала отображаются самые последние комментарии.
#### Отображение публикаций и комментариев в зависимости от статуса
На сайте отображаются только публикации, статус которых "опубликовано", и комментарии, статус которых "одобрен". Остальные публикации и комментарии хранятся в базе данных, но на сайте не отображаются. Их можно посмотреть в разделе администратора.
____
### Раздел администратора
Этот раздел доступен только для авторизованных пользователей. Чтобы перейти в раздел администратора, нажмите ссылку "Раздел администратора" на верхнем меню. Главная страница раздела администратора состоит из верхнего и бокового меню и блока вывода информации.
В верхнем меню находятся следующие ссылки:
+ **"Управление сайтом"** &ndash; переход на главную страницу раздела администратора;
+ **"Перейти на сайт"** &ndash; переход на главную страницу сайта;
+ **"Профиль"** (указан логин авторизовавшегося пользователя) &ndash; появляется выпадающее меню, состоящее из пунктов: 
    + **"Добавить публикацию"**
    + **"Профиль"** &ndash; перенаправляет пользователя на страницу редактирования данных авторизовавшегося пользователя;
    + **"Выйти"** &ndash; завершает сеанс текущего пользователя.

Боковое меню включает в себя следующие разделы:
+ **"Статистика"**
+ **"Публикации"**
+ **"Комментарии"**
+ **"Регионы"** (категории сайта)
+ **"Пользователи"**
+ **"Профиль"**
#### Раздел "Статистика"
На странице этого раздела представлены различные статистические данные о сайте:
+ количество посетителей на сайте в текущий момент (учитываются как авторизовавшиеся, так и неавторизовавшиеся пользователи; пользователь считается активным, если совершал какие-либо действия на сайте в течение последних 5 минут);
+ количество публикаций, комментариев, категорий и пользователей (учитываются только публикации со статусом "опубликовано" и комментарии со статусом "одобрен");
+ диаграмма "Популярные категории" &ndash; показаны категории с наибольшим количеством публикаций;
+ диаграмма "Самые читаемые публикации" &ndash; показаны публикации с наибольшим количеством комментариев;
+ диаграмма "Самые активные пользователи" &ndash; показаны пользователи, у которых больше всего публикаций и комментариев.
#### Раздел "Публикации"
В этом разделе представлена таблица, содержащая информацию обо всех публикациях из базы данных. В конце каждой строки таблицы расположены иконки, позволяющие редактировать или удалить публикацию. При нажатии на иконку редактирования пользователь перенаправляется на страницу с формой, в которой можно отредактировать публикацию, а также загрузить новое изображение. При нажатии на название региона или название публикации открывается соответствующая страница сайта. 

Выше и ниже таблицы расположена форма, позволяющая изменить статус публикации или удалить её. Эти опции можно применить сразу к нескольким публикациям, отметив "галочками" соответствующие строки в таблице.

Также над таблицей расположена кнопка перенаправляющая пользователя на страницу с формой для создания новой публикации. Форма включает в себя следующие поля:
+ название категории (региона) &ndash; необходимо выбрать из выпадающего списка
+ название публикации
+ изображение &ndash; необходимо выбрать файл
+ текст публикации
+ тэги &ndash; поле можно оставить пустым

Статус публикации может принимать следующие значения: "ожидает проверки", "опубликовано", "заблокировано". По умолчанию статус новой публикации &ndash; "ожидает проверки".

Требования к введенным данным:
+ все поля, кроме **"тэги"** не могут быть пустыми;
+ категория (регион) должна соответствовать элементу из таблицы **"категории"** базы данных;
+ допускается не загружать изображение.
#### Раздел "Комментарии"
В этом разделе находится таблица с информацией обо всех комментариях. Таблица отсортирована: сверху находятся самые последние комментарии. В конце каждой строки таблицы расположены ссылки "разрешить" и "отклонить", а также иконка удаления комментария. При нажатии на ссылки "разрешить" и "отклонить" статус комментария меняется на "одобрен" и "заблокирован" соответственно. При нажатии на иконку удаления комментария комментарий удаляется из базы данных. Если кликнуть по названию публикации, к которой относится комментарий, откроется страница этой публикации.

Выше и ниже таблицы расположена форма, позволяющая изменить статус комментария или удалить его. Эти опции можно применить сразу к нескольким комментариям, отметив "галочками" соответствующие строки в таблице.
#### Раздел "Регионы"
В верхней части раздела расположена форма для создания новой категории (региона).

Ниже представлена таблица с информацией о всех категориях из базы данных. В конце каждой строки таблицы находятся иконки для редактирования и удаления категории. При нажатии на иконку редактирования категории в левой части раздела появляется форма редактирования категории.

Над таблицей и под таблицей расположены кнопки удаления регионов. Удалить можно сразу несколько регионов, отметив "галочками" соответствующие строки таблицы.
#### Раздел "Пользователи"
В этом разделе представлена таблица с информацией обо всех пользователях, хранящихся в базе данных. В конце каждой строки расположены иконки редактирования и удаления пользователя. При нажатии на иконку редактирования открывается страница с формой, где можно изменить данные пользователя (кроме логина), сбросить пароль и установить новый, а также загрузить новое изображение. При нажатии на иконку удаления пользователь удаляется из базы данных.

Выше и ниже таблицы расположена форма, позволяющая изменить права доступа пользователя или удалить его. Эти опции можно применить сразу к нескольким пользователям, отметив "галочками" соответствующие строки в таблице.

Также над таблицей расположена кнопка "Добавить пользователя", которая перенаправляет пользователя на страницу с формой для создания нового пользователя. Форма включает следующие поля:
+ логин
+ пароль
+ имя
+ фамилия
+ e-mail
+ изображение &ndash; необходимо выбрать файл
+ права доступа &ndash; необходимо выбрать из выпадающего списка

Требования к введенным данным:
+ поля не могут быть пустыми;
+ пароль должен содержать не менее 8 символов;
+ поле **"e-mail"** должно содержать корректный e-mail;
+ логин и e-mail не должны совпадать с соответствующими данными ранее зарегистрировавшихся пользователей;
+ поле **"права доступа"** должно принимать одно из трех значений: "пользователь", "модератор", "администратор";
+ допускается не загружать изображение.
#### Раздел "Профиль"
В этом разделе пользователь может отредактировать свои данные, а также загрузить новое изображение. Для редактирования доступны следующие поля:
+ имя
+ фамилия
+ e-mail
+ изображение
+ права доступа

В правой части страницы расположена форма смены пароля. Необходимо ввести старый пароль и придумать новый.

Требования к введенным данным такие же, как и при добавлении нового пользователя.
#### Количество публикаций и комментариев
При подсчете количества публикаций и комментариев (соответствующие столбцы в таблицах базы данных) учитываются только публикации со статусом "опубликовано" и комментарии со статусом "одобрен".
____
### Отображение контента
При отображении контента на вебсайте используется разделение на страницы. Т.е. на одной странице сайта отображается ограниченное количество элементов. Чтобы перейти к следующим элементам, необходимо перейти на следующую страницу.
____
### Корректность данных
Все данные, введенные пользователями, проверяются на корректность на стороне браузера и на стороне сервера. В случае некорректных данных выводится сообщение об ошибке. Если все данные корректны, выводится сообщение об успешном выполнении операции (добавление, редактирование, удаление, регистрация).
____
### Подтверждение операций
При удалении объектов (публикации, комментарии, категории, пользователи), а также при завершении сеанса появляется всплывающее окно, в котором необходимо подтвердить выполнение операции.
____
## Безопасность
+ для предотвращения SQL-инъекций во всех входных данных производится экранирование специальных символов;
+ чтобы избежать ошибок при запросах к базе данных все входные данные проходят проверку на корректность;
+ к паролям, хранящимся в базе данных, применяется шифрование по алгоритму **Blowfish**;
+ доступ к разделу администратора и к добавлению комментариев открыт только для авторизованных пользователей;
+ в дальнейшем планируется ограничить права доступа пользователей в разделе администратора (в зависимости от типа их учетных записей).
____
## Загрузка и обновление данных
Информация о количестве посетителей сайта на главной странице раздела администратора обновляется каждые 1 с при помощи средств **Javascript**.

Все остальные данные загружаются средствами **PHP** и обновляются только при повторной загрузке страницы с сервера.
____
## Используемые материалы
При разработке проекта использовались материалы из видеокурса **PHP for Beginners - Become a PHP Master - CMS Project**.
Ссылка на курс: [здесь](https://www.udemy.com/course/php-for-complete-beginners-includes-msql-object-oriented)
