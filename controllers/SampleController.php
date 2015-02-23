<?php

namespace app\controllers;

use app\models\Users;

class SampleController extends Controller
{
	public function actionGet()
	{
		// you can get incoming parameters this way - if they are mandatory
			list($some_mandatory_param) = $this->checkInputParameters(['some_mandatory_param']);

		// you can output JSON this way - and it will also set headers properly
		$this->renderJSON([
			'response' => [
				'data' => [
					'timestamp' => time()
				]
			]
		]);
	}
}