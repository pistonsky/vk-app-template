<?php

namespace app\components;

use yii\log\FileTarget;

class FileTargetShort extends FileTarget
{
	public function formatMessage($message)
	{
		return $message[0];
	}
}