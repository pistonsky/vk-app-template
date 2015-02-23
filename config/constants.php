<?php 

// глобальные константы

define('app_id', '');
define('app_secret', '');

define('RATE_LIMIT_PER_USER_PER_SECOND', 10000);

// коды ошибок

define('WrongAuthKey', 200); // auth_key неверный
define('InsufficientInputParameters', 201); // недостаточно входных параметров, какой-то из необходимых параметров отсутствует
define('UserNotFound',400); // юзер, указанный в uid запроса, не найден в таблице users
define('TooManyRequests',427);