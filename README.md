# CityProject
start from index.php (изменение 1: единая точка входа index.php)
It contains city summary, links to the further city info, photopage and the admin panel login

Изменения: 
1) единая точка входа index.php
2) все члены классов $errors для хранения ошибок, возникавших во время создания объектов таких классов, заменены на Throwable Exceptions
3) везде используются namespace согласно PSR-4
4) проверка залогинен ли уже пользователь оптимизирована по времени: используется (небольшая) таблица БД с активными пользователями
