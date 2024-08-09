# Блог о путешествиях
*Проект находится в процессе разработки*
____
## Цели проекта
Проект демонстрирует навыки backend-разработки на языке PHP. Сайт разрабатывается на локальном веб-сервере XAMPP, для работы с базой данных используется phpMyAdmin. Макет сайта создан при помощи bootstrap.
____
## Используемые технологии
PHP, SQL, HTML, Javascript.
____
## Описание
### Структура базы данных
База данных на сервере включает в себя 4 таблицы: категории (регионы), публикации, комментарии и пользователи. Таблицы состоят из следующих полей.
#### Категории (регионы)
+ ID категории
+ название категории
+ количество публикаций в категории (учитыватся только публикации со статусом "опубликовано")
#### Публикации
+ ID публикации
+ категория публикации (ID категории)
+ название публикации
+ автор публикации
+ дата публикации
+ изображение
+ текст публикации
+ тэги
+ количество комментариев к публикации (учитываются только комментарии со статусом "одобрен")
+ статус публикации ("черновик" или "опубликовано")
#### Комментарии
+ ID комментария
+ публикация, к которой относится комментарий (ID публикации)
+ имя пользователя, оставившего комментарий
+ дата комментария
+ текст комментария
+ e-mail пользователя, оставившего комментарий
+ статус комментария ("ожидает проверки", "одобрен" или "заблокирован")
#### Пользователи
+ ID пользователя
+ логин
+ пароль
+ имя
+ фамилия
+ e-mail
+ изображение
+ права доступа ("пользователь", "модератор" или "администратор")
+ randSalt (для хэширования пароля)
### Структура главной страницы сайта
На главной странице сайта можно выделить 4 основных блока: верхнее меню, область публикаций, боковое меню, концевик.
#### Верхнее меню
Верхнее меню состоит из ссылок навигации по категориям сайта, а также ссылки на главную страницу. Категории отображаются в порядке убывания по количеству публикаций в этой категории. Во избежание переполнения области верхнего меню количество отображаемых категорий органичено. Нажатие на ссылку категории перенаправляет пользователя на страницу данной категории.

Для авторизованных пользователей в правой части верхнего меню расположены ссылка на раздел администратора и иконка управления профилем. При нажатии на иконку профиля появлется выпадающее меню, состоящее из двух пунктов:
+ **"Профиль"** - позволяет отредактировать данные авторизовавшегося пользователя;
+ **"Выйти"** - завершает сеанс текущего пользователя.
#### Область публикаций
В этой области последовательно отображены все публикации. Каждая публикация сопровождается следующей информацией: название, автор, дата публикации, изображение, текст. Для более компактного отображения информации количество видимых символов текста публикации ограничено. При нажатии на заголовок публикации, изображение или кнопку "Читать дальше" пользователь перенаправляется на страницу публикации.
#### Боковое меню
Боковое меню состоит из формы авторизации пользователей (отображается только для неавторизованных пользователей), области поиска, ссылок навигации по категориям сайта (они отсортированы в порядке убывания по количеству публикаций) и рекламного виджета. Назначение ссылок навигации по категориям аналогично подобным ссылкам в верхнем меню за исключением того, что количество отображаемых категорий не ограничено. Поиск осуществляется по полю "тэги" публикаций и перенаправляет пользователя на страницу результатов поиска.
#### Концевик
В этом разделе расположен копирайт.
### Другие страницы сайта
#### Страница категории
На этой странице отображаются все публикации, соответствующие выбранной категории.
#### Страница поиска
На этой странице отображаются все публикации, удовлетворяющие поисковому запросу. 
#### Страница публикации
На этой странице отображается полная информация о выбранной публикации, в том числе полный текст публикации. Под публикацией расположена область комментариев.
#### Область комментариев
Этот раздел состоит из формы для создания нового комментария и комментариев к текущей записи.

Форма для создания нового комментария доступна только для авторизованных пользователей (неавторизованные пользователи не могут оставлять комментарии). Она состоит из трех полей:
+ имя пользователя
+ e-mail
+ текст комментария

Требования к введенным данным:
+ поля не должны быть пустыми;
+ поле **"e-mail"** должно содержать корректный e-mail.

