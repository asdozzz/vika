# vika
1)Для создание класса-конфига сущности нужно использовать артисан команду :
php artisan fm:build vEssence {Название модуля} {название сущности} 

Пример: php artisan fm:build vEssence Users UsersRoles
Создаст файл packages/Asdozzz/Users/src/Essences/UsersRoles.php

2) Для создания файлов для CRUD нужно вначале выполнить пункт 1 из этого файла, для создания файла-конфига, произвести все необходимые настройки. Далее выполним след. действия:

php artisan fm:build vEssence {Название модуля} {название сущности} 

Пример:  php artisan fm:build vModule Users UsersRoles
Генератор будет искать файл по пути packages/Asdozzz/Users/src/Essences/UsersRoles.php

На основе него будет создавать заготовки файлов для CRUD + миграцию




