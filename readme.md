В этом блоге реализована возможность добавления, редактирования и удаления статей и комментариев, зарегистрированными пользователями. Не зарегистрированные пользователи могут только просматривать статьи и комментарии.

<a href="https://ibb.co/D76csV5"><img src="https://i.ibb.co/D76csV5/main.png" alt="main" border="0"></a>
<a href="https://ibb.co/hVBJWJq"><img src="https://i.ibb.co/hVBJWJq/article-add.png" alt="article-add" border="0"></a> 

Пользователь с правами админа может так же банить на время других пользователей, и просматривать статистику по добавленным статьям, комментариям, тегам.

<a href="https://ibb.co/YdTvhgD"><img src="https://i.ibb.co/YdTvhgD/stat.png" alt="stat" border="0"></a>

Так же на сайте реализован полнотекстовый поиск по статьям. Применяемая в проекте СУБД: *PostgreSQL*.

Для запуска проекта выполнить:

**php artisan migrate**

**php artisan db:seed**
 
После этого в БД появится две учётки. Учётная запись администратора:

**Login**: Admin

**Password**: secret

Учётная запись обычного пользователя:

**Login**: User

**Password**: secret

Добавить новые тестовые статьи и новых пользователей, также можно, зупустив команду:

**phpunit**