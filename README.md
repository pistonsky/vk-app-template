vk.com application template written on yii2 and backbone.js
===========================================================

How to setup
============
0. Clone;
1. chmod 0777 runtime; chmod 0777 web/assets;
2. Create database;
3. Edit config/db.php - fill in your database settings;
4. Edit config/params.php - fill in your app_id and app_secret;
5. Edit config/web.php - fill in urlManager rules;
6. Create users table by typing in terminal "./yii migrate";

You're done! Go ahead and modify controllers/MainController.php and corresponding views! You can use
	\Yii::$app->user->identity->id
to get the id of the current user, as well as these properties:
	\Yii:$app->user->identiry->first_name
	\Yii:$app->user->identiry->last_name

Автор кода: Цыганков Александр <tsygankov.aleksandr@gmail.com>