Ниже располагаются комментарии к публикации, которые были одобрены. Каждый отображаемый комментарий сопровождается следующей информацией: имя пользователя, оставившего комментарий, дата и текст комментария. Сначала отображаются самые последние комментарии.
#### Отображение публикаций и комментариев в зависимости от статуса
На сайте отображаются только публикации, статус которых "опубликовано", и комментарии, статус которых "одобрен". Остальные публикации и комментарии хранятся в базе данных, но на сайте не отображаются. Их можно посмотреть в разделе администратора.
#### Статус комментария
При создании нового комментария его статус устанавливается в значение "ожидает проверки". После того, как комментарий проверен модератором, его статус может принимать одно из двух значений: "одобрен" или "заблокирован".
____
### Раздел администратора
Этот раздел доступен только для авторизованных пользователей. Чтобы перейти в раздел администратора, нажмите ссылку "Раздел администратора" на верхнем меню. Главная страница раздела администратора состоит из верхнего и бокового меню и блока вывода информации.
В верхнем меню находятся следующие ссылки:
+ **"Управление сайтом"** - переход на главную страницу раздела администратора;
+ **"Перейти на сайт"** - переход на главную страницу сайта;
+ **"Профиль"** (указан логин авторизовавшегося пользователя) - появляется выпадающее меню, состоящее из пунктов: 
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
+ количество публикаций, комментариев, категорий и пользователей (учитываются только публикации со статусом "опубликовано" и комментарии со статусом "одобрен");
+ график "Популярные категории" &ndash; показаны категории с наибольшим количеством публикаций;
+ график "Самые читаемые публикации" &ndash; показаны публикации с наибольшим количеством комментариев.
#### Раздел "Публикации"
В этом разделе есть два подраздела: "Все публикации" и "Добавить публикацию". Первый подраздел перенаправляет пользователя на страницу с таблицей, содержащей информацию обо всех публикациях из базы данных. В конце каждой строки таблицы расположены иконки, позволяющие редактировать или удалить публикацию. При нажатии на иконку редактирования пользователь перенаправляется на страницу с формой, в которой можно отредактировать публикацию, а также загрузить новое изображение.

Раздел "Добавить публикацию" перенаправляет пользователя на страницу с формой для создания новой публикации. Форма включает в себя следующие поля:
+ название категории (региона) &ndash; необходимо выбрать из выпадающего списка
+ название публикации
+ автор
+ изображение &ndash; необходимо выбрать файл
+ текст публикации
+ тэги &ndash; поле можно оставить пустым
+ статус &ndash; необходимо выбрать из выпадающего списка

Требования к введенным данным:
+ все поля, кроме **"тэги"** не могут быть пустыми;
+ категория (регион) должна соответствовать элементу из таблицы **"категории"** базы данных;
+ поле **"статус"** должно принимать одно из двух значений: "черновик", "опубликовано";
+ допускается не загружать изображение.
#### Раздел "Комментарии"
В этом разделе находится таблица с информацией обо всех комментариях. Таблица отсортирована: сверху находятся самые последние комментарии. В конце каждой строки таблицы расположены ссылки "разрешить" и "отклонить", а также иконка удаления комментария. При нажатии на ссылки "разрешить" и "отклонить" статус соответствующего комментария меняется на "одобрен" и "заблокирован" соответственно. При нажатии на иконку удаления комментария комментарий удаляется из базы данных. Если кликнуть по названию публикации, к которой относится комментарий, откроется страница этой публикации.
#### Раздел "Регионы"
В левой части раздела расположена форма для создания новой категории (региона). В правой части расположена таблица с информацией о всех категориях из базы данных. В конце каждой строки таблицы находятся иконки для редактирования и удаления категории. При нажатии на иконку редактирования категории в левой части раздела появляется форма редактирования категории.
#### Раздел "Пользователи"
Раздел состоит из двух подразделов: "Все пользователи" и "Добавить пользователя". Первый подраздел перенаправляет пользователя на страницу, которая содержит таблицу с информацией обо всех пользователях, хранящихся в базе данных. В конце каждой строки расположены иконки редактирования и удаления пользователя. При нажатии на иконку редактирования открывается страница с формой, где можно изменить данные пользователя (кроме логина), а также загрузить новое изображение. При нажатии на иконку удаления пользователь удаляется из базы данных.

Подраздел "Добавить пользователя" перенаправляет пользователя на страницу с формой для создания нового пользователя. Форма включает следующие поля:
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
+ пароль
+ имя
+ фамилия
+ e-mail
+ изображение
+ права доступа

Требования к введенным данным такие же, как и при добавлении нового пользователя.
____
### Корректность данных
Все данные, введенные пользователями, проверяются на корректность на стороне браузера и на стороне сервера. В случае некорректных данных выводится сообщение об ошибке.
____
### Безопасность
Для предотвращения SQL-инъекций во всех введенных пользователями данных производится экранирование специальных символов. Доступ к разделу администратора и к добавлению новых комментариев открыт только для авторизованных пользователей. В дальнейшем планируется ограничить права доступа пользователей в разделе администратора (в зависимости от типа их учетных записей).
____
## Используемые материалы
При разработке проекта использовались материалы из видеокурса PHP for Beginners - Become a PHP Master - CMS Project.
Ссылка на курс: [здесь](https://www.udemy.com/course/php-for-complete-beginners-includes-msql-object-oriented)
