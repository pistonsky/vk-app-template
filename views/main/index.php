<h1>Done!</h1>

<p>If everything went fine, you should see your first and last name here:</p>
<pre><?= \Yii::$app->user->identity->first_name . " " . \Yii::$app->user->identity->last_name ?></pre>