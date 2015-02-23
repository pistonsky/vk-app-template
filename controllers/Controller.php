<?php

namespace app\controllers;

use app\models\Users;
use app\models\LogRequests;
use app\models\LogSession;
use app\filters\VkAuth;

class Controller extends \yii\rest\Controller
{
	protected $uid;
	private $auth_key;
	public $layout = "@app/views/layouts/iframe";

	public function behaviors()
	{
		return [
			'authenticator' => [
				'class' => VkAuth::className(),
			],
			'corsFilter' => [
				'class' => \yii\filters\Cors::className(),
			]
		];
	}

	private function udate($format, $utimestamp = null)
	{
		if (is_null($utimestamp))
			$utimestamp = microtime(true);
	 
		$timestamp = floor($utimestamp);
		$milliseconds = round(($utimestamp - $timestamp) * 1000000);
	 
		return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
	}

	/**
	 * Return data to browser as JSON and end application.
	 * @param array $data
	 */
	protected function renderJSON($data)
	{
		header('Content-type: application/json');

		echo json_encode(array_merge($data,['time'=>\Yii::getLogger()->getElapsedTime()*1000]));

		\Yii::info("\n" . json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), 'api');

		\Yii::info(json_encode([
				'timestamp' => $this->udate('Y-m-d\Th:i:s.u\Z'),
				'total_time_ms' => \Yii::getLogger()->getElapsedTime()*1000,
				'total_db_queries_count' => \Yii::getLogger()->getDbProfiling()[0],
				'total_db_queries_time_ms' => \Yii::getLogger()->getDbProfiling()[1]*1000,
			]), \Yii::$app->request->url);
		\Yii::endProfile("apiTotalTimeBenchmark \n\tTotal time: " . \Yii::getLogger()->getElapsedTime() . "\n\tAll profiling results:\n" . json_encode(\Yii::getLogger()->getProfiling(), JSON_PRETTY_PRINT), \Yii::$app->request->url);

		exit;
	}

	public function beforeAction($action)
	{
		parent::beforeAction($action);

		if (\Yii::$app->request->isPost) 
		{
			if (!isset($_POST['uid']) || (($this->uid = $_POST['uid']) == ''))
			{
				echo json_encode([
					'error' => [
						'code' => InsufficientInputParameters,
						'msg' => 'uid is not set'
					]
				]);
				return false;
			}

			if (!isset($_POST['auth_key']) || (($this->auth_key = $_POST['auth_key']) == ''))
			{
				echo json_encode([
					'error' => [
						'code' => InsufficientInputParameters,
						'msg' => 'auth_key is not set'
					]
				]);
				return false;
			}
		}

		return true;
	}

	protected function checkInputParameters($names)
	{
		$vars = [];
		foreach ($names as $name)
		{
			if (!isset($_POST[$name]) || ((${$name} = $_POST[$name]) == ''))
			{
				$this->error(InsufficientInputParameters, $name . ' is not set');
			}
			$vars[] = ${$name};
		}
		return $vars;
	}

	protected function getUser()
	{
		if (!$user_model = \Yii::$app->user->identity)
		{
			$this->error(UserNotFound, 'user with ID ' . $this->uid . ' is not found in users table');
		}
		return $user_model;
	}

	protected function error($code, $msg)
	{
		$this->renderJSON([
				'error' => [
					'code' => $code,
					'msg' => $msg
				]
			]);
	}
}